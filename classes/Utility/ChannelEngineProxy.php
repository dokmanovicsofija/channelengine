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
        $this->httpClient = new HttpClient();
    }

    /**
     * Sends products to the ChannelEngine API via a POST request.
     *
     * @param array $products An array of formatted products to be sent to ChannelEngine.
     */
    public function sendProducts(array $products): ?array
    {
        $url = 'https://' . Configuration::get('CHANNELENGINE_ACCOUNT_NAME') . '.channelengine.net/api/v2/products?apikey=' . Configuration::get('CHANNELENGINE_API_KEY');

        return $this->httpClient->post($url, $products);
    }

    /**
     * Retrieves settings from the ChannelEngine API via a GET request.
     *
     */
    public function getSettings(): ?array
    {
        $apiUrl = Configuration::get('CHANNELENGINE_API_URL');
        $apiKey = Configuration::get('CHANNELENGINE_API_KEY');

        $url = $apiUrl . '/v2/settings?apikey=' . $apiKey;
        return $this->httpClient->get($url);
    }
}