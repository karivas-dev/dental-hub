<?php

namespace App\Filament\Resources\AppointmentResource\RelationManagers;

use App\Models\Appointment;
use App\Models\DentalService;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;

class DentalServicesRelationManager extends RelationManager
{
    protected static string $relationship = 'dentalServices';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('pivot.quantity')
                    ->label('Quantity'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Add')
                    ->modalHeading(fn(Tables\Actions\AttachAction $action) => "Add {$action->getModelLabel()}")
                    ->modalSubmitActionLabel('Add')
                    ->attachAnother(false)
                    ->form(fn(Tables\Actions\AttachAction $action) => [
                        Forms\Components\ToggleButtons::make('recordId')
                            ->options(DentalService::whereNotIn('id',
                                /** @var Appointment */ $this->ownerRecord->dentalServices->pluck('id')
                            )->pluck('name', 'id')->toArray())
                            ->hiddenLabel()
                            ->inline()
                            ->required()
                            ->validationAttribute('dental service'),
                        Forms\Components\TextInput::make('quantity')
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
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->modalHeading(fn() => 'Edit Quantity')
                    ->modalSubmitActionLabel('Update')
                    ->form(fn(DentalService $dental_service) => [
                        Forms\Components\TextInput::make('quantity')
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
                    ->label('Remove')
                    ->modalHeading(fn(Tables\Actions\DetachAction $action) => "Remove {$action->getRecordTitle()}"),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

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
