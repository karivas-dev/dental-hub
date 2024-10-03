<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use App\Helpers\TranslatableAttributes;
use App\Models\ToothRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;

class ManageToothRecords extends ManageRelatedRecords
{
    protected static string $resource = PatientResource::class;

    protected static string $relationship = 'toothRecords';

    protected static ?string $navigationIcon = 'hugeicons-dental-broken-tooth';

    protected static ?string $navigationLabel = 'Expedientes dentales';
    protected static ?string $title = 'Manejar expedientes dentales';
    protected ?string $heading = 'Expedientes dentales del paciente';

    public function form(Form $form): Form
    {
        return $form
            ->schema(TranslatableAttributes::translateLabels(ToothRecord::class, [
                Forms\Components\DateTimePicker::make('date')
                    ->required(),

                Forms\Components\TextInput::make('details')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('tooth_id')
                    ->relationship('tooth', 'name')
                    ->required(),
            ]));
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Tooth Records')
            ->paginated(ToothRecord::where('patient_id', $this->record->getKey())->count() > 10)
            ->columns(TranslatableAttributes::translateLabels(ToothRecord::class, [
                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tooth.name'),
                Tables\Columns\TextColumn::make('details'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]))
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->modelLabel(fn() => strtolower(trans_choice('filament-model.models.tooth_record', 1)))
            ->pluralModelLabel(fn() => strtolower(trans_choice('filament-model.models.tooth_record', 2)));
    }
}
