<?php

use classes\Bootstrap;
use classes\BussinesLogicServices\Interfaces\ServiceInterface\ProductSyncServiceInterface;
use classes\Utility\ChannelEngineInstaller;
use classes\Utility\ServiceRegistry;

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
     * @throws Exception
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
     *
     * @return bool
     */
    public function install()
    {
        $installer = new ChannelEngineInstaller($this);
        return parent::install() && $installer->install();
    }

    /**
     * Uninstalls the module.
     *
     * @return bool
     * @throws PrestaShopException
     */
    public function uninstall()
    {
        $installer = new ChannelEngineInstaller($this);
        return parent::uninstall() && $installer->uninstall();
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
     * Hook that is triggered when a product is updated in PrestaShop.
     *
     * This method logs the hook trigger, retrieves the product ID, and attempts to synchronize the product
     * with ChannelEngine. In case of an error during the synchronization, it logs the error message.
     *
     * @param array $params Parameters of the hook, including the product ID being updated.
     * @throws Exception If there is an issue during product synchronization.
     */
    public
    function hookActionProductUpdate(
        $params
    ) {
        PrestaShopLogger::addLog('hookActionProductUpdate triggered for product ID: ' . $params['id_product'], 1);

        try {
            $productId = $params['id_product'];
            $productSyncService = ServiceRegistry::getInstance()->get(ProductSyncServiceInterface::class);
            $productSyncService->syncProductById($productId);
            PrestaShopLogger::addLog('Synchronization successful for product ID: ' . $productId, 1);
        } catch (Exception $e) {
            PrestaShopLogger::addLog('Error during sync in hookActionProductUpdate for product ID: ' . $productId . ' - ' . $e->getMessage(),
                3);
        }
    }
}
