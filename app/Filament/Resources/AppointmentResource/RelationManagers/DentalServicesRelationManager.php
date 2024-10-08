<?php

namespace App\Filament\Resources\AppointmentResource\RelationManagers;

use App\Helpers\TranslatableAttributes;
use App\Models\Appointment;
use App\Models\DentalService;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Maggomann\FilamentModelTranslator\Contracts\Translateable;
use Maggomann\FilamentModelTranslator\Traits\HasTranslateableRelationManager;

class DentalServicesRelationManager extends RelationManager implements Translateable
{
    use HasTranslateableRelationManager;

    protected static ?string $translateablePackageKey = '';

    protected static string $relationship = 'dentalServices';

    /**
     * Define the table for displaying dental services related to appointments.
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns(TranslatableAttributes::translateLabels(DentalService::class, [
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('pivot.quantity')
                    ->label('Quantity'),
            ]))
            ->filters([
                // Add any filters if necessary
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Añadir')
                    ->modalHeading(fn (Tables\Actions\AttachAction $action) => "Añadir {$action->getModelLabel()}")
                    ->modalSubmitActionLabel('Añadir')
                    ->attachAnother(false)
                    ->form(fn (Tables\Actions\AttachAction $action) => [
                        Forms\Components\ToggleButtons::make('recordId')
                            ->options(DentalService::whereNotIn('id',
                                /** @var Appointment */ $this->ownerRecord->dentalServices->pluck('id')
                            )->pluck('name', 'id')->toArray())
                            ->hiddenLabel()
                            ->inline()
                            ->required()
                            ->validationAttribute('dental service'),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Cantidad')
                            ->numeric()
                            ->minValue(1)
                            ->rules(['min:1'])
                            ->default(1)
                            ->suffixAction(Action::make('increment')
                                ->icon('heroicon-o-plus-circle')
                                ->color(Color::Gray)
                                ->livewireClickHandlerEnabled(false)
                                ->alpineClickHandler("incrementQuantity(\$el, 'quantity')")
                            )
                            ->prefixAction(Action::make('decrement')
                                ->icon('heroicon-o-minus-circle')
                                ->color(Color::Gray)
                                ->livewireClickHandlerEnabled(false)
                                ->alpineClickHandler("decrementQuantity(\$el, 'quantity')")
                            )
                            ->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('editQuantity')
                    ->label('Editar')
                    ->icon('heroicon-m-pencil-square')
                    ->modalHeading(fn () => 'Editar cantidad')
                    ->modalSubmitActionLabel('Actualizar')
                    ->form(fn (DentalService $dental_service) => [
                        Forms\Components\TextInput::make('quantity')
                            ->label('Cantidad')
                            ->numeric()
                            ->minValue(1)
                            ->rules(['min:1'])
                            ->default($dental_service->pivot->quantity)
                            ->suffixAction(Action::make('increment')
                                ->icon('heroicon-o-plus-circle')
                                ->color(Color::Gray)
                                ->livewireClickHandlerEnabled(false)
                                ->alpineClickHandler("incrementQuantity(\$el, 'quantity')")
                            )
                            ->prefixAction(Action::make('decrement')
                                ->icon('heroicon-o-minus-circle')
                                ->color(Color::Gray)
                                ->livewireClickHandlerEnabled(false)
                                ->alpineClickHandler("decrementQuantity(\$el, 'quantity')")
                            )
                            ->required(),
                    ])
                    ->action(function (DentalService $record, array $data) {
                        $this->ownerRecord->dentalServices()->updateExistingPivot($record->id, [
                            'quantity' => $data['quantity'],
                        ]);
                    }),
                Tables\Actions\DetachAction::make()
                    ->label('Remover')
                    ->modalHeading(fn (Tables\Actions\DetachAction $action) => "Remove {$action->getRecordTitle()}"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Define the form schema for creating or editing dental services.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
