<?php namespace Aic\Faq;
use System\Classes\PluginBase;

use Backend;
use Event;
use Aic\Faq\Models\Categories;

class Plugin extends PluginBase {

    public function boot()
    {
        /* URL parameter and query string translation (Winter.Translate locale picker) */
        Event::listen('translate.localePicker.translateParams', function ($page, $params, $oldLocale, $newLocale) {
            if ($page->hasComponent('FAQ') || $page->hasComponent('faqCategories')) {
                return Categories::translateParams($params, $oldLocale, $newLocale);
            }
        });
    }

    public function pluginDetails()
    {
        return [
            'name'        => 'aic.faq::lang.plugin.name',
            'description' => 'aic.faq::lang.plugin.description',
            'author'      => 'Meindert Stijfhals',
            'icon'        => 'icon-question-circle'
        ];
    }

    public function registerNavigation()
    {

        return [
            'faq' => [
                'label'       => 'aic.faq::lang.menu.faqs',
                'url'         => Backend::url('aic/faq/faqs'),
                'icon'        => 'icon-question-circle',
                'permissions' => ['aic.faq.*'],
                'order'       => 900,

                'sideMenu' => [
                    'faqs' => [
                        'label'       => 'aic.faq::lang.menu.faqs',
                        'url'         => Backend::url('aic/faq/faqs'),
                        'icon'        => 'icon-question-circle',
                        'order'       => 100
                    ],
                    'categories' => [
                        'label'       => 'aic.faq::lang.menu.categories',
                        'url'         => Backend::url('aic/faq/categories'),
                        'icon'        => 'icon-folder-open-o',
                        'order'       => 200
                    ],
                    'settings' => [
                        'label'       => 'aic.faq::lang.menu.settings',
                        'url'         => Backend::url('aic/faq/settings'),
                        'icon'        => 'icon-cogs',
                        'order'       => 300
                    ]
                ]
            ]
        ];
    }

    public function registerPermissions()
    {
        return [
            'aic.faq.*' => [
                'tab' => 'aic.faq::lang.menu.faqs',
                'label' => 'aic.faq::lang.permission.faq'
            ]
        ];
    }

    public function registerComponents()
    {
        return [
            'Aic\Faq\Components\Faqs' => 'FAQ',
            'Aic\Faq\Components\Categories' => 'faqCategories'
        ];
    }
    
}