<?php

use classes\Bootstrap;

require_once __DIR__ . '/vendor/autoload.php';

// Ensure that the script is being run within PrestaShop's environment
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Main module class for the ChannelEngine PrestaShop module.
 * This class handles the installation, uninstallation, and configuration
 * of the ChannelEngine module within PrestaShop.
 */
class ChannelEngine extends Module
{
    /**
     * Class constructor.
     * Initializes the module properties, such as name, version, author,
     * and compatibility with PrestaShop versions. It also enables Bootstrap styling.
     */
    public function __construct()
    {
        $this->name = 'channelengine';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Sofija';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('channelEngine');
        $this->description = $this->l('Sofija channelEngine');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        Bootstrap::init();
    }

    /**
     * Installs the module.
     * Registers necessary hooks and adds the admin tab for module configuration.
     *
     * @return bool Returns true if installation is successful, false otherwise
     */
    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->installTab();
    }

    /**
     * Uninstalls the module.
     * Removes the admin tab created during installation.
     *
     * @return bool Returns true if uninstallation is successful, false otherwise
     */
    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallTab();
    }

    /**
     * Adds a tab in the PrestaShop admin panel for managing the module.
     * This tab will appear under the "Modules" section in the admin menu.
     *
     * @return bool Returns true if the tab is successfully added, false otherwise
     */
    public function installTab()
    {
        $tab = new Tab();
        $tab->class_name = 'AdminChannelEngine';
        $tab->module = $this->name;
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminParentModulesSf');
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Channel Engine';
        }
        return $tab->add();
    }

    /**
     * Removes the admin tab created during the installation of the module.
     *
     * @return bool Returns true if the tab is successfully removed, false otherwise
     */
    public function uninstallTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminChannelEngine');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }

        return true;
    }

    /**
     * Hook that adds CSS and JS to the back-office header.
     * This hook ensures that the required styles and scripts for the module are loaded
     * when the admin page is displayed.
     */
    public function hookDisplayBackOfficeHeader(): void
    {
        if (Tools::getValue('controller') == 'AdminChannelEngine') {
            $this->context->controller->addCSS($this->_path . 'views/css/admin.css');
            $this->context->controller->addCSS($this->_path . 'views/css/login.css');
            $this->context->controller->addJS($this->_path . 'views/js/sync.js');
            $this->context->controller->addCSS($this->_path . 'views/css/sync.css');
        }
    }

    /**
     * Redirects the user to the module's configuration page.
     * This method is called when the user clicks on the "Configure" button in the module list.
     */
    public function getContent()
    {
        $link = $this->context->link->getAdminLink('AdminChannelEngine');
        Tools::redirectAdmin($link);
    }
}
