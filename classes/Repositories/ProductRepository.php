<?php

namespace classes\Repositories;

use classes\BussinesLogicServices\DomainModel\ProductDomainModel;
use classes\BussinesLogicServices\Interfaces\RepositoryInterface\ProductRepositoryInterface;
use Context;
use Image;
use Product;
use StockAvailable;

/**
 * Class ProductRepository
 *
 * Responsible for interacting with PrestaShop's product data.
 * This class fetches product information from the PrestaShop database and formats it for use in the application.
 */
class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Retrieves a list of products from PrestaShop.
     *
     * This method fetches product data, including the cover image and available stock quantity for each product.
     *
     * @return array An array of products with additional details such as image URL and stock quantity.
     */
    public function getProductsFromPrestaShop(): array
    {
        $idLang = (int)Context::getContext()->language->id;

        $products = Product::getProducts($idLang, 0, 0, 'id_product', 'ASC');

        foreach ($products as &$product) {
            $coverImage = Image::getCover($product['id_product']);
            $product['image_url'] = $coverImage
                ? Context::getContext()->link->getImageLink($product['link_rewrite'], $coverImage['id_image'],
                    'home_default')
                : 'path/to/default-image.jpg';

            $product['quantity'] = StockAvailable::getQuantityAvailableByProduct($product['id_product']);

            $domainProduct = new ProductDomainModel(
                $product['id_product'],
                $product['name'],
                $product['description'],
                $product['price'],
                $product['manufacturer_name'],
                $product['ean13'],
                $product['image_url'],
                $product['quantity']
            );
            $domainProducts[] = $domainProduct->toArray();
        }

        return $domainProducts;
    }

    /**
     * Retrieves a product from PrestaShop by its product ID and returns it as a ProductDomainModel.
     *
     * This method fetches the product details, including the cover image and stock availability, from PrestaShop.
     * If the product has a cover image, the image URL is generated; otherwise, a default image URL is used.
     * The product details are returned as an instance of the ProductDomainModel.
     *
     * @param int $productId The ID of the product to be retrieved.
     * @return ProductDomainModel|null The product data as a ProductDomainModel, or null if not found.
     */
    public function getProductById(int $productId): ?ProductDomainModel
    {
        $idLang = (int)Context::getContext()->language->id;
        $product = new Product($productId, false, $idLang);

        $coverImage = Image::getCover($product->id);
        $imageUrl = $coverImage
            ? Context::getContext()->link->getImageLink($product->link_rewrite, $coverImage['id_image'], 'home_default')
            : 'path/to/default-image.jpg';

        return new ProductDomainModel(
            $product->id,
            $product->name,
            $product->description,
            $product->price,
            $product->manufacturer_name ?? '',
            $product->ean13 ?? '',
            $imageUrl,
            StockAvailable::getQuantityAvailableByProduct($product->id)
        );
    }
}