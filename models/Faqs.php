<?php namespace Aic\Faq\Models;
use Model;

use Db;
use App;
use BackendAuth;
use Aic\Faq\Models\Categories;

class Faqs extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    public $table = 'aic_faq_faqs';

    public $implement = [
        '@Winter.Translate.Behaviors.TranslatableModel'
    ];

    public $rules = [
        'question'     => 'required',
        'answer'       => 'required',
        'is_published' => 'required|between:0,2|numeric',
        'is_featured'  => 'required|between:0,1|numeric'
    ];

    public $translatable = [
        'question',
        'answer'
    ];

    public static $allowedSorting = [
        'category_id asc',
        'category_id desc',
        'created_at asc',
        'created_at desc'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public $belongsTo = [
        'category' => ['Aic\Faq\Models\Categories']
    ];

    public function scopeIsPublished($query)
    {

        // if backend user is logged in
        if (BackendAuth::check()) {

            // return published and draft posts
            return $query->whereIn('is_published', [1, 2]);

        } else {

            // return published posts
            return $query->where('is_published', 1);

        }

    }

    public function scopeIsFeatured($query, $isFeatured)
    {

        // return all featured_statusses
        if ($isFeatured == 2) return;

        // return the featured_status (featured or not_featured)
        return $query->where('is_featured', $isFeatured);

    }

    public function scopeCategory($query, $categoryId)
    {

        // return all categories
        if ($categoryId == 0) return;

        // return the category
        return $query->where('category_id', $categoryId);

    }

    public function scopeTranslatedOnly($query, $isTranslated)
    {

        // cancel if translated is not checked
        if (!$isTranslated) return;

        // cancel if Winter.Translate isn't installed
        if (!class_exists('Winter\Translate\Behaviors\TranslatableModel')) return;

        // get current and default locale
        $currentLocale = App::getLocale();
        $defaultLocale = Db::table('winter_translate_locales')->where('is_default', 1)->value('code');

        // get which FAQs can be shown
        if ($currentLocale != $defaultLocale) {
            $ids = Db::table('winter_translate_attributes')
                        ->where('model_type', 'Aic\Faq\Models\Faqs')
                        ->where('locale', $currentLocale)
                        ->where('attribute_data', 'not like', '%"question":""%')
                        ->pluck('model_id');
            return $query->whereIn('id', $ids);
        }

    }
    
    public function scopeSearchQuery($query, $searchQuery, $searchableFields)
    {

        // cancel is searchQuery is empty
        if ($searchQuery == '') return;

        // if Winter.Translate is installed
        if (class_exists('Winter\Translate\Behaviors\TranslatableModel')) {

            // get current and default locale
            $currentLocale = App::getLocale();
            $defaultLocale = Db::table('winter_translate_locales')->where('is_default', 1)->value('code');

            // search on the FAQs in the correct language
            if ($currentLocale != $defaultLocale) {

                $ids = Db::table('winter_translate_attributes')
                            ->where('model_type', 'Aic\Faq\Models\Faqs')
                            ->where('locale', $currentLocale)
                            ->where('attribute_data', 'like', '%' . $searchQuery . '%')
                            ->pluck('model_id');
                return $query->whereIn('id', $ids);

            }

        }

        // return the search if Winter.Translate is NOT installed
        // and if $currentLocale == $defaultLocale
        return $query->searchWhere($searchQuery, $searchableFields);

    }

    public function scopeSortFAQs($query, $sort)
    {

        foreach (self::$allowedSorting as $sorter) {

            // check if sorter is equal to sort
            if ($sorter != $sort) continue;

            // split sort method
            $sort = explode(' ', $sort);

            // sort the query
            $query->orderBy($sort[0], $sort[1]);

        }

    }

    public function scopeListFrontEnd($query, $options)
    {

        // merge settings with component default properties
        extract(array_merge([
            'categoryId' => 0,
            'isFeatured' => 2,
            'isSearch' => 1,
            'isTranslated' => 1,
            'isPublished' => 1,
            'searchQuery' => ''
        ], $options));

        // define search fields
        $searchFields = [
            'question',
            'answer'
        ];

        // set query
        $query->isPublished();
        $query->isFeatured($isFeatured);
        $query->category($categoryId);
        $query->translatedOnly($isTranslated);
        $query->searchQuery($searchQuery, $searchFields);
        $query->sortFAQs($sort);

        // get FAQs based on the query
        return $query->get();

    }
}
