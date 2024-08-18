<?php

namespace App\Filament\Resources\ClinicResource\Pages;

use App\Filament\Resources\BranchResource;
use App\Filament\Resources\ClinicResource;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;

class ManageClinicBranches extends ManageRelatedRecords
{
    protected static string $resource = ClinicResource::class;

    protected static string $relationship = 'branches';

    protected static ?string $navigationIcon = 'bi-building-add';

    public static function getNavigationLabel(): string
    {
        return 'Branches';
    }

    public function form(Form $form): Form
    {
        return BranchResource::form($form);
    }

    public function table(Table $table): Table
    {
        return BranchResource::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
