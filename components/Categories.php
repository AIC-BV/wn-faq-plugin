<?php

namespace Aic\Faq\Components;

use Lang;
use Aic\Faq\Models\Categories as FaqCategories;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Collection;

class Categories extends ComponentBase
{
    /**
     * A collection of categories to display
     */
    public ?Collection $categories = null;

    /**
     * Reference to the page name for linking to categories.
     */
    public ?string $categoryPage = '';

    /**
     * Reference to the current category slug.
     */
    public ?string $currentCategorySlug = '';

    /**
     * Gets the details for the component
     */
    public function componentDetails(): array
    {
        return [
            'name' => 'aic.faq::lang.components.categories.title',
            'description' => 'aic.faq::lang.components.categories.description',
        ];
    }

    /**
     * Returns the properties provided by the component
     */
    public function defineProperties(): array
    {
        return [
            'categorySlug' => [
                'title' => 'aic.faq::lang.components.categories.properties.slug.title',
                'description' => 'aic.faq::lang.components.categories.properties.slug.description',
                'type' => 'string',
                'default' => '{{ :slug }}',
            ],
            'sort' => [
                'title'       => 'aic.faq::lang.components.categories.properties.sort.title',
                'description' => 'aic.faq::lang.components.categories.properties.sort.description',
                'type'        => 'dropdown',
                'default'     => 'sort_order asc',
            ],
            'categoryPage' => [
                'title' => 'aic.faq::lang.components.categories.properties.category_page.title',
                'description' => 'aic.faq::lang.components.categories.properties.category_page.description',
                'type' => 'dropdown',
                'group' => 'aic.faq::lang.components.categories.properties.links',
            ],
        ];
    }

    /**
     * Sort options getter
     */
    public function getSortOptions(): array
    {
        // Get Faqs::$allowedSorting to filter the options
        // Replace _asc and _desc with asc and desc in the keys of the options array
        $allowedSorting = FaqCategories::$allowedSorting;

        return collect(Lang::get('aic.faq::lang.sorting'))
            ->filter(fn ($label, $key) => in_array(preg_replace('/_(asc|desc)$/', ' $1', $key), $allowedSorting))
            ->mapWithKeys(fn ($label, $key) => [preg_replace('/_(asc|desc)$/', ' $1', $key) => $label])
            ->all();
    }

    /**
     * Category page options getter
     */
    public function getCategoryPageOptions(): array
    {
        return \Aic\Faq\Classes\ComponentHelper::getPagesByComponent('FaqBySlug');
    }

    /**
     * {@inheritDoc}
     */
    public function onRun()
    {
        $this->currentCategorySlug = $this->page['currentCategorySlug'] = $this->property('categorySlug');
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage') ?? $this->page->fileName;
        $this->categories = $this->page['categories'] = $this->loadCategories();
    }

    /**
     * Get all the published faq categories, including a special "All" category at the beginning of the collection.
     *
     * @return Collection
     */
    protected function loadCategories(): Collection
    {
        $categories = FaqCategories::withCount('faqs')
            ->where('is_published', 1);

        if (($sort = $this->property('sort')) && in_array($sort, FaqCategories::$allowedSorting, true)) {
            if ($sort === 'random') {
                $categories = $categories->inRandomOrder();
            } else {
                [$column, $direction] = explode(' ', $sort, 2);
                $categories = $categories->orderBy($column, $direction);
            }
        }

        $categories = $categories->get();

        // Add a special "All" category at the beginning of the collection
        $allCategory = new FaqCategories();
        $allCategory->id = 0;
        $allCategory->name = Lang::get('aic.faq::lang.components.categories.all_label');
        $allCategory->slug = '';
        $allCategory->faqs_count = $categories->sum('faqs_count');
        $categories->prepend($allCategory);

        /*
         * Add a "url" helper attribute for linking to each category
         */
        return $this->linkCategories($categories);
    }


    /**
     * Sets the URL on each category according to the defined category page
     */
    protected function linkCategories(Collection $categories): Collection
    {
        return $categories->each(function ($category) {
            if ($category->id === 0) {
                $category->url = $this->controller->pageUrl($this->page->fileName, [], false);

                return;
            }

            $category->setUrl($this->categoryPage, $this->controller);
        });
    }
}
