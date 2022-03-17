<?php namespace Aic\Faq\Controllers;
use Backend\Classes\Controller;

use BackendMenu;

class Categories extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    public $bodyClass = 'compact-container';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Aic.Faq', 'faq', 'categories');
    }
}