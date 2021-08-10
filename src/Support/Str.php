<?php

declare(strict_types=1);

namespace App\Support;

class Str
{
    /**
     * Mimic Laravel's Str::lower() method.
     *
     * @link https://github.com/illuminate/support/blob/e0855d594d106c4acab2e614f7250f1276be5159/Str.php#L371
     */
    public static function lower(?string $value): string
    {
        return mb_strtolower($value ?? '', 'UTF-8');
    }

    /**
     * Mimic Laravel's Str::snake() method.
     *
     * @link https://github.com/illuminate/support/blob/e0855d594d106c4acab2e614f7250f1276be5159/Str.php#L739
     */
    public static function snake(string $value, string $delimiter = '_'): string
    {
        $value = preg_replace('/\s+/u', '', ucwords($value));

        return static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value ?? ''));
    }
}
