<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages;
use App\Filament\Resources\BranchResource\RelationManagers;
use App\Filament\Traits\TrashedFilterActive;
use App\Helpers\TranslatableAttributes;
use App\Models\Branch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Maggomann\FilamentModelTranslator\Contracts\Translateable;
use Maggomann\FilamentModelTranslator\Traits\HasTranslateableResources;

class BranchResource extends Resource implements Translateable
{
    use TrashedFilterActive, HasTranslateableResources;

    protected static ?string $translateablePackageKey = '';

    protected static ?string $model = Branch::class;

    protected static ?string $navigationIcon = 'bi-building-add';

    /**
     * Determines if this resource should be registered in the navigation.
     *
     * @return bool Returns true if the authenticated user is not an admin.
     */
    public static function shouldRegisterNavigation(): bool
    {
        return ! Auth::user()->admin;
    }

    /**
     * Defines the form schema for creating or editing a branch.
     *
     * @param  Form  $form  The form instance to be configured.
     * @return Form Returns the configured form instance.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema(TranslatableAttributes::translateLabels(self::$model, [
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Toggle::make('main')
                    ->required(),
                Forms\Components\TextInput::make('address')
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\Select::make('municipality_id')
                    ->relationship('municipality', 'name')
                    ->required(),
            ]));
    }

    /**
     * Defines the table schema for displaying branches.
     *
     * @param  Table  $table  The table instance to be configured.
     * @return Table Returns the configured table instance.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns(TranslatableAttributes::translateLabels(self::$model, [
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('main')
                    ->boolean(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('municipality.name')
                    ->searchable()
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
            'index' => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            'view' => Pages\ViewBranch::route('/{record}'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}
