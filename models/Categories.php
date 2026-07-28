<?php namespace Aic\Faq\Models;
use Model;

class Categories extends Model
{
    use \Winter\Storm\Database\Traits\Sluggable;
    use \Winter\Storm\Database\Traits\Validation;
    use \Winter\Storm\Database\Traits\Sortable;

    protected $table = 'aic_faq_categories';

    public $implement = [
        '@Winter.Translate.Behaviors.TranslatableModel'
    ];

    public $rules = [
        'name' => 'required',
        'slug' => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:aic_faq_categories']
    ];

    protected $slugs = [
        'slug' => 'name'
    ];

    public $translatable = [
        'name',
        ['slug', 'index' => true]
    ];

    /**************************************/
    // Set sort_order manually
    /**************************************/
    public function beforeValidate()
    {
        if ($this->sort_order === null) {
            $this->sort_order = static::max('sort_order') + 1;
        }
    }

    /**************************************/
    // Get dropdown options
    /**************************************/
    public function getCategoryOptions()
    {
        return Categories::orderBy('name', 'asc')->lists('name', 'id');
    }

    /**************************************/
    // URL parameter and query string translation
    /**************************************/
    public static function translateParams($params, $oldLocale, $newLocale)
    {
        $newParams = $params;
        foreach ($params as $paramName => $paramValue) {
            if ($paramName === 'page') {
                continue;
            }

            $records = self::transWhere($paramName, $paramValue, $oldLocale)->first();
            if ($records) {
                $newParams[$paramName] = $records->getAttributeTranslated($paramName, $newLocale);
            }
        }
        return $newParams;
    }
}
