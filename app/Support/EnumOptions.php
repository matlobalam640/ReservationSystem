<?php

namespace App\Support;

use BackedEnum;

class EnumOptions
{
    /**
     * @param  class-string  $enumClass
     * @return array<string, string>
     */
    public static function from(string $enumClass): array
    {
        return collect($enumClass::cases())
            ->mapWithKeys(function (BackedEnum $case): array {
                $label = method_exists($case, 'label')
                    ? $case->label()
                    : str($case->name)->headline()->toString();

                return [$case->value => $label];
            })
            ->all();
    }
}
