<?php

namespace App\Services;

use App\Services\DTO\WeatherData;
use App\Services\Interfaces\WeatherProviderInterface;

/**
 * WeatherService
 *
 * This service is responsible for getting weather information for a location.
 * It uses a WeatherProviderInterface to fetch data from OpenWeather API.
 *
 * It can fetch weather using geo-location coordinates and checks
 * that the returned city matches the requested city to avoid wrong data. This check
 * was necessitated due to a limitation of the external API.
 */
class WeatherService
{
    /**
     * @var WeatherProviderInterface The provider used to make API requests
     */
    private WeatherProviderInterface $provider;
    /**
     * @var array Configuration array with error codes and messages
     */
    private array $config;

    /**
     * Constructor
     *
     * @param WeatherProviderInterface $provider The provider to fetch weather data
     * @param array $config Configuration array for error codes/messages
     */
    public function __construct(WeatherProviderInterface $provider, array $config)
    {
        $this->provider = $provider;
        $this->config = $config;
    }

    /**
     * Get weather information by geographic coordinates.
     *
     * @param float $lat Latitude of the location
     * @param float $lon Longitude of the location
     * @param string $requestedCity Name of the city requested by user
     * @return WeatherData Returns a WeatherData object
     */
    public function getWeatherByCoordinates(float $lat, float $lon, string $requestedCity): WeatherData
    {

        $data = $this->provider->getCurrentWeatherByCoordinates($lat, $lon);

        return $this->validateWeatherData($data, $requestedCity);
    }


    /**
     * Validate  API response, maps response data fields to WeatherData DTO.
     *
     * @param array|null $data The API response data
     * @param string $requestedCity The city name requested by the user
     * @return WeatherData Returns a WeatherData object or error if validation fails
     */
    private function validateWeatherData(array $data, string $requestedCity): WeatherData
    {
        if (empty($data)) {
            return new WeatherData(
                error: true,
                errorCode: $this->config['api_error_status']['unexpected_error'] ?? 500,
                errorMessage: $this->config['api_error_messages']['unexpected_error']
            );
        }

        // Handle API responses
        if ((int)($data['cod'] ?? 0) !== 200 && !isset($data['lat'])) {
            return new WeatherData(
                error: true,
                errorCode: (int)($data['cod'] ?? 500),
                errorMessage: $data['message'] ?? ($this->config['api_error_messages']['unexpected_error'])
            );
        }

        /**
         *  N.B. OpenWeather's Geo API from  performs a fuzzy lookup of place names rather than strict city or town name. This
         *  means that  a query like "Paris,GB" can return coordinates for a similarly named place (e.g. a road or a pub name GB)
         *  rather than the intended city. The subsequent call to weather endpoint however does return name of the town/city for the location coordinates.
         *  To prevent incorrect weather data being returned for unintended locations, in the below segment, requested city name is compared
         *  against the city name returned by the API response. A mismatch results in an error response.
         */

        if (strcasecmp(trim($data['name']), trim($requestedCity)) !== 0) {
            return new WeatherData(
                error: true,
                errorCode: (int)$this->config['api_error_status']['unprocessable_request'],
                errorMessage: $this->config['api_error_messages']['invalid_city']
            );
        }


        return new WeatherData(
            city: $data['name'] ?? null,
            temperature: $data['current']['temp'] ?? $data['main']['temp'] ?? null,
            description: isset($data['current']['weather'][0]['description'])
                ? ucfirst($data['current']['weather'][0]['description'])
                : (isset($data['weather'][0]['description']) ? ucfirst($data['weather'][0]['description']) : null),
            icon: $data['current']['weather'][0]['icon'] ?? $data['weather'][0]['icon'] ?? null,
            humidity: $data['current']['humidity'] ?? $data['main']['humidity'] ?? null,
            windSpeed: $data['current']['wind_speed'] ?? $data['wind']['speed'] ?? null,
            windDirection: $data['current']['wind_deg'] ?? $data['wind']['deg'] ?? null
        );
    }
}
