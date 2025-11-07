<?php

declare(strict_types=1);

namespace Tests\Controllers;

use PHPUnit\Framework\TestCase;
use App\Controllers\WeatherInfoController;
use App\Services\WeatherService;
use App\Services\GeoService;
use App\Services\DTO\WeatherData;
use App\Services\DTO\GeoData;

class WeatherInfoControllerTest extends TestCase
{
    private WeatherService $weatherService;
    private GeoService $geoService;
    private WeatherInfoController $controller;

    protected function setUp(): void
    {
        $this->weatherService = $this->createMock(WeatherService::class);
        $this->geoService = $this->createMock(GeoService::class);

        $this->controller = new WeatherInfoController(
            $this->weatherService,
            $this->geoService
        );
    }

    public function test_it_returns_weather_data_for_valid_city(): void
    {

        // Simulate GET parameter
        $_GET['city'] = 'London';

        // Mock GeoService to return GeoData for London
        $this->geoService->method('getGeoLocation')
            ->willReturn(new GeoData(
                city: 'London',
                lat: 51.5074,
                lon: -0.1278,
                country: 'GB'
            ));

        // Mock WeatherService to return WeatherData
        $this->weatherService->method('getWeatherByCoordinates')
            ->willReturn(new WeatherData(
                city: 'London',
                temperature: 18.5,
                description: 'Clear sky',
                icon: '04n',
                humidity: 77,
                windSpeed: 1.05,
                windDirection: 10,
                error: false,
                errorCode: null,
                errorMessage: null
            ));

        // Call controller
        $response = $this->controller->getWeather();

        // Assertions
        $this->assertIsArray($response);
        $this->assertArrayHasKey('error', $response);
        $this->assertFalse($response['error']);
        $this->assertEquals('London', $response['city']);
        $this->assertArrayHasKey('temperature', $response);
        $this->assertArrayHasKey('weatherDescription', $response);
        $this->assertArrayHasKey('wind_speed', $response);
        $this->assertArrayHasKey('wind_dir', $response);
        $this->assertArrayHasKey('humidity', $response);
    }

    public function test_it_returns_error_when_city_is_missing(): void
    {
        unset($_GET['city']);

        // Mock GeoService to simulate missing city
        $this->geoService->method('getGeoLocation')
            ->willReturn(new GeoData(
                error: true,
                errorCode: 400,
                errorMessage: 'City name is required.'
            ));

        $response = $this->controller->getWeather();

        $this->assertTrue($response['error']);
        $this->assertEquals(400, $response['errorCode']);
        $this->assertEquals('City name is required.', $response['errorMessage']);
    }

    public function test_it_returns_error_for_non_uk_city(): void
    {
        $_GET['city'] = 'Paris';

        // Mock GeoService to return a city outside of the UK
        $this->geoService->method('getGeoLocation')
            ->willReturn(new \App\Services\DTO\GeoData(
                city: null,
                lat: null,
                lon: null,
                country: null,
                error: true,
                errorCode: 404,
                errorMessage: "Location not found."
            ));

        $response = $this->controller->getWeather();
        $this->assertEquals(404, $response['errorCode']);
    
    }


    public function test_it_returns_error_when_geo_service_fails(): void
    {
        $_GET['city'] = 'Pluto';

        $this->geoService->method('getGeoLocation')
            ->willReturn(new GeoData(error: true, errorCode: 404, errorMessage: 'Location not found.'));

        $response = $this->controller->getWeather();

        $this->assertTrue($response['error']);
        $this->assertEquals(404, $response['errorCode']);
    }

    public function test_it_returns_error_when_weather_service_fails(): void
    {
        $_GET['city'] = 'London';

        $this->geoService->method('getGeoLocation')
            ->willReturn(new GeoData(city: 'London', lat: 51.5074, lon: -0.1278, country: 'GB'));

        $this->weatherService->method('getWeatherByCoordinates')
            ->willReturn(new WeatherData(error: true, errorCode: 500, errorMessage: 'Unexpected error'));

        $response = $this->controller->getWeather();

        $this->assertTrue($response['error']);
        $this->assertEquals(500, $response['errorCode']);
    }
}
