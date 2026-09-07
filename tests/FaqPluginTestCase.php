<?php

namespace Aic\Faq\Tests;

use Aic\Faq\Models\Categories;
use Aic\Faq\Models\Faqs;
use System\Tests\Bootstrap\PluginTestCase;

/**
 * Shared base for the Aic.Faq plugin tests, providing common fixture helpers
 * so model and component tests don't duplicate record creation logic.
 */
abstract class FaqPluginTestCase extends PluginTestCase
{
    /**
     * @var array   Plugins to refresh between tests.
     */
    protected $refreshPlugins = [
        'Aic.Faq',
    ];

    protected function createCategory(string $name, int $isPublished = 1): Categories
    {
        $category = new Categories();
        $category->name = $name;
        $category->is_published = $isPublished;
        $category->save();

        return $category;
    }

    protected function createFaq(
        ?int $categoryId,
        int $isPublished = 1,
        int $isFeatured = 0,
        string $question = 'Question',
        string $answer = 'Answer'
    ): Faqs {
        $faq = new Faqs();
        $faq->category_id = $categoryId;
        $faq->is_published = $isPublished;
        $faq->is_featured = $isFeatured;
        $faq->question = $question;
        $faq->answer = $answer;
        $faq->save();

        return $faq;
    }
}
