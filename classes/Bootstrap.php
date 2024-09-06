<?php

namespace classes;

use classes\BussinesLogicServices\ProductService;
use classes\BussinesLogicServices\RepositoryInterface\ProductRepositoryInterface;
use classes\BussinesLogicServices\ServiceInterface\ProductSyncServiceInterface;
use classes\Repositories\ProductRepository;
use classes\Utility\ChannelEngineProxy;
use classes\Utility\ServiceRegistry;
use Exception;

if (!defined('_PS_VERSION_')) {
    exit;
}

/** @noinspection PhpIncludeInspection */
require_once rtrim(_PS_MODULE_DIR_, '/') . '/channelengine/vendor/autoload.php';

/**
 * Class Bootstrap
 * This class is responsible for initializing and registering repositories, services, and controllers.
 */
class Bootstrap
{
    /**
     * Initializes the application by registering repositories, services, and controllers.
     *
     * @throws Exception
     */
    public static function init(): void
    {
        self::registerUtilities();
        self::registerRepositories();
        self::registerServices();
    }

    /**
     * Register utility classes like HttpClient and ChannelEngineProxy.
     */
    private static function registerUtilities(): void
    {
        ServiceRegistry::getInstance()->register(ChannelEngineProxy::class, new ChannelEngineProxy());
    }

    /**
     * Registers repository instances with the service registry.
     */
    protected static function registerRepositories(): void
    {
        ServiceRegistry::getInstance()->register(ProductRepositoryInterface::class, new ProductRepository());
    }

    /**
     * Registers service instances with the service registry.
     *
     * @throws Exception
     */
    protected static function registerServices(): void
    {
        ServiceRegistry::getInstance()->register(ProductSyncServiceInterface::class, new ProductService(
            ServiceRegistry::getInstance()->get(ProductRepositoryInterface::class),
            ServiceRegistry::getInstance()->get(ChannelEngineProxy::class)
        ));
    }
}
