<?php

namespace App\Services;

use App\Services\DTO\GeoData;
use App\Services\Providers\GeoServiceProvider;

/**
 * GeoService
 *
 * This service class is responsible for getting the geographical coordinates of a city.
 * It uses the GeoServiceProvider to fetch data from the OpenWeather Geo API.
 */
class GeoService
{
    /**
     * @var GeoServiceProvider The provider that handles API requests for geolocation
     */
    private GeoServiceProvider $provider;

    /**
     * @var array Configuration array, including API error codes and messages
     */
    private array $config;

    /**
     * Constructor
     *
     * @param GeoServiceProvider $provider The provider to fetch geolocation data
     * @param array $config Optional configuration array
     */
    public function __construct(GeoServiceProvider $provider, array $config = [])
    {
        $this->provider = $provider;
        $this->config = $config;
    }

    /**
     * Get geolocation information for a given city.
     *
     * This method checks if the city name is empty and returns an error if so.
     * Then it calls the GeoServiceProvider to fetch coordinates.
     * If the provider returns empty data, it also returns an error.
     *
     * @param string $city Name of the city to fetch coordinates for
     * @return GeoData Returns a GeoData object containing latitude, longitude, city, and country.
     *                 If any error occurs, the error properties will be set.
     */
    public function getGeoLocation(string $city): GeoData
    {
        $city = trim($city);

        if (empty($city)) {
            return new GeoData(
                error: true,
                errorCode: $this->config['api_error_status']['bad_request'] ?? 400,
                errorMessage: $this->config['api_error_messages']['city_name_required'] ?? 'City name is required.'
            );
        }

        $data = $this->provider->getCoordinates($city);

        if (!is_array($data) && $data == false) {
            return new GeoData(
                error: true,
                errorCode: $this->config['api_error_status']['unexpected_error'],
                errorMessage: $this->config['api_error_messages']['unexpected_error']
            );
        }

        if (empty($data)) {
            return new GeoData(
                error: true,
                errorCode: $this->config['api_error_status']['resource_not_found'],
                errorMessage: $this->config['api_error_messages']['invalid_city']
            );
        }

        // Handle API responses
        if ((int)($data['cod'] ?? 0) !== 200 && !isset($data[0]['lat'])) {
            return new GeoData(
                error: true,
                errorCode: (int)($data['cod'] ?? $this->config['api_error_status']['unexpected_error']),
                errorMessage: $data['message'] ?? ($this->config['api_error_messages']['unexpected_error'])
            );
        }



        return new GeoData(
            city: $data[0]['name'],
            lat: $data[0]['lat'],
            lon: $data[0]['lon'],
            country: $this->config['openweather']['country_code']
        );
    }
}
