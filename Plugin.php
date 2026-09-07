<?php

namespace Aic\Faq;

use Aic\Faq\Console\ScaffoldCommand;
use Backend\Facades\Backend;
use Backend\Models\UserRole;
use Illuminate\Support\Facades\Lang;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'aic.faq::lang.plugin.name',
            'description' => 'aic.faq::lang.plugin.description',
            'author'      => 'Meindert Stijfhals',
            'icon'        => 'icon-question-circle'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     */
    public function register(): void
    {
        $this->registerConsoleCommand('scaffold:aic.faq', ScaffoldCommand::class);
    }

    /**
     * Register the backend navigation items provided by this plugin
     */
    public function registerNavigation(): array
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

    /**
     * Register the permissions provided by this plugin
     */
    public function registerPermissions(): array
    {
        return [
            'aic.faq.manage_categories' => [
                'tab' => 'aic.faq::lang.menu.faqs',
                'label' => 'aic.faq::lang.permissions.manage_categories',
                'roles' => [UserRole::CODE_DEVELOPER, UserRole::CODE_PUBLISHER],
            ],
            'aic.faq.manage_faqs' => [
                'tab' => 'aic.faq::lang.menu.faqs',
                'label' => 'aic.faq::lang.permissions.manage_faqs',
                'roles' => [UserRole::CODE_DEVELOPER, UserRole::CODE_PUBLISHER],
            ],
        ];
    }

    /**
     * Register the CMS components provided by this plugin
     */
    public function registerComponents(): array
    {
        return [
            'Aic\Faq\Components\Categories' => 'faqCategories',
            'Aic\Faq\Components\Faqs' => 'FAQ',
            'Aic\Faq\Components\FaqsBySlug' => 'FaqsBySlug',
        ];
    }

    /**
     * Register custom column types for the backend list view
     */
    public function registerListColumnTypes(): array
    {
        return [
            'publishedstatus' => function ($value) {
                $class = [
                    0 => 'text-danger',
                    1 => 'text-success',
                    2 => 'text-warning'
                ];

                return '<span class="wn-icon-circle '. ($class[(int) $value] ?? '') .'">'.
                    \Aic\Faq\Classes\Enums\PublishStatusEnum::nameTranslated((int) $value)
                    .'</span>';
            },
            'featuredstatus' => function ($value) {
                $isFeatured = (bool) $value;

                return '<span class="wn-icon-circle '. ($isFeatured ? 'text-success' : '') .'">'.
                    Lang::get('backend::lang.list.column_switch_'. ($isFeatured ? 'true' : 'false'))
                    .'</span>';
            }
        ];
    }
}
