<?php

namespace Aic\Faq\Tests\Models;

use Aic\Faq\Models\Faqs;
use Aic\Faq\Tests\FaqPluginTestCase;

class FaqsModelTest extends FaqPluginTestCase
{
    public function testIsPublishedScopeReturnsOnlyPublishedForGuest(): void
    {
        $category = $this->createCategory('General');

        $published = $this->createFaq($category->id, 1, 0, 'Published question', 'Published answer');
        $this->createFaq($category->id, 2, 0, 'Draft question', 'Draft answer');
        $this->createFaq($category->id, 0, 0, 'Hidden question', 'Hidden answer');

        $ids = Faqs::isPublished()->pluck('id')->all();

        $this->assertSame([$published->id], $ids);
    }

    public function testIsFeaturedScopeFiltersByStatus(): void
    {
        $category = $this->createCategory('General');

        $featured = $this->createFaq($category->id, 1, 1, 'Featured question', 'Featured answer');
        $this->createFaq($category->id, 1, 0, 'Normal question', 'Normal answer');

        $ids = Faqs::isPublished()->isFeatured(1)->pluck('id')->all();

        $this->assertSame([$featured->id], $ids);
    }

    public function testCategoryScopeFiltersByCategory(): void
    {
        $categoryA = $this->createCategory('Category A');
        $categoryB = $this->createCategory('Category B');

        $faqInA = $this->createFaq($categoryA->id, 1, 0, 'Question in A', 'Answer in A');
        $this->createFaq($categoryB->id, 1, 0, 'Question in B', 'Answer in B');

        $ids = Faqs::isPublished()->category($categoryA->id)->pluck('id')->all();

        $this->assertSame([$faqInA->id], $ids);
    }

    public function testSortFaqsScopeAppliesAllowedSorting(): void
    {
        $categoryA = $this->createCategory('Category A');
        $categoryB = $this->createCategory('Category B');

        $faqInA = $this->createFaq($categoryA->id, 1, 0, 'Question A', 'Answer A');
        $faqInB = $this->createFaq($categoryB->id, 1, 0, 'Question B', 'Answer B');

        $sortedIds = Faqs::isPublished()->sortFAQs('category_id desc')->pluck('id')->all();

        $this->assertSame([$faqInB->id, $faqInA->id], $sortedIds);
    }

    public function testListFrontEndAppliesCombinedFilters(): void
    {
        $categoryA = $this->createCategory('Category A');
        $categoryB = $this->createCategory('Category B');

        $matchingFaq = $this->createFaq($categoryA->id, 1, 1, 'Target question', 'Target answer');
        $this->createFaq($categoryA->id, 1, 0, 'Wrong featured', 'Wrong featured answer');
        $this->createFaq($categoryA->id, 2, 1, 'Draft question', 'Draft answer');
        $this->createFaq($categoryB->id, 1, 1, 'Wrong category', 'Wrong category answer');

        $faqs = Faqs::listFrontEnd([
            'categoryId' => $categoryA->id,
            'isFeatured' => 1,
            'isSearch' => 0,
            'isTranslated' => 0,
            'sort' => 'category_id asc',
        ]);

        $this->assertCount(1, $faqs);
        $this->assertSame($matchingFaq->id, $faqs->first()->id);
    }

    public function testListFrontEndSearchFiltersQuestionAndAnswer(): void
    {
        $category = $this->createCategory('Search category');

        $faqQuestionMatch = $this->createFaq($category->id, 1, 0, 'How to install WinterCMS?', 'Some answer');
        $faqAnswerMatch = $this->createFaq($category->id, 1, 0, 'Another question', 'Install guide is here');
        $this->createFaq($category->id, 1, 0, 'No match question', 'No match answer');

        $faqs = Faqs::listFrontEnd([
            'categoryId' => 0,
            'isFeatured' => null,
            'isSearch' => 1,
            'isTranslated' => 0,
            'searchQuery' => 'Install',
        ]);

        $ids = $faqs->pluck('id')->all();

        $this->assertSame([$faqQuestionMatch->id, $faqAnswerMatch->id], $ids);
    }

    public function testListFrontEndExcludesFaqsFromUnpublishedCategory(): void
    {
        $publishedCategory = $this->createCategory('Published category', 1);
        $unpublishedCategory = $this->createCategory('Unpublished category', 0);

        $visibleFaq = $this->createFaq($publishedCategory->id, 1, 0, 'Visible question', 'Visible answer');
        $this->createFaq($unpublishedCategory->id, 1, 0, 'Hidden by category', 'Hidden answer');

        $ids = Faqs::listFrontEnd([
            'categoryId' => 0,
            'isFeatured' => null,
            'isSearch' => 0,
            'isTranslated' => 0,
        ])->pluck('id')->all();

        $this->assertSame([$visibleFaq->id], $ids);
    }

    public function testListFrontEndKeepsUncategorizedFaqs(): void
    {
        $unpublishedCategory = $this->createCategory('Unpublished category', 0);

        $uncategorizedFaq = $this->createFaq(null, 1, 0, 'Uncategorized question', 'Uncategorized answer');
        $this->createFaq($unpublishedCategory->id, 1, 0, 'Hidden by category', 'Hidden answer');

        $ids = Faqs::listFrontEnd([
            'categoryId' => 0,
            'isFeatured' => null,
            'isSearch' => 0,
            'isTranslated' => 0,
        ])->pluck('id')->all();

        $this->assertSame([$uncategorizedFaq->id], $ids);
    }
}
