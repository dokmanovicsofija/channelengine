<?php

namespace classes;

use classes\BussinesLogicServices\Interfaces\RepositoryInterface\ProductRepositoryInterface;
use classes\BussinesLogicServices\Interfaces\ServiceInterface\LoginServiceInterface;
use classes\BussinesLogicServices\Interfaces\ServiceInterface\ProductSyncServiceInterface;
use classes\BussinesLogicServices\Services\LoginService;
use classes\BussinesLogicServices\Services\ProductService;
use classes\Proxy\ChannelEngineProxy;
use classes\Repositories\ProductRepository;
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
        ServiceRegistry::getInstance()->register(LoginServiceInterface::class, new LoginService(
            ServiceRegistry::getInstance()->get(ChannelEngineProxy::class)
        ));
    }
}