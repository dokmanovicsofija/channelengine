<?php

namespace classes\BussinesLogicServices\RepositoryInterface;

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
}
