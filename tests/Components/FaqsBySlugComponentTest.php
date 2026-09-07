<?php

namespace Aic\Faq\Tests\Components;

use Aic\Faq\Components\FaqsBySlug;
use Aic\Faq\Tests\FaqPluginTestCase;

class FaqsBySlugComponentTest extends FaqPluginTestCase
{
    public function testLoadsFaqsForMatchingCategorySlug(): void
    {
        $matchedCategory = $this->createCategory('Getting started');
        $otherCategory = $this->createCategory('Advanced');

        $expectedFaq = $this->createFaq($matchedCategory->id, 1, 1, 'What is this plugin?', 'A FAQ plugin.');
        $this->createFaq($otherCategory->id, 1, 1, 'How to configure?', 'Read docs.');

        $component = new FaqsBySlug(null, [
            'categoryFilter' => $matchedCategory->slug,
            'sort' => 'question asc',
            'isFeatured' => null,
            'isTranslated' => true,
        ]);

        $component->onRun();

        $this->assertNotNull($component->faqs);
        $this->assertCount(1, $component->faqs);
        $this->assertSame($expectedFaq->id, $component->faqs->first()->id);
        $this->assertNotEmpty($component->faqsPerCategory);
        $this->assertNotSame('', $component->jsonLd);
    }

    public function testInvalidCategorySlugTriggers404(): void
    {
        $category = $this->createCategory('General');
        $this->createFaq($category->id, 1, 0, 'Visible FAQ', 'Visible answer');

        $component = new FaqsBySlug(null, [
            'categoryFilter' => 'missing-slug',
            'sort' => 'question asc',
            'isFeatured' => null,
            'isTranslated' => true,
        ]);

        $controller = new class {
            public array $ranUrls = [];

            public function run($url)
            {
                $this->ranUrls[] = $url;

                return '404-response';
            }
        };
        (new \ReflectionProperty($component, 'controller'))->setAccessible(true);
        (new \ReflectionProperty($component, 'controller'))->setValue($component, $controller);

        $result = $component->onRun();

        $this->assertSame('404-response', $result);
        $this->assertSame(['404'], $controller->ranUrls);
        $this->assertNull($component->faqs);
    }

    public function testEmptyCategoryFilterLoadsAllPublishedFaqs(): void
    {
        $categoryA = $this->createCategory('Category A');
        $categoryB = $this->createCategory('Category B');

        $faqA = $this->createFaq($categoryA->id, 1, 0, 'Question A', 'Answer A');
        $faqB = $this->createFaq($categoryB->id, 1, 0, 'Question B', 'Answer B');
        $this->createFaq($categoryB->id, 0, 0, 'Hidden', 'Hidden');

        $component = new FaqsBySlug(null, [
            'categoryFilter' => '',
            'sort' => 'question asc',
            'isFeatured' => null,
            'isTranslated' => true,
        ]);

        $component->onRun();

        $this->assertNotNull($component->faqs);
        $ids = $component->faqs->pluck('id')->all();
        sort($ids);

        $expectedIds = [$faqA->id, $faqB->id];
        sort($expectedIds);

        $this->assertSame($expectedIds, $ids);
    }

    public function testDefinePropertiesExposesCategoryFilterWithoutSearchProperties(): void
    {
        $component = new FaqsBySlug(null, []);
        $properties = $component->defineProperties();

        $this->assertArrayHasKey('categoryFilter', $properties);
        $this->assertArrayNotHasKey('isSearch', $properties);
        $this->assertArrayNotHasKey('minSearchResults', $properties);
    }
}
