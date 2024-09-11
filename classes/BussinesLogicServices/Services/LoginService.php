<?php

namespace classes\BussinesLogicServices\Services;

use classes\BussinesLogicServices\Interfaces\ServiceInterface\LoginServiceInterface;
use classes\Utility\ChannelEngineProxy;
use Configuration;

class LoginService implements LoginServiceInterface
{
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
