<?php

class AdminChannelEngineController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
    }

    public function initContent()
    {
        $this->display = 'view';
        parent::initContent();
        $this->context->smarty->assign(array(
            'module_dir' => $this->module->getPathUri(),
        ));
        $this->setTemplate('admin/configure.tpl');

        Tools::redirectAdmin(
            $this->context->link->getAdminLink('AdminModules')
            . '&configure=' . $this->module->name
            . '&token=' . Tools::getAdminTokenLite('AdminModules')
        );
    }
}
