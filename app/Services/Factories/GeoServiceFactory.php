<?php

namespace App\Services\Factories;

use GuzzleHttp\Client;
use App\Services\Providers\GeoServiceProvider;
use App\Services\GeoService;

/**
 * GeoServiceFactory
 *
 * This factory class is responsible for creating instances of GeoService.
 * It sets up the GeoServiceProvider with required configuration and Guzzle client,
 * then passes it to the GeoService.
 */
class GeoServiceFactory
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
     * Create a GeoService instance
     *
     * This method sets up the GeoServiceProvider with a Guzzle client configuration values,
     * and returns a fully initialized GeoService.
     *
     * @param Client $client Guzzle HTTP client used for API requests
     * @return GeoService Returns a new GeoService instance
     */
    public function create(Client $client): GeoService
    {
        $geoProvider = new GeoServiceProvider(
            client: $client,
            apiKey: $this->config['openweather']['api_key'],
            units: $this->config['openweather']['units'],
            countryCode: $this->config['openweather']['country_code'],
            apiEndpoint: $this->config['openweather']['endpoint_geo'],
            locationLimit: $this->config['openweather']['location_limit']
        );

        return new GeoService($geoProvider, $this->config);
    }
}
