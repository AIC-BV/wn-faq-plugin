<?php namespace Aic\Faq\Components;
use Cms\Classes\ComponentBase;

use Lang;
use Aic\Faq\Models\Categories;
use Aic\Faq\Models\Faqs as Faq;
use Aic\Faq\Models\Settings;

class Faqs extends ComponentBase
{
    public $title;
    public $intro;
    public $blocks;

    public $faqs;
    public $faqsPerCategory;
    public $isSearch;
    public $isFilter;
    public $noPostsMessage;
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
            'categorySlug' => [
                'title'       => 'aic.faq::lang.component.settings.category_slug.title',
                'description' => 'aic.faq::lang.component.settings.category_slug.description',
                'type'        => 'string',
                'default'     => ''
            ],
            'isFilter' => [
                'title'       => 'aic.faq::lang.settings.filter_title',
                'description' => 'aic.faq::lang.settings.filter_description',
                'default'     => true,
                'type'        => 'checkbox'
            ],
            'noPostsMessage' => [
                'title'       => 'aic.faq::lang.settings.no_posts_title',
                'description' => 'aic.faq::lang.settings.no_posts_description',
                'type'        => 'string',
                'default'     => Lang::get('aic.faq::lang.settings.no_posts_found'),
                'showExternalParam' => false
            ],
        ];
    }

    public function onRun()
    {
        $this->prepareVars();
        $this->faqs = $this->getFAQs();
        $this->faqsPerCategory = $this->faqsPerCategory($this->faqs);
        $this->minSearchResults = $this->showSearch();
    }

    protected function prepareVars()
    {
        $this->isSearch = (int) $this->property('isSearch');
        $this->isFilter = (int) $this->property('isFilter');
        $this->noPostsMessage = $this->property('noPostsMessage');
        $this->searchLabel = Lang::get('aic.faq::lang.component.settings.search.button_label');
        $this->searchPlaceholder = Lang::get('aic.faq::lang.component.settings.search.input_placeholder');
        $this->searchQuery = trim(input('q'));

        // title and intro
        $settings = Settings::instance();
        $this->title = $settings->title;
        $this->intro = $settings->intro;
        $this->blocks = $settings->blocks;

        // set meta
        $this->setMeta($settings);
    }

    protected function setMeta($settings)
    {
        // create the meta_description
        $meta_description = strip_tags($settings->intro);
        if (strlen($meta_description) > 200) {
            $meta_description = substr($meta_description, 0, 197) . '...';
        }

        // set title
        $this->page->title = ($settings->meta_title) ? $settings->meta_title : $settings->title;

        // set meta (try meta_value, else try og_value, else use default value)
        $this->page->meta_title = ($settings->meta_title) ? $settings->meta_title : ($settings->og_title ? $settings->og_title : $settings->title);
        $this->page->meta_description = ($settings->meta_description) ? $settings->meta_description : ($settings->og_description ? $settings->og_description : $meta_description);

        // set og (try og_value, else use meta_value)
        $this->page->og_title = ($settings->og_title) ? $settings->og_title : $this->page->meta_title;
        $this->page->og_description = ($settings->og_description) ? $settings->og_description : $this->page->meta_description;
        $this->page->og_image = ($settings->og_image) ? $settings->og_image : null;
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
        ])->count();

        if ($faqsWithoutSearch >= $minResults) return true;
        else return false;
    }

    protected function getFAQs()
    {

        $categoryId = $this->loadCategory() ?? (int) $this->property('categoryId');

        $faqs = Faq::listFrontEnd([
            'sort'         => $this->property('sort'),
            'categoryId'   => $categoryId,
            'isFeatured'   => (int) $this->property('isFeatured'),
            'isSearch'     => (int) $this->property('isSearch'),
            'isTranslated' => (int) $this->property('isTranslated'),
            'searchQuery'  => trim(input('q'))
        ]);

        return $faqs;
    }

    protected function loadCategory()
    {
        $slug = $this->property('categorySlug');
        if (!$slug) return null;

        $category = new Categories();
        $category = $category->isClassExtendedWith('Winter.Translate.Behaviors.TranslatableModel')
            ? $category->transWhere('slug', $slug)
            : $category->where('slug', $slug);
        $category = $category->first();

        return $category ? $category->id : null;
    }

    protected function faqsPerCategory($faqs)
    {

        // get properties
        $categoryId = (int) $this->property('categoryId');
        $sort = $this->property('sort');

        // get category name
        $categoryName = $this->getCategoryName($categoryId);

        // if category name is not 0 (all)
        if ($categoryName != 0) {

            // return the FAQs with their category
            return [
                [
                    'name' => $categoryName,
                    'faqs' => $faqs
                ]
            ];

        } else {

            // create new array
            $newArray = [];

            // prepare the array with the categories
            $categories = [];
            if ($sort == 'category_id asc') {
                $categories = Categories::orderBy('id', 'asc')->get();
            } else if ($sort == 'category_id desc') {
                $categories = Categories::orderBy('id', 'desc')->get();
            } else {
                $categories = Categories::get();
            }
           
            foreach($categories as $category) {
                $newArray[$category->id] = [
                    'categoryName' => $category->name,
                    'faqs' => []
                ];
            }

            // push the faq to the right category
            foreach ($faqs as $faq) {
                $newArray[$faq->category_id]['faqs'][] = $faq;

                // if categoryName doesn't exist
                // set no_category_label
                if (!array_key_exists('categoryName', $newArray[$faq->category_id])) {
                    $newArray[$faq->category_id]['categoryName'] = Lang::get('aic.faq::lang.component.settings.category.no_category_label');
                }
            }

            // remove empty categories
            foreach ($newArray as $key => $value) {
                $amountFaqs = count($value['faqs']);
                if ($amountFaqs == 0) unset($newArray[$key]);
            }

            // return the new array
            return $newArray;

        }

    }

    protected function getCategoryName($categoryId)
    { 
        if ($categoryId == 0) return 0;
        else return Categories::find($categoryId)->name;
    }

    public function getCategoryIdOptions()
    {
        // return all categories for the dropdown
        $categories = Categories::lists('name', 'id');
        $categories[0] = 'aic.faq::lang.component.settings.category.all';
        ksort($categories);
        return $categories;
    }
}
