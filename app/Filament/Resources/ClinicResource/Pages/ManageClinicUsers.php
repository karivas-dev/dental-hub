<?php

namespace App\Filament\Resources\ClinicResource\Pages;

use App\Filament\Resources\ClinicResource;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ManageClinicUsers extends ManageRelatedRecords
{
    protected static string $resource = ClinicResource::class;

    protected static string $relationship = 'users';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationLabel(): string
    {
        return 'Users';
    }

    public function form(Form $form): Form
    {
        $form = UserResource::form($form);
        $form->getComponent('branch', true)->visible();
        $form->getComponent('role', true)->visible();
        return $form;
    }

    public function table(Table $table): Table
    {
        $table = UserResource::table($table)
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));

        $table->getColumn('role.type')->visible();
        $table->getColumn('branch.name')->visible();
        return $table;
    }
}
