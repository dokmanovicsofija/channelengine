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
     * @var string
     */
    private const BASE_URL = 'https://{accountName}.channelengine.net';

    /**
     * @var string
     */
    private const API_VERSION = '/api/v2';

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
     * Generates the base URL with account name and API version.
     *
     * @param string $accountName The account name for ChannelEngine.
     * @return string The constructed base URL.
     */
    private function generateBaseUrl(string $accountName): string
    {
        $baseUrl = str_replace('{accountName}', $accountName, self::BASE_URL);

        return rtrim($baseUrl, '/') . '/' . ltrim(self::API_VERSION, '/');
    }

    /**
     * Sends products to the ChannelEngine API via a POST request.
     *
     * @param string $accountName The account name for ChannelEngine.
     * @param string $apiKey The API key for ChannelEngine.
     * @param array $products An array of formatted products to be sent to ChannelEngine.
     * @return array|null The response from ChannelEngine.
     */
    public function sendProducts(string $accountName, string $apiKey, array $products): ?array
    {
        $url = $this->generateBaseUrl($accountName) . '/products?apikey=' . $apiKey;

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
    public function validateCredentials(string $accountName, string $apiKey): bool
    {
        $url = $this->generateBaseUrl($accountName) . '/settings?apikey=' . $apiKey;
        $headers = ['Accept: application/json'];
        $response = $this->httpClient->get($url, $headers);

        return $response && $response['StatusCode'] == 200 && $response['Success'];
    }
}