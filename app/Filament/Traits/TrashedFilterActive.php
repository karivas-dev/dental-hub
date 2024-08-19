<?php

namespace App\Filament\Traits;

use App\Models\Branch;
use Filament\Tables;
use Filament\Tables\Table;

trait TrashedFilterActive
{
    public static function isActiveBooleanColumn($table): Tables\Columns\IconColumn
    {
        return Tables\Columns\IconColumn::make('deleted')
            ->boolean()
            ->alignCenter()
            ->getStateUsing(fn(Branch $branch) => $branch->trashed())
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
