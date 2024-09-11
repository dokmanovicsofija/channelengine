<?php

use classes\BussinesLogicServices\ServiceInterface\LoginServiceInterface;
use classes\Utility\ServiceRegistry;

if (!defined('_PS_VERSION_')) {
    exit;
}

/** @noinspection PhpIncludeInspection */
require_once rtrim(_PS_MODULE_DIR_, '/') . '/channelengine/vendor/autoload.php';

/**
 * Controller for the AdminChannelEngine section of the module.
 * It extends the ModuleAdminController and provides functionality
 * for handling specific actions within the module, such as displaying the login and configuration pages.
 */
class AdminChannelEngineController extends ModuleAdminController
{
    /**
     * Class constructor.
     * Enables Bootstrap for the PrestaShop admin panel and calls the parent constructor.
     * @throws PrestaShopException
     */
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
    }

    /**
     * Initializes the content for the page.
     * This method checks which action to perform based on the 'action' parameter
     * passed in the request. If the method exists, it is executed; otherwise, the defaultAction is called.
     *
     * @throws SmartyException If there is an issue rendering the Smarty template
     */
    public function initContent(): void
    {
        $action = Tools::getValue('action', 'defaultAction');

        if (method_exists($this, $action)) {
            $this->$action();
        } else {
            $this->defaultAction();
        }
    }

    /**
     * Default action to be executed when no specific action is provided.
     * This method prepares the configuration page, assigns the necessary variables to Smarty,
     * and renders the 'configure.tpl' template.
     *
     * @throws SmartyException If there is an issue rendering the Smarty template
     */
    protected function defaultAction(): void
    {
        if (Configuration::hasKey('CHANNELENGINE_ACCOUNT_NAME')) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminChannelEngine') . '&action=syncPage');
        }

        $loginUrl = $this->context->link->getAdminLink('AdminChannelEngine') . '&action=displayLogin';

        $this->context->smarty->assign([
            'module_dir' => $this->module->getPathUri(),
            'login_url' => $loginUrl,
        ]);

        $output = $this->context->smarty->fetch($this->module->getLocalPath() . 'views/templates/admin/configure.tpl');
        $this->context->smarty->assign('content', $output);
        $this->setTemplate('content.tpl');
    }

    /**
     * Displays the login page for the module.
     * This method assigns necessary variables and renders the 'login.tpl' template.
     *
     * @throws SmartyException If there is an issue rendering the Smarty template
     */
    protected function displayLogin($errorMessage = null): void
    {
        $loginUrl = $this->context->link->getAdminLink('AdminChannelEngine') . '&action=displayLogin';

        $this->context->smarty->assign([
            'module_dir' => $this->module->getPathUri(),
            'login_url' => $loginUrl,
        ]);

        if ($errorMessage) {
            $this->context->smarty->assign('error', $errorMessage);
        }

        $output = $this->context->smarty->fetch($this->module->getLocalPath() . 'views/templates/admin/login.tpl');
        $this->context->smarty->assign('content', $output);
        $this->setTemplate('content.tpl');
    }

    /**
     * Process the login form submission and validate the API key.
     * @throws SmartyException
     * @throws Exception
     */
    public function processLogin(): void
    {
        $accountName = Tools::getValue('account_name');
        $apiKey = Tools::getValue('api_key');

        if (empty($accountName) || empty($apiKey)) {
            $this->displayLogin('Account name and API key cannot be empty.');
            return;
        }

        $loginService = ServiceRegistry::get(LoginServiceInterface::class);

        if (!$loginService->handleLogin($apiKey, $accountName)) {
            $this->displayLogin('Login failed. Please check your credentials.');
            return;
        }

        Tools::redirectAdmin($this->context->link->getAdminLink('AdminChannelEngine') . '&action=syncPage');
    }

    /**
     * Display the sync page (after successful login).
     * @throws SmartyException
     */
    public function syncPage(): void
    {
        $syncUrl = $this->context->link->getAdminLink('AdminProduct') . '&action=startSync';

        $this->context->smarty->assign([
            'module_dir' => $this->module->getPathUri(),
            'sync_status' => 'In progress...',
            'admin_sync_link' => $syncUrl
        ]);

        $output = $this->context->smarty->fetch($this->module->getLocalPath() . 'views/templates/admin/sync.tpl');

        $this->context->smarty->assign('content', $output);

        $this->setTemplate('content.tpl');
    }
}