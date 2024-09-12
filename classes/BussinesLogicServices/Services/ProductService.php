<?php

namespace classes\BussinesLogicServices\Services;

use classes\BussinesLogicServices\Interfaces\ServiceInterface\ProductSyncServiceInterface;
use classes\Proxy\ChannelEngineProxy;
use classes\Repositories\ProductRepository;
use Configuration;
use PrestaShopLogger;

/**
 * Class ProductService
 *
 * This class implements the ProductSyncServiceInterface and provides functionality
 * to synchronize products from PrestaShop to an external system (e.g., ChannelEngine).
 */
class ProductService implements ProductSyncServiceInterface
{
    /**
     * @var ProductRepository Handles retrieving and managing products from PrestaShop.
     */
    private $productRepository;
    /**
     * @var ChannelEngineProxy Responsible for communicating with the ChannelEngine API.
     */
    private $channelEngineProxy;

    /**
     * ProductService constructor.
     *
     * @param ProductRepository $productRepository The repository for fetching products.
     * @param ChannelEngineProxy $channelEngineProxy The proxy for sending products to ChannelEngine.
     */
    public function __construct(ProductRepository $productRepository, ChannelEngineProxy $channelEngineProxy)
    {
        $this->productRepository = $productRepository;
        $this->channelEngineProxy = $channelEngineProxy;
    }

    /**
     * Synchronizes products from PrestaShop to ChannelEngine.
     *
     * @return array The response from ChannelEngine after attempting to sync the products.
     */
    public function syncProducts(): array
    {
        $credentials = $this->getCredentials();
        $products = $this->productRepository->getProductsFromPrestaShop();
        $formattedProducts = $this->formatProductsForChannelEngine($products);

        return $this->channelEngineProxy->sendProducts($credentials['accountName'], $credentials['apiKey'],
            $formattedProducts);
    }

    /**
     * Synchronizes a single product with ChannelEngine by its product ID.
     *
     * This method retrieves the product from the repository, formats it, and then sends it to ChannelEngine.
     * If the product is not found, it throws an exception. The response from ChannelEngine is logged for debugging purposes.
     *
     * @param int $productId The ID of the product to be synchronized.
     * @return array The response from ChannelEngine after attempting to sync the product.
     * @throws \Exception If the product is not found in the repository.
     */
    public function syncProductById(int $productId): array
    {
        $credentials = $this->getCredentials();
        $product = $this->productRepository->getProductById($productId);

        if (!$product) {
            throw new \Exception("Product with ID $productId not found.");
        }

        $formattedProduct = $product->toArray();
        $response = $this->channelEngineProxy->sendProducts($credentials['accountName'], $credentials['apiKey'],
            [$formattedProduct]);
        PrestaShopLogger::addLog('ChannelEngine API Response: ' . print_r($response, true), 1);

        return $response;
    }

    /**
     * Formats PrestaShop products for ChannelEngine API.
     *
     * @param array $products The array of products retrieved from PrestaShop.
     * @return array The products formatted for the ChannelEngine API.
     */
    private function formatProductsForChannelEngine(array $products): array
    {
        $formattedProducts = [];
        foreach ($products as $product) {
            $formattedProducts[] = [
                'Name' => $product['name'],
                'Description' => $product['description'],
                'MerchantProductNo' => $product['MerchantProductNo'],
                'Price' => $product['price'],
                'VatRateType' => 'STANDARD',
                'Brand' => $product['brand'] ?? '',
                'Ean' => $product['ean'] ?? '',
                'ManufacturerProductNumber' => $product['reference'] ?? '',
                'CategoryTrail' => $product['id_category_default'] ?? '',
                'ImageUrl' => $product['image_url'],
                'Quantity' => $product['quantity'],
            ];
        }

        return $formattedProducts;
    }

    /**
     * Retrieves the ChannelEngine account credentials from the PrestaShop configuration.
     *
     * This method fetches the account name and API key stored in the PrestaShop configuration
     * and returns them as an associative array.
     *
     * @return array An associative array containing 'accountName' and 'apiKey' keys.
     */
    private function getCredentials(): array
    {
        return [
            'accountName' => Configuration::get('CHANNELENGINE_ACCOUNT_NAME'),
            'apiKey' => Configuration::get('CHANNELENGINE_API_KEY'),
        ];
    }
}