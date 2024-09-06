<?php

namespace classes\BussinesLogicServices;

use classes\BussinesLogicServices\ServiceInterface\ProductSyncServiceInterface;
use classes\Repositories\ProductRepository;
use classes\Utility\ChannelEngineProxy;

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
}