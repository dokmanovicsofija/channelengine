<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

/** @noinspection PhpIncludeInspection */
require_once rtrim(_PS_MODULE_DIR_, '/') . '/channelengine/vendor/autoload.php';

use Sofija\Channelengine\BussinesLogicServices\Interfaces\ServiceInterface\ProductSyncServiceInterface;
use Sofija\Channelengine\Utility\ServiceRegistry;

/**
 * Class AdminProductController
 *
 * Controller for managing product synchronization with ChannelEngine in the PrestaShop admin panel.
 * Extends the default PrestaShop ModuleAdminController to handle actions like displaying the sync page and
 * starting the product sync process.
 */
class AdminProductController extends ModuleAdminController
{
    /**
     * Class constructor.
     * Enables Bootstrap for the PrestaShop admin panel and calls the parent constructor.
     */
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
    }

    /**
     * Init Content
     *
     * This method initializes the content of the page based on the 'action'
     * parameter from the request. If the specified action exists as a method, it is called;
     * otherwise, the defaultAction method is called.
     */
    public function initContent()
    {
        $action = Tools::getValue('action', 'defaultAction');

        if (method_exists($this, $action)) {
            $this->$action();
        } else {
            $this->defaultAction();
        }
    }

    /**
     * Default action to load the default page content.
     *
     * This method renders the sync page and assigns the necessary variables for the front-end template.
     *
     * @throws SmartyException
     */
    public function defaultAction()
    {
        $this->context->smarty->assign([
            'module_dir' => $this->module->getPathUri(),
            'admin_sync_link' => $this->context->link->getAdminLink('AdminProduct') . '&action=startSync',
        ]);

        $output = $this->context->smarty->fetch($this->module->getLocalPath() .
            'views/templates/admin/sync.tpl');
        $this->context->smarty->assign('content', $output);
        $this->setTemplate('content.tpl');
    }

    /**
     * Starts the product synchronization process.
     *
     * This method uses the ProductSyncService to sync products with ChannelEngine.
     * It handles both success and error cases, sending a JSON response accordingly.
     */
    public function startSync()
    {
        try {
            $productSyncService = ServiceRegistry::get(ProductSyncServiceInterface::class);
            $response = $productSyncService->syncProducts();

            if (isset($response['StatusCode']) && $response['StatusCode'] == 200 && $response['Success'] === true) {
                $this->sendJsonResponse(true, 'Product synchronization successful!');
            } else {
                $this->sendJsonResponse(false, 'Synchronization failed: ' .
                    ($response['Message'] ?? 'Unknown error'));
            }
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Sends a JSON response to the front-end.
     *
     * This method sends a JSON response containing the success status and a message.
     *
     * @param bool $success Indicates whether the operation was successful or not.
     * @param string $message A message to send in the JSON response.
     */
    private function sendJsonResponse(bool $success, string $message)
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}