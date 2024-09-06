<?php

namespace classes\Utility;

/**
 * Class ChannelEngineProxy
 *
 * Provides communication with the ChannelEngine API using the HttpClient.
 */
class ChannelEngineProxy
{
    private HttpClient $httpClient;
    private string $apiUrl;
    private string $apiKey;

    /**
     * Constructor for ChannelEngineProxy.
     *
     * @param string $apiUrl The base URL of the ChannelEngine API.
     * @param string $apiKey The API key for authentication.
     */
    public function __construct(string $apiUrl, string $apiKey)
    {
        $this->httpClient = new HttpClient();
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
    }

    /**
     * Sends products to the ChannelEngine API via a POST request.
     *
     * @param array $products An array of formatted products to be sent to ChannelEngine.
     * @return mixed The response from the API.
     */
    public function sendProducts(array $products)
    {
        $url = $this->apiUrl . '/v2/products?apikey=' . $this->apiKey;
        return $this->httpClient->post($url, $products);
    }

    /**
     * Retrieves settings from the ChannelEngine API via a GET request.
     *
     * @return mixed The response from the API.
     */
    public function getSettings()
    {
        $url = $this->apiUrl . '/v2/settings?apikey=' . $this->apiKey;
        return $this->httpClient->get($url);
    }
}