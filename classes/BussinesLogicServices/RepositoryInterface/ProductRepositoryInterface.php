<?php

namespace classes\BussinesLogicServices\RepositoryInterface;

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

    public function getProductById(int $productId): ?ProductDomainModel;
}
