<?php namespace Aic\Faq;
use System\Classes\PluginBase;

use Backend;

class Plugin extends PluginBase {

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
            'Aic\Faq\Components\Faqs' => 'FAQ'
        ];
    }
    
}