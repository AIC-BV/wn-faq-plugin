<?php

namespace Aic\Faq\Tests\Enums;

use Aic\Faq\Classes\Enums\FeaturedStatusEnum;
use PluginTestCase;
use ValueError;


/**
 * Tests for FeaturedStatusEnum.
 *
 * This enum is used as the representative test subject for the shared
 * BackedEnum / TranslatedBackedEnum / ExtendsBackedEnum traits, so a bug
 * caught here protects every other enum built on top of these traits.
 */
class FeaturedStatusEnumTest extends PluginTestCase
{
    // ------------------------------------------------------------------
    // fromName() / tryFromName()
    // ------------------------------------------------------------------
 
    public function testFromNameReturnsCorrectCase(): void
    {
        $this->assertSame(FeaturedStatusEnum::FEATURED, FeaturedStatusEnum::fromName('FEATURED'));
    }
 
    public function testFromNameThrowsOnInvalidName(): void
    {
        $this->expectException(ValueError::class);
 
        FeaturedStatusEnum::fromName('INVALID_NAME');
    }
 
    public function testTryFromNameReturnsCorrectCase(): void
    {
        $this->assertSame(FeaturedStatusEnum::NOT_FEATURED, FeaturedStatusEnum::tryFromName('NOT_FEATURED'));
    }
 
    public function testTryFromNameReturnsNullOnInvalidName(): void
    {
        $this->assertNull(FeaturedStatusEnum::tryFromName('INVALID_NAME'));
    }
 
    // ------------------------------------------------------------------
    // name() / value() round-trip
    // ------------------------------------------------------------------
 
    public function testNameReturnsCorrectCaseNameForValue(): void
    {
        $this->assertSame('NOT_FEATURED', FeaturedStatusEnum::name(0));
        $this->assertSame('FEATURED', FeaturedStatusEnum::name(1));
    }
 
    public function testNameReturnsNullForUnknownValue(): void
    {
        $this->assertNull(FeaturedStatusEnum::name(999));
    }
 
    public function testValueReturnsCorrectValueForCaseName(): void
    {
        $this->assertSame(0, FeaturedStatusEnum::value('NOT_FEATURED'));
        $this->assertSame(1, FeaturedStatusEnum::value('FEATURED'));
    }
 
    public function testValueReturnsNullForUnknownCaseName(): void
    {
        $this->assertNull(FeaturedStatusEnum::value('UNKNOWN'));
    }
 
    // ------------------------------------------------------------------
    // names() / values()
    // ------------------------------------------------------------------
 
    public function testNamesReturnsAllCaseNamesInDeclarationOrder(): void
    {
        $this->assertSame(
            ['FEATURED', 'NOT_FEATURED'],
            FeaturedStatusEnum::names()
        );
    }
 
    public function testValuesReturnsAllValuesInDeclarationOrder(): void
    {
        $this->assertSame([1, 0], FeaturedStatusEnum::values());
    }
 
    // ------------------------------------------------------------------
    // Translations (namesTranslated / nameTranslated / *ByName variants)
    // ------------------------------------------------------------------
 
    /**
     * WinterCMS falls back to the EN translation when a key is missing in
     * the user's active locale. This means a missing key in, say, FR is
     * invisible to the end user (they silently get the EN text instead).
     * The only way for Laravel's Lang::get() to leak the raw, untranslated
     * key is if the EN entry itself is missing — since there's no further
     * fallback beyond that. So EN is the locale that must be exhaustive.
     */
    public function testEveryCaseHasAnEnglishTranslation(): void
    {
        \App::setLocale('en');
 
        foreach (FeaturedStatusEnum::cases() as $case) {
            $translated = FeaturedStatusEnum::nameTranslated($case->value);
 
            $this->assertNotNull(
                $translated,
                "Missing EN translation for case {$case->name}"
            );
 
            $this->assertStringNotContainsString(
                'aic.faq::lang.enums',
                $translated,
                "Untranslated raw lang key leaked for case {$case->name} — EN fallback is incomplete"
            );
        }
    }
 
    public function testNameTranslatedReturnsNullForUnknownValue(): void
    {
        $this->assertNull(FeaturedStatusEnum::nameTranslated(999));
    }
 
    public function testNameTranslatedByNameMatchesNameTranslated(): void
    {
        \App::setLocale('en');
 
        foreach (FeaturedStatusEnum::cases() as $case) {
            $this->assertSame(
                FeaturedStatusEnum::nameTranslated($case->value),
                FeaturedStatusEnum::nameTranslatedByName($case->name)
            );
        }
    }
 
    public function testNamesTranslatedIndexedByValueByDefault(): void
    {
        \App::setLocale('en');
 
        $translated = FeaturedStatusEnum::namesTranslated();
 
        $this->assertSame(
            array_column(FeaturedStatusEnum::cases(), 'value'),
            array_keys($translated)
        );
    }
 
    public function testNamesTranslatedByNameIndexedByCaseName(): void
    {
        \App::setLocale('en');
 
        $translated = FeaturedStatusEnum::namesTranslatedByName();
 
        $this->assertSame(
            FeaturedStatusEnum::names(),
            array_keys($translated)
        );
    }
}
