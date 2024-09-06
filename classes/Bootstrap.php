<?php

namespace classes;

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
        self::registerRepositories();
        self::registerServices();
        self::registerControllers();
    }

    /**
     * Registers repository instances with the service registry.
     */
    protected static function registerRepositories(): void
    {
//        ServiceRegistry::register(ProductRepositoryInterface::class, new ProductRepository());
//        ServiceRegistry::register(LoginRepositoryInterface::class, new LoginRepository());
    }

    /**
     * Registers service instances with the service registry.
     *
     * @throws Exception
     */
    protected static function registerServices(): void
    {
//        ServiceRegistry::register(ProductServiceInterface::class, new ProductService(
//            ServiceRegistry::get(ProductRepositoryInterface::class)
//        ));
//
//        ServiceRegistry::register(LoginServiceInterface::class, new LoginService(
//            ServiceRegistry::get(LoginRepositoryInterface::class)
//        ));
    }

    /**
     * Registers controller instances with the service registry.
     *
     * @throws Exception
     */
    protected static function registerControllers(): void
    {
//        ServiceRegistry::register(ProductController::class, new ProductController(
//            ServiceRegistry::get(ProductServiceInterface::class)
//        ));
//
//        ServiceRegistry::register(LoginController::class, new LoginController(
//            ServiceRegistry::get(LoginServiceInterface::class)
//        ));
    }
}
