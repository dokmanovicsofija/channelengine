<?php

namespace Sofija\Channelengine;

use Sofija\Channelengine\BussinesLogicServices\Interfaces\RepositoryInterface\ProductRepositoryInterface;
use Sofija\Channelengine\BussinesLogicServices\Interfaces\ServiceInterface\LoginServiceInterface;
use Sofija\Channelengine\BussinesLogicServices\Interfaces\ServiceInterface\ProductSyncServiceInterface;
use Sofija\Channelengine\BussinesLogicServices\Services\LoginService;
use Sofija\Channelengine\BussinesLogicServices\Services\ProductService;
use Sofija\Channelengine\Proxy\ChannelEngineProxy;
use Sofija\Channelengine\Repositories\ProductRepository;
use Sofija\Channelengine\Utility\ServiceRegistry;
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