<?php

namespace App\Filament\Resources;

use App\Enums\Genre;
use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Traits\TrashedFilterActive;
use App\Helpers\TranslatableAttributes;
use App\Models\Patient;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Maggomann\FilamentModelTranslator\Contracts\Translateable;
use Maggomann\FilamentModelTranslator\Traits\HasTranslateableResources;

class PatientResource extends Resource implements Translateable
{
    use HasTranslateableResources, TrashedFilterActive;

    protected static ?string $translateablePackageKey = '';

    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'fluentui-patient-32';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    /**
     * Defines the form schema for creating or editing a patient.
     *
     * @param  Form  $form  The form instance to be configured.
     * @return Form Returns the configured form instance.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema(TranslatableAttributes::translateLabels(self::$model, [
                Forms\Components\TextInput::make('first_name')
                    ->required(),
                Forms\Components\TextInput::make('last_name')
                    ->required(),
                Forms\Components\TextInput::make('dui')
                    ->unique(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\Select::make('genre')
                    ->options(Genre::options())
                    ->enum(Genre::class)
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required(),
                Forms\Components\TextInput::make('cellphone')
                    ->tel()
                    ->required(),
                Forms\Components\TextInput::make('address')
                    ->required(),
                Forms\Components\TextInput::make('occupation')
                    ->required(),
                Forms\Components\DatePicker::make('birthdate')
                    ->required(),
                Forms\Components\Select::make('state_id')
                    ->label('Departamento')
                    ->options(State::pluck('name', 'id'))
                    ->reactive()
                    ->searchable()
                    ->required()
                    ->afterStateUpdated(function (callable $set) {
                        $set('municipality_id', null);
                    })
                    ->formatStateUsing(fn(Patient $patient) => $patient->municipality?->state_id),
                Forms\Components\Select::make('municipality_id')
                    ->required()
                    ->options(function (callable $get) {
                        $stateId = $get('state_id');
                        if ($stateId) {
                            return State::find($stateId)->municipalities->pluck('name', 'id');
                        }

                        return [];
                    })
                    ->searchable()
                    ->disabled(fn(callable $get) => ! $get('state_id')),
            ]));
    }

    /**
     * Defines the table schema for displaying patients.
     *
     * @param  Table  $table  The table instance to be configured.
     * @return Table Returns the configured table instance.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns(TranslatableAttributes::translateLabels(self::$model, [
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dui')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('genre')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cellphone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('occupation')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('birthdate')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('municipality.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }

    /**
     * Retrieves the Eloquent query used for fetching records of this resource.
     *
     * @return Builder The Eloquent query builder instance.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    /**
     * Retrieves any relations associated with this resource.
     *
     * @return array An array of relation managers associated with this resource.
     */
    public static function getRelations(): array
    {
        return [
            // Add relation managers here if needed.
        ];
    }

    /**
     * Retrieves the pages associated with this resource.
     *
     * @return array An array of page classes associated with this resource.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'view' => Pages\ViewPatient::route('/{record}'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
            'emergencyContacts' => Pages\ManageEmergencyContacts::route('/{record}/emergencyContacts'),
            'medicRecords' => Pages\ManageMedicRecords::route('/{record}/medicRecords'),
            'toothRecords' => Pages\ManageToothRecords::route('/{record}/toothRecords'),
        ];
    }

    /**
     * Generates the sub-navigation items for the record view page.
     *
     * @param  Page  $page  The page instance for which to generate navigation items.
     * @return array An array of navigation items for the record sub-navigation.
     */
    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewPatient::class,
            Pages\EditPatient::class,
            Pages\ManageEmergencyContacts::class,
            Pages\ManageMedicRecords::class,
            Pages\ManageToothRecords::class,
        ]);
    }
}
