<?php

namespace Aic\Faq\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Lang;
use Aic\Faq\Models\Categories as FaqCategories;

class Categories extends ComponentBase
{
    public $categories;
    public $oPage;
    public $categoryPage;
    public $categorySlug;
    public $allLabel;

    public function componentDetails()
    {
        return [
            'name' => 'aic.faq::lang.component.categories.title',
            'description' => 'aic.faq::lang.component.categories.description',
        ];
    }

    public function defineProperties()
    {
        return [
            'categorySlug' => [
                'title' => 'aic.faq::lang.component.categories.settings.slug.title',
                'description' => 'aic.faq::lang.component.categories.settings.slug.description',
                'type' => 'string',
                'default' => '{{ :slug }}',
            ],
            'oPage' => [
                'title' => 'aic.faq::lang.component.categories.settings.overview_page.title',
                'description' => 'aic.faq::lang.component.categories.settings.overview_page.description',
                'type' => 'dropdown',
                'group' => 'aic.faq::lang.component.categories.settings.links',
                'options' => Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName'),
            ],
            'categoryPage' => [
                'title' => 'aic.faq::lang.component.categories.settings.category_page.title',
                'description' => 'aic.faq::lang.component.categories.settings.category_page.description',
                'type' => 'dropdown',
                'group' => 'aic.faq::lang.component.categories.settings.links',
                'options' => Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName'),
            ],
        ];
    }

    public function onRun()
    {
        $this->prepareVars();
        $this->categories = FaqCategories::where('is_published', 1)->get();
    }

    protected function prepareVars()
    {
        $this->oPage = $this->property('oPage');
        $this->categoryPage = $this->property('categoryPage');
        $this->categorySlug = $this->property('categorySlug');
        $this->allLabel = Lang::get('aic.faq::lang.component.categories.all_label');
    }
}
