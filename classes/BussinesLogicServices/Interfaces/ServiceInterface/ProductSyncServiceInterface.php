<?php

namespace classes\BussinesLogicServices\Interfaces\ServiceInterface;

/**
 * Interface ProductSyncServiceInterface
 *
 * Defines the contract for the product synchronization service that handles syncing products
 * between PrestaShop and external systems like ChannelEngine.
 */
interface ProductSyncServiceInterface
{
    /**
     * Synchronizes products with an external system.
     *
     * @return array An array containing the result of the synchronization, including success status and message.
     */
    public function syncProducts(): array;
}
