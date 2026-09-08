<?php

namespace Aic\Faq\Controllers;

use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;

class Categories extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
        \Backend\Behaviors\ReorderController::class,
    ];

    /**
     * @var array Required permissions
     */
    public $requiredPermissions = ['aic.faq.manage_categories'];

    /**
     * @var string Body class property used for customising the layout
     */
    public $bodyClass = 'compact-container';

    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Aic.Faq', 'faq', 'categories');
    }
}
