<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClinicResource\Pages;
use App\Models\Clinic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClinicResource extends Resource
{
    protected static ?string $model = Clinic::class;

    protected static ?string $label = 'Clínicas';

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static bool $isScopedToTenant = false;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    /**
     * Defines the form schema for creating or editing a clinic.
     *
     * @param  Form  $form  The form instance to be configured.
     * @return Form Returns the configured form instance.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
            ]);
    }

    /**
     * Defines the table schema for displaying clinics.
     *
     * @param  Table  $table  The table instance to be configured.
     * @return Table Returns the configured table instance.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean()
                    ->getStateUsing(fn($record) => ! $record->trashed()),
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
        return parent::getEloquentQuery()->whereNot('id', 1)
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
            'index' => Pages\ListClinics::route('/'),
            'create' => Pages\CreateClinic::route('/create'),
            'view' => Pages\ViewClinic::route('/{record}'),
            'edit' => Pages\EditClinic::route('/{record}/edit'),
            'branches' => Pages\ManageClinicBranches::route('/{record}/branches'),
            'users' => Pages\ManageClinicUsers::route('/{record}/users'),
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
            Pages\ViewClinic::class,
            Pages\EditClinic::class,
            Pages\ManageClinicBranches::class,
            Pages\ManageClinicUsers::class,
        ]);
    }
}
