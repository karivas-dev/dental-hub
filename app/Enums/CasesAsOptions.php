<?php

namespace App\Enums;

trait CasesAsOptions
{
    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($value, $key) => [$value->value => $value->value])->toArray();
    }
}
