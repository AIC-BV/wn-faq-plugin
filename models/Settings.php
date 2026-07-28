<?php namespace Aic\Faq\Models;
use Winter\Storm\Database\Model;

class Settings extends Model
{
    public $implement = ['@Winter.Translate.Behaviors.TranslatableModel', '@System.Behaviors.SettingsModel'];
    public $settingsCode = 'aic_faq_settings';
    public $settingsFields = 'fields.yaml';

    public $translatable = [
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'title',
        'intro',
        'blocks'
    ];
}
