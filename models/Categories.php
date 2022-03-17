<?php namespace Aic\Faq\Models;
use Model;

class Categories extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    protected $table = 'aic_faq_categories';

    public $implement = [
        '@Winter.Translate.Behaviors.TranslatableModel'
    ];

    public $rules = [
        'name' => 'required'
    ];

    public $translatable = [
        'name'
    ];

    /**************************************/
    // Get dropdown options
    /**************************************/
    public function getCategoryOptions()
    {
        return Categories::orderBy('name', 'asc')->lists('name', 'id');
    }
}