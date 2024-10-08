<?php

namespace App\Filament\Resources;

use App\Enums\AppointmentStatus;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers\DentalServicesRelationManager;
use App\Filament\Traits\TrashedFilterActive;
use App\Helpers\TranslatableAttributes;
use App\Models\Appointment;
use App\Models\Patient;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Maggomann\FilamentModelTranslator\Contracts\Translateable;
use Maggomann\FilamentModelTranslator\Traits\HasTranslateableResources;

class AppointmentResource extends Resource implements Translateable
{
    use HasTranslateableResources, TrashedFilterActive;

    protected static ?string $translateablePackageKey = '';

    // The model associated with this resource
    protected static ?string $model = Appointment::class;

    // Icon displayed in the navigation for this resource
    protected static ?string $navigationIcon = 'fluentui-document-text-clock-20-o';

    // Position of the sub-navigation in the layout
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    /**
     * Define the form schema for creating and editing appointments.
     *
     * @param  Form  $form  The form instance to build upon.
     * @return Form The modified form instance.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema(TranslatableAttributes::translateLabels(self::$model, [
                Forms\Components\DateTimePicker::make('date')
                    ->required()
                    ->after('now'),
                Forms\Components\TextInput::make('details'),
                Forms\Components\ToggleButtons::make('status')
                    ->options(AppointmentStatus::options())
                    ->icons([
                        AppointmentStatus::Programada->value => 'heroicon-o-clock',
                        AppointmentStatus::Reagendada->value => 'heroicon-o-exclamation-circle',
                        AppointmentStatus::Cancelada->value => 'heroicon-o-x-mark',
                        AppointmentStatus::Completada->value => 'heroicon-o-check-circle',
                    ])
                    ->inline()
                    ->enum(AppointmentStatus::class)
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->rules(['min:0'])
                    ->requiredIf('status', AppointmentStatus::Completada->value),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name', modifyQueryUsing: fn($query) => $query->whereRelation('role', 'type',
                        'Doctor'))
                    ->label('Doctor'),
                Forms\Components\Select::make('patient_id')
                    ->relationship('patient')
                    ->getOptionLabelFromRecordUsing(fn(Patient $patient
                    ) => "$patient->first_name $patient->last_name - $patient->email")
                    ->searchable(['first_name', 'last_name', 'email'])
                    ->required(),
            ]));
    }

    /**
     * Define the table schema for displaying appointments.
     *
     * @param  Table  $table  The table instance to build upon.
     * @return Table The modified table instance.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns(TranslatableAttributes::translateLabels(self::$model, [
                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('details')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('patient')
                    ->getStateUsing(fn(Appointment $appointment
                    ) => "{$appointment->patient->first_name} {$appointment->patient->last_name}"),
                Tables\Columns\TextColumn::make('branch.name')
                    ->numeric()
                    ->sortable()
                    ->visible(fn() => Filament::getTenant()->main),
                self::isDeletedBooleanColumn($table),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]))
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // Enable editing of appointments
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Get the relation managers for this resource.
     *
     * @return array An array of relation manager classes.
     */
    public static function getRelations(): array
    {
        return [
            DentalServicesRelationManager::class,
        ];
    }

    /**
     * Get the pages for this resource.
     *
     * @return array An array of page route definitions.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            //'view' => Pages\ViewAppointment::route('/{record}'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }

    /**
     * Get the eloquent query for this resource, excluding certain global scopes.
     *
     * @return Builder The modified Eloquent query builder.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
