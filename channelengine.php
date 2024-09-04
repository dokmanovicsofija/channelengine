<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class ChannelEngine extends Module
{
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

        $this->ps_versions_compliancy = array('min' => '8.1', 'max' => _PS_VERSION_);
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->installTab();
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallTab();
    }

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

    public function uninstallTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminChannelEngine');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }

        return true;
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('controller') == 'AdminChannelEngine') {
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
        }
    }

    public function getContent()
    {
        $this->context->smarty->assign(array(
            'module_dir' => $this->_path,
            'some_variable' => 'This is some dynamic content',
        ));

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }
}
