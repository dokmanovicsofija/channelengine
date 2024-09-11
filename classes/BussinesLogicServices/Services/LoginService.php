<?php

namespace classes\BussinesLogicServices\Services;

use classes\BussinesLogicServices\Interfaces\ServiceInterface\LoginServiceInterface;
use classes\Utility\ChannelEngineProxy;
use Configuration;

/**
 * Class LoginService
 *
 * This service handles the login process for the ChannelEngine module.
 * It validates the API key by communicating with ChannelEngine through a proxy,
 * and updates the PrestaShop configuration with the account name and API key upon successful login.
 */
class LoginService implements LoginServiceInterface
{
    /**
     * @var ChannelEngineProxy An instance of the proxy used to communicate with ChannelEngine.
     */
    private ChannelEngineProxy $channelEngineProxy;

    /**
     * Constructor to initialize the service with the proxy.
     *
     * @param ChannelEngineProxy $channelEngineProxy
     */
    public function __construct(ChannelEngineProxy $channelEngineProxy)
    {
        $this->channelEngineProxy = $channelEngineProxy;
    }

    /**
     * Handle login by validating API key and updating configuration.
     *
     * @param string $apiKey
     * @param string $accountName
     * @return bool Returns true if login is successful, false otherwise.
     */
    public function handleLogin(string $apiKey, string $accountName): bool
    {
        $response = $this->channelEngineProxy->validateCredentials($apiKey);

        if ($response === true) {
            Configuration::updateValue('CHANNELENGINE_ACCOUNT_NAME', $accountName);
            Configuration::updateValue('CHANNELENGINE_API_KEY', $apiKey);
            return true;
        }

        return false;
    }
}
