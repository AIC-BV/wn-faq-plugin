<?php

namespace Aic\Faq\Tests\Enums;

use Aic\Faq\Classes\Enums\PublishStatusEnum;
use PluginTestCase;
use ValueError;


/**
 * Tests for PublishStatusEnum.
 *
 * This enum is used as the representative test subject for the shared
 * BackedEnum / TranslatedBackedEnum / ExtendsBackedEnum traits, so a bug
 * caught here protects every other enum built on top of these traits.
 */
class PublishStatusEnumTest extends PluginTestCase
{
    // ------------------------------------------------------------------
    // fromName() / tryFromName()
    // ------------------------------------------------------------------
 
    public function testFromNameReturnsCorrectCase(): void
    {
        $this->assertSame(PublishStatusEnum::PUBLISHED, PublishStatusEnum::fromName('PUBLISHED'));
    }
 
    public function testFromNameThrowsOnInvalidName(): void
    {
        $this->expectException(ValueError::class);
 
        PublishStatusEnum::fromName('INVALID_NAME');
    }
 
    public function testTryFromNameReturnsCorrectCase(): void
    {
        $this->assertSame(PublishStatusEnum::IN_DRAFT, PublishStatusEnum::tryFromName('IN_DRAFT'));
    }
 
    public function testTryFromNameReturnsNullOnInvalidName(): void
    {
        $this->assertNull(PublishStatusEnum::tryFromName('INVALID_NAME'));
    }
 
    // ------------------------------------------------------------------
    // name() / value() round-trip
    // ------------------------------------------------------------------
 
    public function testNameReturnsCorrectCaseNameForValue(): void
    {
        $this->assertSame('NOT_PUBLISHED', PublishStatusEnum::name(0));
        $this->assertSame('PUBLISHED', PublishStatusEnum::name(1));
        $this->assertSame('IN_DRAFT', PublishStatusEnum::name(2));
    }
 
    public function testNameReturnsNullForUnknownValue(): void
    {
        $this->assertNull(PublishStatusEnum::name(999));
    }
 
    public function testValueReturnsCorrectValueForCaseName(): void
    {
        $this->assertSame(0, PublishStatusEnum::value('NOT_PUBLISHED'));
        $this->assertSame(1, PublishStatusEnum::value('PUBLISHED'));
        $this->assertSame(2, PublishStatusEnum::value('IN_DRAFT'));
    }
 
    public function testValueReturnsNullForUnknownCaseName(): void
    {
        $this->assertNull(PublishStatusEnum::value('UNKNOWN'));
    }
 
    // ------------------------------------------------------------------
    // names() / values()
    // ------------------------------------------------------------------
 
    public function testNamesReturnsAllCaseNamesInDeclarationOrder(): void
    {
        $this->assertSame(
            ['PUBLISHED', 'IN_DRAFT', 'NOT_PUBLISHED'],
            PublishStatusEnum::names()
        );
    }
 
    public function testValuesReturnsAllValuesInDeclarationOrder(): void
    {
        $this->assertSame([1, 2, 0], PublishStatusEnum::values());
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
 
        foreach (PublishStatusEnum::cases() as $case) {
            $translated = PublishStatusEnum::nameTranslated($case->value);
 
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
        $this->assertNull(PublishStatusEnum::nameTranslated(999));
    }
 
    public function testNameTranslatedByNameMatchesNameTranslated(): void
    {
        \App::setLocale('en');
 
        foreach (PublishStatusEnum::cases() as $case) {
            $this->assertSame(
                PublishStatusEnum::nameTranslated($case->value),
                PublishStatusEnum::nameTranslatedByName($case->name)
            );
        }
    }
 
    public function testNamesTranslatedIndexedByValueByDefault(): void
    {
        \App::setLocale('en');
 
        $translated = PublishStatusEnum::namesTranslated();
 
        $this->assertSame(
            array_column(PublishStatusEnum::cases(), 'value'),
            array_keys($translated)
        );
    }
 
    public function testNamesTranslatedByNameIndexedByCaseName(): void
    {
        \App::setLocale('en');
 
        $translated = PublishStatusEnum::namesTranslatedByName();
 
        $this->assertSame(
            PublishStatusEnum::names(),
            array_keys($translated)
        );
    }
}
