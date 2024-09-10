<?php

namespace classes\BussinesLogicServices;

use classes\BussinesLogicServices\ServiceInterface\ProductSyncServiceInterface;
use classes\Entity\ProductDomainModel;
use classes\Repositories\ProductRepository;
use classes\Utility\ChannelEngineProxy;
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
    private ProductRepository $productRepository;
    /**
     * @var ChannelEngineProxy Responsible for communicating with the ChannelEngine API.
     */
    private ChannelEngineProxy $channelEngineProxy;

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
        $products = $this->productRepository->getProductsFromPrestaShop();

        $formattedProducts = $this->formatProductsForChannelEngine($products);

        return $this->channelEngineProxy->sendProducts($formattedProducts);
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
                'MerchantProductNo' => $product['id'],
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
     * @throws \Exception
     */
    public function syncProductById(int $id_product): array
    {
        $product = $this->productRepository->getProductById($id_product);

        if (!$product) {
            throw new \Exception("Product with ID $id_product not found.");
        }

        $formattedProduct = $product->toArray();

        $response = $this->channelEngineProxy->sendProducts([$formattedProduct]);

        PrestaShopLogger::addLog('ChannelEngine API Response: ' . print_r($response, true), 1);

        return $response;
    }
}