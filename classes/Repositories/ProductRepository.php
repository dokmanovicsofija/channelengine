<?php

namespace classes\Repositories;

use classes\BussinesLogicServices\RepositoryInterface\ProductRepositoryInterface;
use classes\Entity\ProductDomainModel;
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