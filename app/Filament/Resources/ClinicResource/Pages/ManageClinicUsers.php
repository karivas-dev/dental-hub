<?php

namespace App\Filament\Resources\ClinicResource\Pages;

use App\Filament\Resources\ClinicResource;
use App\Filament\Resources\UserResource;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;

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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ]);

        $table->getColumn('role.type')->visible();
        $table->getColumn('branch.name')->visible();

        return $table;
    }
}
