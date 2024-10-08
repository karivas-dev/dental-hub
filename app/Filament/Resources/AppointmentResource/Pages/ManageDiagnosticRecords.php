<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\Diagnosis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageDiagnosticRecords extends ManageRelatedRecords
{
    protected static string $resource = AppointmentResource::class;

    protected static string $relationship = 'diagnoses';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Get the navigation label for the diagnostic records.
     */
    public static function getNavigationLabel(): string
    {
        return 'Diagnoses';
    }

    /**
     * Get the form schema for creating or editing a diagnosis record.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('Diagnosis Record')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    /**
     * Get the table schema for displaying diagnostic records.
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Diagnosis Record')
            ->paginated(Diagnosis::where('patient_id', $this->record->getKey())->count() > 10)
            ->columns([
                Tables\Columns\TextColumn::make('details'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DissociateAction::make(),
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
