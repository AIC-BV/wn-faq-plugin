<?php

namespace Aic\Faq\Tests\Models;

use Aic\Faq\Models\Categories;
use Aic\Faq\Tests\FaqPluginTestCase;

class CategoriesModelTest extends FaqPluginTestCase
{
    public function testDuplicateCategoryNamesReceiveUniqueSlugs(): void
    {
        $firstCategory = $this->createCategory('General');
        $secondCategory = $this->createCategory('General');

        $this->assertSame('general', $firstCategory->slug);
        $this->assertSame('general-2', $secondCategory->slug);
    }

    public function testSortOrderDefaultsToNextAvailableValue(): void
    {
        $first = $this->createCategory('First');
        $second = $this->createCategory('Second');

        $this->assertSame(1, $first->sort_order);
        $this->assertSame(2, $second->sort_order);
    }

    public function testGetUrlParamsExposesSlugFromToArray(): void
    {
        $category = $this->createCategory('Getting Started');

        $params = $category->getUrlParams();

        $this->assertSame($category->slug, $params['slug']);
    }

    public function testCategoryOptionsAreSortedByName(): void
    {
        $this->createCategory('Zeta');
        $this->createCategory('Alpha');

        $options = (new Categories())->getCategoriesListOptions();

        $this->assertSame(['Alpha', 'Zeta'], array_values($options));
    }

    public function testFaqsRelationOnlyIncludesPublishedFaqsOrderedBySortOrder(): void
    {
        $category = $this->createCategory('With FAQs');

        $hidden = $this->createFaq($category->id, 0, 0, 'Hidden question', 'Hidden answer');
        $second = $this->createFaq($category->id, 1, 0, 'Second question', 'Second answer');
        $first = $this->createFaq($category->id, 1, 0, 'First question', 'First answer');

        $first->sort_order = 2;
        $first->save();
        $second->sort_order = 1;
        $second->save();

        $ids = $category->faqs()->pluck('id')->all();

        $this->assertSame([$first->id, $second->id], $ids);
        $this->assertNotContains($hidden->id, $ids);
    }
}
