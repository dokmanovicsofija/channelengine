<?php

namespace Infrastructure\Utility;

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
    private static array $services = [];

    /**
     * Registers a service in the registry.
     *
     * @param string $key The key under which the service is registered.
     * @param mixed $service The service instance to register.
     *
     * @return void
     */
    public static function register(string $key, mixed $service): void
    {
        self::$services[$key] = $service;
    }

    /**
     * Retrieves a registered service.
     *
     * @param string $key The key of the service to retrieve.
     *
     * @return mixed The service instance associated with the given key.
     *
     * @throws Exception If the service is not found in the registry.
     */
    public static function get(string $key): mixed
    {
        if (!isset(self::$services[$key])) {
            throw new Exception("Service not found: " . $key);
        }
        return self::$services[$key];
    }
}