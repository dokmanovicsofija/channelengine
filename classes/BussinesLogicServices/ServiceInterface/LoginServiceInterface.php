<?php

namespace classes\BussinesLogicServices\ServiceInterface;

/**
 * Interface LoginServiceInterface
 *
 * This interface defines the contract for the login service,
 * which is responsible for validating API keys by communicating
 * with the ChannelEngine API. Any class implementing this interface
 * must provide an implementation for the `validateCredentials` method.
 *
 * @package classes\BussinesLogicServices\ServiceInterface
 */
interface LoginServiceInterface
{
    /**
     * Handles the login process by validating the provided account name and API key.
     *
     * @param string $accountName The account name for the login process.
     * @param string $apiKey The API key for the login process.
     * @return bool Returns true if login is successful, false otherwise.
     */
    public function handleLogin(string $accountName, string $apiKey): bool;
}
