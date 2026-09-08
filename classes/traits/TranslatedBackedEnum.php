<?php

namespace Aic\Faq\Classes\Traits;

use Illuminate\Support\Facades\Lang;

trait TranslatedBackedEnum
{
    /**
     * Retrieve an array containing all of the case names translated with the LANG_KEY.
     *
     * @return array<string> An array of case names translated.
     */
    public static function namesTranslated(?bool $byName = false): array
    {
        $all = self::cases();
        
        $key = $byName ? 'name' : 'value';
        
        $translated = array_combine(
            array_column($all, $key),
            array_map(
                function ($value) {
                    return Lang::get(sprintf(self::LANG_KEY, $value));
                },
                array_column($all, 'value')
            ),
        );

        return $translated;
    }

    /**
     * Retrieve an array containing all of the case names translated with the LANG_KEY.
     * The array is indexed by the case name instead of the value.
     *
     * @return array<string> An array of case names translated.
     */
    public static function namesTranslatedByName()
    {
        return self::namesTranslated(true);
    }

    /**
     * Retrieve the case name translated for the given simpler value.
     *
     * @param  int|string  $value  The simpler value.
     * @return string|null The case name translated.
     */
    public static function nameTranslated(int|string $value): ?string
    {
        $enum = self::tryFrom($value);

        return $enum ? Lang::get(sprintf(self::LANG_KEY, $enum->value)) : null;
    }

    /**
     * Retrieve the case name translated for the given simpler value.
     *
     * @param  string  $name  The case name.
     * @return string|null The case name translated.
     */
    public static function nameTranslatedByName(string $name): ?string
    {
        $enum = self::__tryFromName($name);

        return $enum ? Lang::get(sprintf(self::LANG_KEY, $enum->value)) : null;
    }
}
