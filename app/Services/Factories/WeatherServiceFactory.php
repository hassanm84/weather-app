<?php

namespace App\Services\Factories;

use GuzzleHttp\Client;
use App\Services\Providers\WeatherServiceProvider;
use App\Services\WeatherService;

/**
 * WeatherServiceFactory
 *
 * This factory class is responsible for creating instances of WeatherService.
 * It sets up the WeatherServiceProvider with required configuration and Guzzle client,
 * and then passes it to the WeatherService.
 */
class WeatherServiceFactory
{
    /**
     * @var array Configuration array for OpenWeather API and application settings
     */
    private array $config;

    /**
     * Constructor
     *
     * @param array $config Configuration array containing API keys, endpoints, and units
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Create a WeatherService instance
     *
     * This method sets up the WeatherServiceProvider with a Guzzle client
     * and configuration values, and returns a fully initialized WeatherService.
     *
     * @param Client $client Guzzle HTTP client used for API requests
     * @return WeatherService Returns a new WeatherService instance
     */
    public function create(Client $client): WeatherService
    {
        $weatherProvider = new WeatherServiceProvider(
            client: $client,
            apiKey: $this->config['openweather']['api_key'],
            units: $this->config['openweather']['units'],
            countryCode: $this->config['openweather']['country_code'],
            apiEndpoint: $this->config['openweather']['endpoint_weather']
        );

        return new WeatherService($weatherProvider, $this->config);
    }
}
