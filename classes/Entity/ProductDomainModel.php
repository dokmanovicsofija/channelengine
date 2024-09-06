<?php

namespace classes\Entity;

/**
 * Class ProductDomainModel
 *
 * Represents a domain model for a product in the application.
 * This class encapsulates the product's properties and provides accessors for each field.
 */
class ProductDomainModel
{
    /**
     * @var int The unique identifier of the product.
     */
    private int $id;

    /**
     * @var string The name of the product.
     */
    private string $name;

    /**
     * @var string A short description of the product.
     */
    private string $description;

    /**
     * @var float The price of the product.
     */
    private float $price;

    /**
     * @var string The brand name of the product.
     */
    private ?string $brand;

    /**
     * @var string The EAN (European Article Number) code of the product.
     */
    private string $ean;

    /**
     * @var string The URL to the product's image.
     */
    private string $imageUrl;

    /**
     * @var int The available quantity of the product in stock.
     */
    private int $quantity;

    /**
     * ProductDomainModel constructor.
     *
     * @param int $id The unique identifier of the product.
     * @param string $name The name of the product.
     * @param string $description A short description of the product.
     * @param float $price The price of the product.
     * @param string $brand The brand name of the product.
     * @param string $ean The EAN code of the product.
     * @param string $imageUrl The URL to the product's image.
     * @param int $quantity The available quantity of the product in stock.
     */
    public function __construct(
        int $id,
        string $name,
        string $description,
        float $price,
        ?string $brand,
        string $ean,
        string $imageUrl,
        int $quantity
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->brand = $brand;
        $this->ean = $ean;
        $this->imageUrl = $imageUrl;
        $this->quantity = $quantity;
    }

    /**
     * Get the product ID.
     *
     * @return int The unique identifier of the product.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the product name.
     *
     * @return string The name of the product.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the product description.
     *
     * @return string A short description of the product.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get the product price.
     *
     * @return float The price of the product.
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Get the product brand name.
     *
     * @return string The brand name of the product.
     */
    public function getBrand(): ?string
    {
        return $this->brand;
    }

    /**
     * Get the product EAN code.
     *
     * @return string The EAN code of the product.
     */
    public function getEan(): string
    {
        return $this->ean;
    }

    /**
     * Get the product image URL.
     *
     * @return string The URL to the product's image.
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * Get the available quantity of the product.
     *
     * @return int The available quantity of the product in stock.
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    // Convert object to array
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'price' => $this->getPrice(),
            'brand' => $this->getBrand(),
            'ean' => $this->getEan(),
            'image_url' => $this->getImageUrl(),
            'quantity' => $this->getQuantity(),
        ];
    }
}