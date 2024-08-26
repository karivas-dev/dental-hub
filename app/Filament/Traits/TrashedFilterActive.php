<?php

namespace App\Filament\Traits;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

trait TrashedFilterActive
{
    public static function isDeletedBooleanColumn($table): Tables\Columns\IconColumn
    {
        return Tables\Columns\IconColumn::make('active')
            ->boolean()
            ->alignCenter()
            ->getStateUsing(fn(Model $model) => !$model->trashed())
            ->visible(fn() => self::trashedFilterActive($table));
    }

    public static function trashedFilterActive(Table $table): bool
    {
        $trashedFilter = $table->getFilters()['trashed'] ?? null;

        if ($trashedFilter && isset($trashedFilter->getState()['value'])) {
            return $trashedFilter->getState()['value'] != null;
        }

        return false;
    }
}
