<?php

namespace App\Services\Providers;

use App\Services\Interfaces\GeoProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * GeoServiceProvider
 *
 * This class provides geographical coordinates (latitude and longitude) for a given city.
 * It uses the OpenWeather GeoCoding API to fetch the coordinates.
 *
 * Implements GeoProviderInterface.
 */
class GeoServiceProvider implements GeoProviderInterface
{
    /**
     * @var Client Guzzle HTTP client for making API requests
     */
    private Client $client;


    /**
     * @var string API key for OpenWeather requests
     */
    private string $apiKey;

    /**
     * @var string Endpoint for the Geo API (e.g., 'direct')
     */
    private string $apiEndpoint;

    /**
     * @var string Country code used to filter results (e.g., 'GB')
     */
    private string $countryCode;

    /**
     * @var string Units category (e.g., 'metric', 'imperial', 'standard')
     */
    private string $units;

    /**
     * @var int Defines the number of locations with the same name that will be returned.
     */
    private int $locationLimit;

    /**
     * @var string HTTP method for requests
     */
    private const HTTP_GET_METHOD = 'GET';

    /**
     * Constructor
     *
     * @param Client $client Guzzle HTTP client used for API requests
     * @param string $apiKey API key for OpenWeather
     * @param string $apiEndpoint Geo API endpoint
     * @param string $countryCode Country code to filter city results
     * @param string $units Units category
     * @param int $locationLimit Specifies number of locations with the same name that should be returned
     */
    public function __construct(Client $client, string $apiKey, string $apiEndpoint, string $countryCode, string $units, int $locationLimit)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->units = $units;
        $this->apiEndpoint = $apiEndpoint;
        $this->countryCode = $countryCode;
        $this->locationLimit = $locationLimit;
    }

    /**
     * Get geo-location coordinates (latitude and longitude) for a given city.
     *
     * This method makes a request to the OpenWeather Geo API.
     * If the city is found, it returns an array with 'name', 'lat', and 'lon'.
     * If no results are found or there is an API error, it returns an empty array.
     *
     * @param string $city City name to look up
     * @return array Returns an array with keys 'name', 'lat', 'lon' or empty array if not found
     */
    public function getCoordinates(string $city): ?array
    {
        $query = [
            'q' => "{$city},{$this->countryCode}",
            'limit' => $this->locationLimit,
            'appid' => $this->apiKey,
        ];

        try {
            $response = $this->client->request(self::HTTP_GET_METHOD, $this->apiEndpoint, ['query' => $query]);

             return json_decode((string)$response->getBody(), true);

        } catch (GuzzleException $e) {
            // Exception logging is presumed to be out of scope for this assessment.

            return [];
        }
    }
}
