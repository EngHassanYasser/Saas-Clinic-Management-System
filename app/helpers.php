<?php
if (! function_exists('enumToArray')) {
    function enumToArray(string $enum): array
    {
        return collect($enum::cases())
            ->mapWithKeys(fn (BackedEnum $case) => [
                $case->name => $case->value
            ])
            ->toArray();
    }
}