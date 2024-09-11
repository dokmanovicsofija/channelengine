<?php

namespace classes\Utility;

/**
 * Class HttpClient
 *
 * This class provides simple HTTP client functionality to make GET and POST requests using cURL.
 *  Implements the Singleton design pattern to ensure only one instance exists.
 */
class HttpClient extends Singleton
{
    /**
     * Sends an HTTP GET request to the specified URL with optional headers.
     *
     * @param string $url The URL to send the GET request to.
     * @param array $headers Optional. An array of headers to include with the request.
     * @return array|null The JSON-decoded response as an associative array, or null if the response cannot be decoded.
     */
    public function get(string $url, array $headers = []): ?array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Sends an HTTP POST request to the specified URL with optional headers and data.
     *
     * @param string $url The URL to send the POST request to.
     * @param array $data The data to be sent in the request body, typically as an associative array.
     * @param array $headers Optional. An array of headers to include with the request.
     * @return array|null The JSON-decoded response as an associative array, or null if the response cannot be decoded.
     */
    public function post(string $url, array $data, array $headers = []): ?array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge(['Content-Type: application/json'], $headers));

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}