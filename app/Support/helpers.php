<?php

declare(strict_types=1);

namespace App\Support;

use BackedEnum;
use Filament\Facades\Filament;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

if (! function_exists('local_now')) {
    function local_now(): Carbon
    {
        return now(config('app.local_timezone'));
    }
}

if (! function_exists('local_date')) {
    function local_date(Carbon|string|null $date = null, string $format = 'd/m/Y', bool $short = false): ?string
    {
        if (! $date) {
            return null;
        }

        if (! $date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        return $date
            ->timezone(config('app.local_timezone'))
            ->when(
                fn (Carbon $carbon) => $short && $carbon->isCurrentYear(),
                fn (Carbon $carbon) => $carbon->translatedFormat(preg_replace('/[\/\-]?\s?[Yy][\/\-]?/', '', $format)),
                fn (Carbon $carbon) => $carbon->translatedFormat($format)
            );
    }
}

if (! function_exists('local_time')) {
    function local_time(Carbon|string|null $time = null, string $format = 'H:i'): ?string
    {
        if (! $time) {
            return null;
        }

        if (! $time instanceof Carbon) {
            $time = Carbon::parse($time);
        }

        return $time
            ->timezone(config('app.local_timezone'))
            ->translatedFormat($format);
    }
}

if (! function_exists('local_datetime')) {
    function local_datetime(Carbon|string|null $datetime = null, string $format = 'd/m/Y H:i', bool $short = false): ?string
    {
        if (! $datetime) {
            return null;
        }

        if (! $datetime instanceof Carbon) {
            $datetime = Carbon::parse($datetime);
        }

        return $datetime
            ->timezone(config('app.local_timezone'))
            ->when(
                fn (Carbon $carbon) => $short && $carbon->isCurrentYear(),
                fn (Carbon $carbon) => $carbon->translatedFormat(preg_replace('/[\/\-]?\s?[Yy][\/\-]?/', '', $format)),
                fn (Carbon $carbon) => $carbon->translatedFormat($format)
            );
    }
}

if (! function_exists('array_remove')) {
    function array_remove(array &$arr, $key)
    {
        if (array_key_exists($key, $arr)) {
            $val = $arr[$key];
            unset($arr[$key]);

            return $val;
        }

        return null;
    }
}

if (! function_exists('md')) {
    function md(?string $string = null): ?HtmlString
    {
        if (! $string) {
            return null;
        }

        return Str::of($string)->markdown()->toHtmlString();
    }
}

if (! function_exists('blade')) {
    function blade(?string $string = null, array $data = [], bool $deleteCachedView = false): ?string
    {
        if (! $string) {
            return null;
        }

        return Blade::render($string, $data, $deleteCachedView);
    }
}

if (! function_exists('enum_equals')) {
    function enum_equals(BackedEnum|array $enum, BackedEnum|string|int|null $value): bool
    {
        if (is_array($enum)) {
            return array_reduce($enum, fn (bool $carry, BackedEnum $enum) => $carry || enum_equals($enum, $value), false);
        }

        if (! $value instanceof BackedEnum) {
            return $enum::tryFrom($value) === $enum;
        }

        return $enum === $value;
    }
}

if (! function_exists('tenant')) {
    /**
     * @template TValue
     *
     * @param  class-string<TValue>  $class
     * @return TValue|mixed
     */
    function tenant(string $class, ?string $attribute = null): mixed
    {
        return once(function () use ($class, $attribute) {
            $tenant = Filament::getTenant();

            if (! $tenant instanceof $class) {
                return null;
            }

            if (is_null($attribute)) {
                return $tenant;
            }

            return $tenant->getAttribute($attribute);
        });
    }
}

if (! function_exists('css_classes')) {
    function css_classes($classes = []): string
    {
        return Arr::toCssClasses($classes);
    }
}

if (! function_exists('mask')) {
    /**
     * Applies mask in the value.
     */
    function mask(string $mask, $value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        // Apply mask
        $value = str_replace(' ', '', $value);
        $replacedStr = Str::replaceArray('#', str_split($value), $mask);

        // Get filled substring
        $posSymbol = strpos($replacedStr, '#', strlen($value));
        $replacedStrLen = strlen($replacedStr);
        $length = $posSymbol ? $replacedStrLen - ($replacedStrLen - $posSymbol) : $replacedStrLen;

        return substr($replacedStr, 0, $length);
    }
}

if (! function_exists('rate_converter')) {
    function rate_converter(string|int|float|null $value, $toFloat = false): null|float|string
    {
        // If value is null, return null
        if (is_null($value)) {
            return null;
        }

        // If value is a string, convert it to float
        if (is_string($value)) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
            $value = (float) number_format((float) $value, 2, '.', '');
        }

        // If value is an integer, convert it to float
        if (is_int($value)) {
            $value = $value / 100;
        }

        // If $toFloat is true, return the value as float
        // Otherwise, return the value as string
        return $toFloat ? $value : number_format($value, 2, ',', '.');
    }
}

if (! function_exists('format_address')) {
    function formattedAddress(array $address): ?string
    {
        $formattedAddress = null;

        if ($street = data_get($address, 'street')) {
            $formattedAddress .= $street.', ';
        }

        if ($streetNumber = data_get($address, 'street_number')) {
            $formattedAddress .= $streetNumber.', ';
        }

        if ($neighborhood = data_get($address, 'neighborhood')) {
            $formattedAddress .= $neighborhood.' - ';
        }

        if ($city = data_get($address, 'city')) {
            $formattedAddress .= $city->name.' - ';
        }

        if ($state = data_get($address, 'state')) {
            $formattedAddress .= $state->short_name.', ';
        }

        if ($postalCode = data_get($address, 'postal_code')) {
            $formattedAddress .= $postalCode;
        }

        return $formattedAddress;
    }
}
