<?php

namespace Aic\Faq\Components;

use Aic\Faq\Classes\FaqBaseComponent;
use Aic\Faq\Models\Categories;
use Aic\Faq\Models\Faqs as Faq;
use Winter\Storm\Database\Collection;

class Faqs extends FaqBaseComponent
{
    /**
     * Whether to show the search form
     */
    public bool $isSearch = false;

    /**
     * Whether the search form should be rendered
     */
    public bool $canShowSearch = false;

    /**
     * The search query
     */
    public string $searchQuery = '';


    /**
     * Gets the details for the component
     *
     * @return array<string, string>
     */
    public function componentDetails(): array
    {
        return [
            'name'        => 'aic.faq::lang.components.faqs.title',
            'description' => 'aic.faq::lang.components.faqs.description',
        ];
    }

    /**
     * Returns the properties provided by the component
     *
     * @return array<string, mixed>
     */
    public function defineProperties(): array
    {
        return array_merge($this->defineBaseProperties(), [
            'categoryId' => [
                'title'       => 'aic.faq::lang.components.faqs.properties.category.title',
                'description' => 'aic.faq::lang.components.faqs.properties.category.description',
                'type'        => 'dropdown',
                'default'     => 0
            ],
            'isSearch' => [
                'title'       => 'aic.faq::lang.components.faqs.properties.search.title',
                'description' => 'aic.faq::lang.components.faqs.properties.search.description',
                'default'     => true,
                'type'        => 'checkbox',
                'group'       => 'aic.faq::lang.components.faqs.properties.search_group',
            ],
            'minSearchResults' => [
                'title'             => 'aic.faq::lang.components.faqs.properties.minSearchResults.title',
                'description'       => 'aic.faq::lang.components.faqs.properties.minSearchResults.description',
                'default'           => 10,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'aic.faq::lang.components.faqs.properties.minSearchResults.validationMessage',
                'group'             => 'aic.faq::lang.components.faqs.properties.search_group',
            ],
        ]);
    }

    /**
     * Returns all categories as dropdown options.
     *
     * @return array<int, string>
     */
    public function getCategoryIdOptions(): array
    {
        // return all categories for the dropdown
        $categories = Categories::lists('name', 'id');
        $categories[0] = 'aic.faq::lang.components.faqs.properties.category.all';
        ksort($categories);

        return $categories;
    }

    /**
     * {@inheritDoc}
     */
    public function onRun(): void
    {
        $this->prepareBaseVars();
        $this->prepareSearchVars();

        $categoryId = (int) $this->property('categoryId');

        if ($categoryId !== 0 && !Categories::whereKey($categoryId)->exists()) {
            $this->canShowSearch = false;
            $this->resolvedCategoryId = null;
            $this->faqs = new Collection();
            $this->faqsPerCategory = [];
            $this->jsonLd = '';

            return;
        }

        $this->resolvedCategoryId = $categoryId;
        $this->faqs = $this->getFAQs([
            'isSearch' => $this->isSearch,
            'searchQuery' => $this->searchQuery,
        ]);
        $this->faqsPerCategory = $this->faqsPerCategory();
        $this->jsonLd = $this->getJsonLd();
        $this->canShowSearch = $this->showSearch();
    }

    /**
     * Prepare search-specific variables for the page and the component.
     */
    protected function prepareSearchVars(): void
    {
        $this->isSearch      = $this->page['isSearch']      = (bool) $this->property('isSearch');
        $this->searchQuery   = $this->page['searchQuery']   = (string) trim(input('q'));
    }

    /**
     * Determine whether the search form should be shown.
     */
    protected function showSearch(): bool
    {
        if (!$this->isSearch) {
            return false;
        }

        $minSearchResults = (int) $this->property('minSearchResults');

        // Reuse the existing FAQs collection if no search query is provided,
        // otherwise fetch the count of FAQs without search applied.
        $faqsWithoutSearch = $this->faqs->count();

        if ($this->searchQuery !== '') {
            $faqsWithoutSearch = Faq::listFrontEnd([
                'categoryId'   => $this->resolvedCategoryId,
                'isFeatured'   => $this->getFeaturedFilter(),
                'isSearch'     => false,
                'isTranslated' => (bool) $this->property('isTranslated')
            ])->count();
        }

        return $faqsWithoutSearch >= $minSearchResults;
    }

    //
    // Ajax handlers
    //
    public function onSearch(): array
    {
        $this->prepareBaseVars();
        $this->prepareSearchVars();

        $categoryId = (int) $this->property('categoryId');
        if ($categoryId !== 0 && !Categories::whereKey($categoryId)->exists()) {
            $this->resolvedCategoryId = null;
            $this->faqs = new Collection();
            $this->faqsPerCategory = [];

            return [
                '#'. input('componentId') => $this->renderPartial('@items.htm', [
                    'faqsPerCategory' => $this->faqsPerCategory,
                    'searchQuery' => $this->searchQuery,
                ]),
            ];
        }

        $this->resolvedCategoryId = $categoryId;
        $this->faqs = $this->getFAQs([
            'isSearch' => $this->isSearch,
            'searchQuery' => $this->searchQuery,
        ]);
        $this->faqsPerCategory = $this->faqsPerCategory();

        return [
            '#'. input('componentId') => $this->renderPartial('@items.htm', [
                'faqsPerCategory' => $this->faqsPerCategory,
                'searchQuery' => $this->searchQuery,

            ]),
        ];
    }
}
