<?php

namespace Aic\Faq\Classes\Traits;

use Winter\Storm\Support\Str;

/**
 * Had usefull methods to enums
 *
 * See: https://github.com/iteks/laravel-enum
 *
 */
trait BackedEnum
{
    use ExtendsBackedEnum;
    use TranslatedBackedEnum;

    /**
     * Maps a scalar to an enum instance.
     *
     * @param  string  $name  The case name.
     * @return mixed
     */
    public static function fromName(string $name): self
    {
        return self::__fromName($name);
    }

    /**
     * Retrieve the case name for the given simpler value.
     *
     * @param  int|string  $value  The simpler value.
     * @return string|null The case name.
     */
    public static function name(int|string $value): ?string
    {
        $enum = self::tryFrom($value);

        return $enum ? $enum->name : null;
    }

    /**
     * Retrieve an array containing all of the case names.
     *
     * @return array<string> An array of case names.
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Maps a scalar to an enum instance or null.
     *
     * @param  string  $name  The case name.
     * @return mixed
     */
    public static function tryFromName(string $name): mixed
    {
        return self::__tryFromName($name);
    }

    /**
     * Retrieve the simpler value for the given case name.
     *
     * @param  string  $name  The case name.
     * @return int|string|null The simpler value.
     */
    public static function value(string $name): int|string|null
    {
        $enumClass = static::class;

        foreach ($enumClass::cases() as $case) {
            if ($case->name === $name) {
                return $case->value;
            }
        }

        return null;
    }

    /**
     * Retrieve an array containing all of the simpler values.
     *
     * @return array<int|string> An array of simpler values.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
