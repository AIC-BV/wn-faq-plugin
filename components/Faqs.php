<?php namespace Aic\Faq\Components;
use Cms\Classes\ComponentBase;

use Lang;
use Aic\Faq\Models\Categories;
use Aic\Faq\Models\Faqs as Faq;

class Faqs extends ComponentBase
{
    public $faqs;
    public $isSearch;
    public $searchLabel;
    public $searchPlaceholder;
    public $minSearchResults;
    public $searchQuery;

    public function componentDetails()
    {
        return [
            'name'        => 'aic.faq::lang.component.title',
            'description' => 'aic.faq::lang.component.description',
        ];
    }

    public function defineProperties()
    {
        return [
            'sort' => [
                'title'       => 'aic.faq::lang.component.settings.sort.title',
                'description' => 'aic.faq::lang.component.settings.sort.description',
                'type'        => 'dropdown',
                'default'     => 'category_id asc',
                'options'     => [
                    'category_id asc'  => 'aic.faq::lang.component.settings.sort.options.category_id_asc',
                    'category_id desc' => 'aic.faq::lang.component.settings.sort.options.category_id_desc',
                    'created_at asc'   => 'aic.faq::lang.component.settings.sort.options.created_at_asc',
                    'created_at desc'  => 'aic.faq::lang.component.settings.sort.options.created_at_desc'
                ]
            ],
            'categoryId' => [
                'title'       => 'aic.faq::lang.component.settings.category.title',
                'description' => 'aic.faq::lang.component.settings.category.description',
                'type'        => 'dropdown',
                'default'     => 0
            ],
            'isFeatured' => [
                'title'       => 'aic.faq::lang.component.settings.featured.title',
                'description' => 'aic.faq::lang.component.settings.featured.description',
                'type'        => 'dropdown',
                'default'     => 2,
                'options'     => [
                    0 => 'aic.faq::lang.component.settings.featured.not_featured',
                    1 => 'aic.faq::lang.component.settings.featured.featured',
                    2 => 'aic.faq::lang.component.settings.featured.all'
                ]
            ],
            'isSearch' => [
                'title'       => 'aic.faq::lang.component.settings.search.title',
                'description' => 'aic.faq::lang.component.settings.search.description',
                'default'     => true,
                'type'        => 'checkbox'
            ],
            'minSearchResults' => [
                'title'       => 'aic.faq::lang.component.settings.minSearchResults.title',
                'description' => 'aic.faq::lang.component.settings.minSearchResults.description',
                'default'     => 10,
                'type'        => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'aic.faq::lang.component.settings.minSearchResults.validationMessage'
            ],
            'isTranslated' => [
                'title'       => 'aic.faq::lang.component.settings.translated.title',
                'description' => 'aic.faq::lang.component.settings.translated.description',
                'default'     => true,
                'type'        => 'checkbox'
            ],
        ];
    }

    public function onRun()
    {
        $this->prepareVars();
        $this->faqs = $this->getFAQs();
        $this->showSearch();
    }

    protected function prepareVars()
    {
        $this->isSearch = (int) $this->property('isSearch');
        $this->searchLabel = Lang::get('aic.faq::lang.component.settings.search.button_label');
        $this->searchPlaceholder = Lang::get('aic.faq::lang.component.settings.search.input_placeholder');
        $this->searchQuery = trim(input('q'));
    }

    protected function showSearch()
    {
        $minResults = (int) $this->property('minSearchResults');

        $faqsWithoutSearch = Faq::listFrontEnd([
            'sort'         => $this->property('sort'),
            'categoryId'   => (int) $this->property('categoryId'),
            'isFeatured'   => (int) $this->property('isFeatured'),
            'isSearch'     => (int) $this->property('isSearch'),
            'isTranslated' => (int) $this->property('isTranslated'),
            'searchQuery'  => ''
        ]);

        if (count($faqsWithoutSearch) >= $minResults) $this->minSearchResults = true;
        else $this->minSearchResults = false;
    }

    protected function getFAQs()
    {
        return Faq::listFrontEnd([
            'sort'         => $this->property('sort'),
            'categoryId'   => (int) $this->property('categoryId'),
            'isFeatured'   => (int) $this->property('isFeatured'),
            'isSearch'     => (int) $this->property('isSearch'),
            'isTranslated' => (int) $this->property('isTranslated'),
            'searchQuery'  => trim(input('q'))
        ]);
    }

    public function getCategoryIdOptions()
    {
        $categories = Categories::lists('name', 'id');
        $categories[0] = 'aic.faq::lang.component.settings.category.all';
        ksort($categories);
        return $categories;
    }
}