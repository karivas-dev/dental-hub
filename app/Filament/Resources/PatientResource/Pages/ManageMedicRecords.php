<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use App\Enums\kinship;
use App\Enums\System;
use Closure;
use Filament\Forms\Get;
use Filament\Actions;
use App\Models\MedicRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Traits\TrashedFilterActive;

class ManageMedicRecords extends ManageRelatedRecords
{
    use TrashedFilterActive;
    protected static string $resource = PatientResource::class;

    protected static string $relationship = 'medicRecords';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Medic Records';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('system')
                    ->options(System::options())
                    ->enum(System::class)
                    ->required(),
                Forms\Components\Checkbox::make('hereditary')
                    ->live(),
                Forms\Components\Select::make('kinship')
                    ->options(Kinship::options())
                    ->enum(Kinship::class)
                    ->required(fn (Get $get): bool => $get('hereditary'))
                    ->hidden(fn (Get $get): bool => ! $get('hereditary')),
                Forms\Components\TextInput::make('treatment')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('details')
                    ->required()
                    ->maxLength(255),
                    Forms\Components\DatePicker::make('date')
                    ->required()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Medic Records')
            ->paginated(MedicRecord::where('patient_id', $this->record->getKey())->count() > 10)
            ->columns([
                Tables\Columns\TextColumn::make('system'),
                Tables\Columns\TextColumn::make('kinship')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('hereditary')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('details'),
                Tables\Columns\TextColumn::make('treatment'),
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
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
    }
}
