<?php

namespace App\Services\Providers;

use App\Services\Interfaces\WeatherProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * WeatherServiceProvider
 *
 * This class fetches weather information from the OpenWeather API using geographic coordinates.
 * It implements the WeatherProviderInterface.
 *
 * The provider can fetch weather by latitude and longitude.
 */
class WeatherServiceProvider implements WeatherProviderInterface
{
    /**
     * @var Client Guzzle HTTP client used to make API requests
     */
    private Client $client;

    /**
     * @var string API key for OpenWeather
     */
    private string $apiKey;
    /**
     * @var string API endpoint for weather data
     */
    private string $apiEndpoint;

    /**
     * @var string Units category (metric, imperial, standard)
     */
    private string $units;

    /**
     * @var string|null Optional country code filter
     */
    private ?string $countryCode;

    /**
     * @var string HTTP GET method constant
     */

    private const HTTP_GET_METHOD = 'GET';


    /**
     * Constructor
     *
     * @param Client $client Guzzle HTTP client for API requests
     * @param string $apiKey OpenWeather API key
     * @param string $apiEndpoint Weather API endpoint
     * @param string $units Units category ('metric', 'imperial', 'standard')
     * @param string $countryCode Country code filter (optional)
     */
    public function __construct(
        Client $client,
        string $apiKey,
        string $apiEndpoint,
        string $units,
        string $countryCode
    ) {
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->apiEndpoint = $apiEndpoint;
        $this->units = $units;
        $this->countryCode = $countryCode;
    }

    /**
     * Get current weather using latitude and longitude coordinates.
     *
     * This method makes a GET request to the OpenWeather API using the provided
     * coordinates. It returns the API response as an array.
     *
     * @param float $lat Latitude of the location
     * @param float $lon Longitude of the location
     * @return array Returns API response as associative array. Returns empty array if API fails.
     */
    public function getCurrentWeatherByCoordinates(float $lat, float $lon): array
    {
        $query = [
            'lat' => $lat,
            'lon' => $lon,
            'appid' => $this->apiKey,
            'units' => $this->units,
        ];

        try {
            $response = $this->client->request(self::HTTP_GET_METHOD, $this->apiEndpoint, [
                'query' => $query
            ]);

            return json_decode((string)$response->getBody(), true);
        } catch (GuzzleException $e) {
            // Exception logging is presumed to be out of scope for this assessment.

            return [];
        }
    }
}
