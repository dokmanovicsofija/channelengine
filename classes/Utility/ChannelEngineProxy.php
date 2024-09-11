<?php

namespace classes\Utility;

use Configuration;

/**
 * Class ChannelEngineProxy
 *
 * Provides communication with the ChannelEngine API using the HttpClient.
 */
class ChannelEngineProxy
{
    protected HttpClient $httpClient;

    /**
     * Constructor for ChannelEngineProxy.
     *
     */
    public function __construct()
    {
        $this->httpClient = HttpClient::getInstance();
    }

    /**
     * Sends products to the ChannelEngine API via a POST request.
     *
     * @param array $products An array of formatted products to be sent to ChannelEngine.
     */
    public function sendProducts(array $products): ?array
    {
        $url = 'https://' . Configuration::get('CHANNELENGINE_ACCOUNT_NAME') .
            '.channelengine.net/api/v2/products?apikey=' . Configuration::get('CHANNELENGINE_API_KEY');

        return $this->httpClient->post($url, $products);
    }

    public function validateCredentials($apiKey): bool
    {
        $url = 'https://logeecom-1-dev.channelengine.net/api/v2/settings?apikey=' . $apiKey;
        $headers = ['Accept: application/json'];
        $response = $this->httpClient->get($url, $headers);

        if ($response && $response['StatusCode'] == 200 && $response['Success'] === true) {
            return true;
        }

        return false;
    }
}