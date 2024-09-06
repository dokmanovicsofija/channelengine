<?php

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
     */
    public function processLogin(): void
    {
        $accountName = Tools::getValue('account_name');
        $apiKey = Tools::getValue('api_key');

        if (empty($accountName) || empty($apiKey)) {
            $this->displayLogin('Account name and API key cannot be empty.');
            return;
        }

        $url = 'https://logeecom-1-dev.channelengine.net/api/v2/settings?apikey=' . $apiKey;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['accept: application/json']);

        $response = curl_exec($ch);
        curl_close($ch);

        $responseData = json_decode($response, true);

        if ($responseData['StatusCode'] == 200 && $responseData['Success'] === true) {
            Configuration::updateValue('CHANNELENGINE_ACCOUNT_NAME', $accountName);
            Configuration::updateValue('CHANNELENGINE_API_KEY', $apiKey);

            Tools::redirectAdmin($this->context->link->getAdminLink('AdminChannelEngine') . '&action=syncPage');
        } else {
            $this->displayLogin('Login failed. Please check your credentials.');
        }
    }

    /**
     * Display the sync page (after successful login).
     * @throws SmartyException
     */
    public function syncPage(): void
    {
        $syncUrl = $this->context->link->getAdminLink('AdminChannelEngine') . '&action=syncProducts';

        $this->context->smarty->assign([
            'module_dir' => $this->module->getPathUri(),
            'sync_status' => 'In progress...',
            'admin_sync_link' => $syncUrl
        ]);

        $output = $this->context->smarty->fetch($this->module->getLocalPath() . 'views/templates/admin/sync.tpl');

        $this->context->smarty->assign('content', $output);

        $this->setTemplate('content.tpl');
    }

    public function syncProducts()
    {
        $syncResult = $this->syncProductsToChannelEngine();

        header('Content-Type: application/json');
        echo json_encode($syncResult);
        exit;
    }

    public function syncProductsToChannelEngine()
    {
        $products = $this->getProductsFromPrestaShop();

        $formattedProducts = $this->formatProductsForChannelEngine($products);

        $response = $this->sendProductsToChannelEngine($formattedProducts);

        if ($response['StatusCode'] == 200 && $response['Success'] === true) {
            return ['success' => true, 'message' => 'Synchronization completed successfully.'];
        } else {
            return ['success' => false, 'message' => 'Synchronization failed: ' . $response['Message']];
        }
    }

    private function getProductsFromPrestaShop()
    {
        $idLang = (int)Context::getContext()->language->id;

        $products = Product::getProducts(
            $idLang,
            0,
            0,
            'id_product',
            'ASC'
        );

        foreach ($products as &$product) {
            $coverImage = Image::getCover($product['id_product']);
            if ($coverImage) {
                $product['image_url'] = Context::getContext()->link->getImageLink(
                    $product['link_rewrite'],
                    $coverImage['id_image'],
                    'home_default'
                );
            } else {
                $product['image_url'] = 'path/to/default-image.jpg';
            }

            $product['quantity'] = StockAvailable::getQuantityAvailableByProduct($product['id_product']);
        }
            return $products;
        }

        private
        function formatProductsForChannelEngine($products)
        {
            $formattedProducts = [];
            foreach ($products as $product) {
                $formattedProducts[] = [
                    'Name' => $product['name'],
                    'Description' => $product['description_short'],
                    'MerchantProductNo' => $product['id_product'],
                    'Price' => $product['price'],
                    'VatRateType' => 'STANDARD',
                    'Brand' => $product['manufacturer_name'],
                    'Ean' => $product['ean13'],
                    'ManufacturerProductNumber' => $product['reference'],
                    'CategoryTrail' => $product['id_category_default'],
                    'ImageUrl' => $product['image_url'],
                    'Quantity' => $product['quantity'],
                ];
            }
            return $formattedProducts;
        }

        private
        function sendProductsToChannelEngine($products)
        {
            $apiKey = Configuration::get('CHANNELENGINE_API_KEY');
            $url = 'https://logeecom-1-dev.channelengine.net/api/v2/products?apikey=' . $apiKey;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($products));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'accept: application/json',
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            return json_decode($response, true);
        }
    }