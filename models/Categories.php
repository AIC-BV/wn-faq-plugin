<?php

namespace Aic\Faq\Models;

use Winter\Storm\Database\Model;

class Categories extends Model
{
    use \Aic\Faq\Classes\Traits\HasPublishStatus;
    use \Aic\Faq\Classes\Traits\Urlable;
    use \Winter\Storm\Database\Traits\Sluggable;
    use \Winter\Storm\Database\Traits\Sortable;
    use \Winter\Storm\Database\Traits\Validation;

    protected $table = 'aic_faq_categories';
    public $implement = ['@Winter.Translate.Behaviors.TranslatableModel'];

    /**
     * Validation rules
     */
    public $rules = [
        'name' => 'required',
        'slug' => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:aic_faq_categories'],
    ];

    /**
     * @var array Attributes that support slugs, if available.
     */
    protected $slugs = [
        'slug' => 'name'
    ];

    /**
     * @var array Attributes that support translation, if available.
     */
    public $translatable = [
        'name',
        ['slug', 'index' => true],
    ];

    /*
     * Relations
     */
    public $hasMany = [
        'faqs' => [
            Faqs::class,
            'key' => 'category_id',
            'order' => 'sort_order desc',
            'scope' => 'isPublished',
        ],
    ];

    /**
     * Allowed sorting options
     *
     * @var array
     */
    public static $allowedSorting = [
        'sort_order asc',
        'sort_order desc',
        'name asc',
        'name desc',
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
            $this->sort_order = static::max('sort_order') + 1;
        }
    }

    //
    // Getters
    //

    /**
     * Get the list of categories for dropdowns
     */
    public function getCategoriesListOptions(): array
    {
        return Categories::orderBy('name', 'asc')->lists('name', 'id');
    }
}
