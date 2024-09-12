<?php

namespace classes\Utility;

use ChannelEngine;
use Configuration;
use PrestaShopException;
use Tab;

/**
 * Class ChannelEngineInstaller
 *
 * Handles the installation, uninstallation, and configuration of the ChannelEngine module.
 */
class ChannelEngineInstaller
{
    /**
     * Instance of the module.
     *
     * @var ChannelEngine
     */
    private $module;

    private static $hooks = array(
        'displayBackOfficeHeader',
        'actionProductUpdate'
    );

    /**
     * ChannelEngineInstaller constructor.
     *
     * @param ChannelEngine $module
     */
    public function __construct(ChannelEngine $module)
    {
        $this->module = $module;
    }

    /**
     * Initializes the module during installation.
     *
     * @return bool
     */
    public function install(): bool
    {
        return $this->addHooks() && $this->addMenuItem();
    }

    /**
     * Uninstalls the module.
     *
     * @return bool
     * @throws PrestaShopException
     */
    public function uninstall(): bool
    {
        $this->removeHooks();
        $this->removeMenuItem();

        return true;
    }

    /**
     * Adds hooks required by the module.
     *
     * @return bool
     */
    private function addHooks(): bool
    {
        $result = true;
        foreach (self::$hooks as $hook) {
            $result = $result && $this->module->registerHook($hook);
        }

        return $result;
    }

    /**
     * Removes hooks during uninstallation.
     *
     * @return bool
     */
    private function removeHooks(): bool
    {
        $result = true;
        foreach (self::$hooks as $hook) {
            $result = $result && $this->module->unregisterHook($hook);
        }

        return $result;
    }

    /**
     * Adds a menu item to the PrestaShop admin panel for this module.
     *
     * @return bool
     */
    private function addMenuItem(): bool
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminChannelEngine';
        $tab->module = $this->module->name;
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminParentModulesSf');
        $tab->name[(int)Configuration::get('PS_LANG_DEFAULT')] = 'Channel Engine';

        return $tab->add();
    }

    /**
     * Removes the menu item during uninstallation.
     *
     * @return bool
     * @throws PrestaShopException
     */
    private function removeMenuItem(): bool
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminChannelEngine');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }

        return true;
    }
}