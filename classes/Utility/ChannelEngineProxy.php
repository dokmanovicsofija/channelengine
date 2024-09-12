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
    /**
     * @var HttpClient The HTTP client used for making API requests.
     */
    protected $httpClient;

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

    /**
     * Validates the provided API key by sending a request to the ChannelEngine API.
     *
     * This method constructs the URL with the provided API key and makes an HTTP GET request
     * to the ChannelEngine API. It checks the response for a successful status code (200)
     * and ensures that the 'Success' field is true.
     *
     * @param string $apiKey The API key to validate.
     * @return bool Returns true if the API key is valid, false otherwise.
     */
    public function validateCredentials(string $apiKey): bool
    {
        $accountName = Configuration::get('CHANNELENGINE_ACCOUNT_NAME');
        $baseUrl = 'https://' . $accountName . '.channelengine.net/api/v2/settings?apikey=' . $apiKey;
        $headers = ['Accept: application/json'];
        $response = $this->httpClient->get($baseUrl, $headers);

        return $response && $response['StatusCode'] == 200 && $response['Success'];
    }
}