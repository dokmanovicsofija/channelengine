<?php

namespace classes\BussinesLogicServices\Interfaces\RepositoryInterface;

use classes\BussinesLogicServices\DomainModel\ProductDomainModel;

/**
 * Interface ProductRepositoryInterface
 *
 * Defines the contract for the Product repository that retrieves products from PrestaShop.
 */
interface ProductRepositoryInterface
{
    /**
     * Retrieves products from PrestaShop.
     *
     * @return array Returns an array of products retrieved from PrestaShop.
     */
    public function getProductsFromPrestaShop(): array;

    /**
     * Retrieves a specific product by its ID.
     *
     * This method fetches a product based on the provided product ID.
     * If the product is found, it returns a ProductDomainModel object representing the product.
     * If the product is not found, it returns null.
     *
     * @param int $productId The ID of the product to retrieve.
     * @return ProductDomainModel|null The product domain model if found, or null if the product does not exist.
     */
    public function getProductById(int $productId): ?ProductDomainModel;
}
