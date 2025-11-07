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

        if (empty($data)) {
            return new GeoData(
                error: true,
                errorCode: $this->config['api_error_status']['not_found'] ?? 404,
                errorMessage: $this->config['api_error_messages']['location_not_found'] ?? 'Location not found.'
            );
        }

        return new GeoData(
            city: $data['name'],
            lat: $data['lat'],
            lon: $data['lon'],
            country: $this->config['openweather']['country_code']
        );
    }
}
