<?php

namespace Aic\Faq\Tests\Components;

use Aic\Faq\Tests\FaqPluginTestCase;
use Cms\Classes\Theme;
use Cms\Classes\Controller;
use Winter\Storm\Support\Facades\Config;
use Winter\Storm\Halcyon\Model;
use System\Helpers\View;

class CategoriesComponentTest extends FaqPluginTestCase
{
    protected string $testThemeDir = 'theme';
    protected string $origThemesPath;
    protected Theme $theme;

    public function setUp(): void
    {
        parent::setUp();

        // Redirect on-disk theme resolution to the plugin's isolated fixture
        // theme, same mechanism Winter's own test suite uses (RegisterWinter
        // reads cms.themesPathLocal at boot); themes_path() reads from the
        // 'path.themes' container binding, not from config('cms.themesPath').
        $this->origThemesPath = app('path.themes');
        app()->setThemesPath(__DIR__ . '/fixtures');

        Config::set('cms.activeTheme', $this->testThemeDir);
        $this->theme = Theme::load($this->testThemeDir);

        // Clear cached models and event listeners
        Model::clearBootedModels();
        Model::flushEventListeners();

        // Clear Twig cache
        View::clearVarCache();
    }

    public function tearDown(): void
    {
        // Restore the original themes path
        app()->setThemesPath($this->origThemesPath);
        parent::tearDown();
    }


    public function testPublishedCategoriesAreFilteredAndAllCategoryIsAdded(): void
    {
        $published = $this->createCategory('Published');
        $hidden = $this->createCategory('Hidden', 0);

        $this->createFaq($published->id, 1, 0, 'Question 1', 'Answer 1');
        $this->createFaq($published->id, 1, 0, 'Question 2', 'Answer 2');
        $this->createFaq($hidden->id, 1, 0, 'Hidden Question', 'Hidden answer');

        // Run the page with the component
        $controller = new Controller($this->theme);
        $response = $controller->run('/faq');

        // Access the page object and component
        $page = self::getProtectedProperty($controller, 'page');
        $this->assertNotNull($page, 'Page should be set after running controller');
        $this->assertArrayHasKey('categories', $page->components);

        $component = $page->components['categories'];

        // Verify unpublished categories are filtered out
        $this->assertCount(2, $component->categories);
        $this->assertSame([0, $published->id], $component->categories->pluck('id')->all());

        // Verify synthetic "All" category is added
        $this->assertSame(['All questions', 'Published'], $component->categories->pluck('name')->all());

        // Verify FAQ count is aggregated for "All" category
        $this->assertSame(2, $component->categories->first()->faqs_count);

        // Verify individual category FAQ count
        $this->assertSame(2, $component->categories->last()->faqs_count);

        // Verify URLs are generated correctly with the category slug
        $this->assertNotNull($component->categories->first()->url);
        $this->assertNotNull($component->categories->last()->url);
        $this->assertStringContainsString($published->slug, $component->categories->last()->url);
    }

    public function testSortPropertyOrdersCategoriesAndKeepsCategoryPageLinks(): void
    {
        $newer = $this->createCategory('Zebra');
        $older = $this->createCategory('Alpha');

        $this->createFaq($newer->id, 1, 0, 'Question Z', 'Answer Z');
        $this->createFaq($older->id, 1, 0, 'Question A', 'Answer A');

        // Run the page with the component
        $controller = new Controller($this->theme);
        $controller->run('/faq');

        // Access the page object and component
        $page = self::getProtectedProperty($controller, 'page');
        $component = $page->components['categories'];

        // Override the sort property to test name sorting
        $component->setProperty('sort', 'name asc');
        $component->onRun();

        // Verify sort order is applied
        $this->assertSame(['All questions', 'Alpha', 'Zebra'], $component->categories->pluck('name')->all());
        $this->assertSame([0, $older->id, $newer->id], $component->categories->pluck('id')->all());

        // Verify FAQ counts are correct
        $this->assertSame(2, $component->categories->first()->faqs_count);
        $this->assertSame(1, $component->categories->get(1)->faqs_count);
        $this->assertSame(1, $component->categories->get(2)->faqs_count);

        // Verify URLs are generated and unique
        $allUrl = $component->categories->first()->url;
        $alphaUrl = $component->categories->get(1)->url;
        $zebraUrl = $component->categories->get(2)->url;

        $this->assertNotSame($allUrl, $alphaUrl);
        $this->assertNotSame($allUrl, $zebraUrl);
        $this->assertNotSame($alphaUrl, $zebraUrl);

        // Verify each category URL contains its slug
        $this->assertStringContainsString($older->slug, $alphaUrl);
        $this->assertStringContainsString($newer->slug, $zebraUrl);
    }
}
