<?php

namespace App\Controllers;

use App\Services\DTO\WeatherData;
use WeatherApp\Core\Http\Request;
use App\Services\WeatherService;
use App\Services\GeoService;

/**
 * This controller handles requests to fetch weather information for a city. It uses GeoService to get locationv coordinates
 * and WeatherService to get weather data.
 */
class WeatherInfoController
{
    /**
     * @var WeatherService
     */
    private WeatherService $weatherService;

    /**
     * @var GeoService
     */
    private GeoService $geoService;

    /**
     * Inject WeatherService and GeoService instances via constructor so that the controller can use them.
     *
     * @param WeatherService $weatherService
     */
    public function __construct(WeatherService $weatherService, GeoService $geoService)
    {
        $this->weatherService = $weatherService;

        $this->geoService = $geoService;
    }

    /**
     * Gets weather data for a city.
     *
     * This method handles the request, gets city name from query string, fetches location coordinates using GeoService and
     * then fetches weather information using WeatherService.
     *
     * @return array Returns an array with weather data or error information
     */
    public function getWeather(): array
    {
        $request = Request::create();

        $city = trim($request->getQueryParams('city'));

        //Fetch geographical coordinates for the city
        $geoData = $this->geoService->getGeoLocation($city);


        if ($geoData->error) {
            return [
                'error' => true,
                'errorCode' => $geoData->errorCode,
                'errorMessage' => $geoData->errorMessage
            ];
        }

        $lat = (float)$geoData->lat;

        $lon = (float)$geoData->lon;

        /**
         * @var WeatherData
         *
         * Fetch weather data using location coordinates. City name is only being passed in method call as we need to compare this with the value
         * in API response in order to work around a known OpenWeather API limitation. Please refer to comments on line 60 in WeatherService class.
         */
        $weatherData = $this->weatherService->getWeatherByCoordinates($lat, $lon, $city);

        return [
            'city' => $weatherData->city,
            'temperature' => $weatherData->temperature,
            'weatherDescription' => $weatherData->description,
            'iconCode' => $weatherData->icon,
            'humidity' => $weatherData->humidity,
            'wind_speed' => $weatherData->windSpeed,
            'wind_dir' => $weatherData->windDirection,
            'error' => $weatherData->error,
            'errorCode' => $weatherData->errorCode,
            'errorMessage' => $weatherData->errorMessage,
        ];
    }
}
