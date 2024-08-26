<?php

namespace App\Filament\Resources;

use App\Enums\AppointmentStatus;
use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Traits\TrashedFilterActive;
use App\Models\Appointment;
use App\Models\Patient;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentResource extends Resource
{
    use TrashedFilterActive;

    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'fluentui-document-text-clock-20-o';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('date')
                    ->required()
                    ->after('now'),
                Forms\Components\TextInput::make('details'),
                Forms\Components\ToggleButtons::make('status')
                    ->options(AppointmentStatus::options())
                    ->icons([
                        AppointmentStatus::Programada->value => 'heroicon-o-clock',
                        AppointmentStatus::Reagendada->value => 'tabler-clock-exclamation',
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                    ->visible(fn () => Filament::getTenant()->main),
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
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'view' => Pages\ViewAppointment::route('/{record}'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewAppointment::class,
            Pages\EditAppointment::class,
        ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
