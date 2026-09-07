<?php

namespace Aic\Faq\Models;

use Backend\Facades\BackendAuth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Winter\Storm\Database\Model;
use Winter\Storm\Database\Builder;
use Winter\Storm\Support\Facades\DB;

class Faqs extends Model
{
    use \Aic\Faq\Classes\Traits\HasPublishStatus;
    use \Winter\Storm\Database\Traits\Validation;
    use \Winter\Storm\Database\Traits\Sortable;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'aic_faq_faqs';
    public $implement = ['@Winter.Translate.Behaviors.TranslatableModel'];

    /**
     * Validation rules
     */
    public $rules = [
        'question'     => 'required',
        'answer'       => 'required',
        'is_published' => 'required|between:0,2|numeric',
        'is_featured'  => 'required|between:0,1|numeric'
    ];

    /**
     * @var array Attributes that support translation, if available.
     */
    public $translatable = [
        'question',
        'answer'
    ];

    /**
     * @var array Attribute casts, matching the boolean `is_featured` column.
     */
    protected $casts = [
        'is_featured' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /*
     * Relations
     */
    public $belongsTo = [
        'category' => ['Aic\Faq\Models\Categories']
    ];

    /**
     * Allowed sorting options
     *
     * @var array
     */
    public static $allowedSorting = [
        // Sorting relationships will be available with Winter v1.2.13+
        // 'sort_order asc',
        // 'sort_order desc',
        'question asc',
        'question desc',
        'category_id asc',
        'category_id desc',
        'created_at asc',
        'created_at desc',
        'updated_at asc',
        'updated_at desc',
        'random',
    ];


    /**
     * Before validation event
     */
    public function beforeValidate()
    {
        if ($this->sort_order === null) {
            $this->sort_order = static::where('category_id', $this->category_id)->max('sort_order') + 1;
        }
    }

    /**
     * Check whether Winter.Translate is available and its required tables exist.
     */
    protected static function canUseTranslateTables(): bool
    {
        return class_exists('Winter\\Translate\\Behaviors\\TranslatableModel')
            && Schema::hasTable('winter_translate_locales')
            && Schema::hasTable('winter_translate_attributes');
    }

    //
    // Scopes
    //

    /**
     * Scope a query to only include published FAQs.
     *
     * @param  Builder  $query      QueryBuilder
     *
     * @return Builder              QueryBuilder
     */
    public function scopeIsPublished(Builder $query): Builder
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

    /**
     * Scope a query to only include featured or not featured FAQs.
     *
     * @param  Builder  $query      QueryBuilder
    * @param  int      $isFeatured Featured status (0 = not featured, 1 = featured)
     *
     * @return Builder              QueryBuilder
     */
    public function scopeIsFeatured(Builder $query, int $isFeatured): Builder
    {
        return $query->where('is_featured', $isFeatured);
    }

    /**
     * Scope a query to only include FAQs of a specific category.
     *
     * @param  Builder  $query      QueryBuilder
     * @param  int      $categoryId Category id
     *
     * @return Builder              QueryBuilder
     */
    public function scopeCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope a query to only include FAQs that are translated.
     *
     * @param  Builder  $query        QueryBuilder
     * @param  bool                      $isTranslated Translated status (true = translated, false = all)
     *
     * @return Builder                QueryBuilder
     */
    public function scopeTranslatedOnly(Builder $query, bool $isTranslated): Builder
    {
        if (self::canUseTranslateTables()) {
            // get current and default locale
            $currentLocale = App::getLocale();
            $defaultLocale = DB::table('winter_translate_locales')->where('is_default', 1)->value('code');

            // get which FAQs can be shown
            if ($defaultLocale !== null && $currentLocale != $defaultLocale) {
                $ids = DB::table('winter_translate_attributes')
                    ->where('model_type', 'Aic\Faq\Models\Faqs')
                    ->where('locale', $currentLocale)
                    ->where('attribute_data', 'not like', '%"question":""%')
                    ->pluck('model_id');

                return $query->whereIn('id', $ids);
            }
        }

        return $query;
    }

    /**
     * Scope a query to only include FAQs that match the search query.
     *
     * @param  Builder  $query          QueryBuilder
     * @param  string   $searchQuery    Search query
     * @param  array    $searchableFields List of searchable fields
     *
     * @return Builder                  QueryBuilder
     */
    public function scopeSearchQuery(Builder $query, string $searchQuery, array $searchableFields): Builder
    {
        if (self::canUseTranslateTables()) {
            // get current and default locale
            $currentLocale = App::getLocale();
            $defaultLocale = DB::table('winter_translate_locales')->where('is_default', 1)->value('code');

            // search on the FAQs in the correct language
            if ($defaultLocale !== null && $currentLocale != $defaultLocale) {
                $ids = DB::table('winter_translate_attributes')
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

    /**
     * Scope a query to sort FAQs based on the allowed sorting options.
     *
     * @param  Builder  $query QueryBuilder
     * @param  string   $sort  Sorting option
     *
     * @return Builder        QueryBuilder
     */
    public function scopeSortFAQs(Builder $query, string $sort): Builder
    {
        if (!in_array($sort, self::$allowedSorting)) {
            return $query;
        }

        if ($sort === 'random') {
            return $query->inRandomOrder();
        }

        [$column, $direction] = explode(' ', $sort);
        $query->orderBy($column, $direction);

        return $query;
    }

    /**
     * Scope a query to get FAQs based on the options provided by the FAQ component.
     *
     * @param  Builder  $query   QueryBuilder
     * @param  array    $options Options provided by the FAQ component
     *
     * @return \Winter\Storm\Database\Collection
     */
    public function scopeListFrontEnd(Builder $query, array $options): \Winter\Storm\Database\Collection
    {
        // merge settings with component default properties
        extract(array_merge([
            'categoryId' => 0,
            'isFeatured' => null,
            'isSearch' => 1,
            'isTranslated' => 1,
            'isPublished' => 1,
            'searchQuery' => ''
        ], $options));

        // set query
        $query->isPublished();

        // Exclude FAQs attached to an unpublished category, keeping uncategorized FAQs
        $query->where(function (Builder $query) {
            $query->whereNull('category_id')
                ->orWhereHas('category', function (Builder $query) {
                    $query->where('is_published', 1);
                });
        });

        // Apply featured filter if a specific featured status is selected
        if ($isFeatured !== null) {
            $query->isFeatured($isFeatured);
        }

        // Apply category filter if a specific category is selected
        if ($categoryId !== 0) {
            $query->category($categoryId);
        }

        // Apply scope to only include translated FAQs if the isTranslated option is true
        if ($isTranslated) {
            $query->translatedOnly($isTranslated);
        }

        // Apply search query to the query
        if ($isSearch) {
            $query->searchQuery($searchQuery, [
                'question',
                'answer'
            ]);
        }

        // Apply sorting to the query
        if (isset($options['sort'])) {
            $query->sortFAQs($options['sort']);
        }

        // get FAQs based on the query
        return $query->get();
    }

    //
    // Getters
    //

    /**
     * Set the URL for this record instance
     */
    public function getFeaturedStatusOptions(): array
    {
        return \Aic\Faq\Classes\Enums\FeaturedStatusEnum::namesTranslated();
    }

    /**
     * Get the question and category name for this record instance
     */
    public function getQuestionAndCategoryAttribute(): string
    {
        $categoryName = $this->category ? " ({$this->category->name})" : '';

        return $this->question . $categoryName;
    }
}
