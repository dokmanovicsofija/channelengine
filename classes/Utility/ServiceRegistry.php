<?php

namespace classes\Utility;

use Exception;

/**
 * Class ServiceRegistry
 *
 * A simple service registry for managing service instances. This class allows you to register services
 * with a unique key and retrieve them later. It uses a static array to store services.
 */
class ServiceRegistry extends Singleton
{
    /**
     * @var array An associative array to hold registered services, with keys as service names and values as service instances.
     */
    private static $services = [];

    /**
     * Registers a service in the registry.
     *
     * @param string $key The key under which the service is registered.
     * @param $service
     * @return void
     */
    public static function register(string $key, $service): void
    {
        self::$services[$key] = $service;
    }

    /**
     * Retrieves a registered service.
     *
     * @param string $key The key of the service to retrieve.
     *
     *
     * @throws Exception If the service is not found in the registry.
     */
    public static function get(string $key)
    {
        if (!isset(self::$services[$key])) {
            throw new Exception("Service not found: " . $key);
        }

        return self::$services[$key];
    }
}