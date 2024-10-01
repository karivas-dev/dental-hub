<?php

namespace App\Helpers;

use Filament\Forms\Components\Field;
use Filament\Tables\Columns\Column;

class TranslatableAttributes
{
    public static function translateLabels(string $model, array $array)
    {
        return collect($array)
            ->each(fn (Field|Column $field) => $field->label($model::transAttribute($field->getName())))
            ->toArray();
    }
}
