<?php namespace Aic\Faq\Controllers;
use Backend\Classes\Controller;

use Illuminate\Support\Facades\Lang;
use Flash;
use Backend;
use BackendMenu;
use Aic\Faq\Models\Settings as SettingsModel;

class Settings extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class
    ];

    public $bodyClass = 'compact-container';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Aic.Faq', 'faq', 'settings');
    }

    /**************************************/
    // Settings
    /**************************************/
    public function index()
    {
        $this->pageTitle = Lang::get('aic.faq::lang.menu.settings');
        $this->asExtension('FormController')->update();
    }

    public function index_onSave()
    {
        return $this->asExtension('FormController')->update_onSave();
    }

    public function index_onResetDefault()
    {
        $model = SettingsModel::instance();
        $model->resetDefault();

        Flash::success(Lang::get('backend::lang.form.reset_success'));
        return Backend::redirect('aic/faq/settings');
    }

    public function formFindModelObject()
    {
        return SettingsModel::instance();
    }
}
