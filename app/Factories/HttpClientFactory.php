<?php

namespace App\Factories;

use GuzzleHttp\Client;

class HttpClientFactory
{
    public static function createWeatherClient(array $config): Client
    {
        return new Client([
            'base_uri' => $config['openweather']['base_uri_weather'],
            'timeout' => $config['guzzle']['connect_timeout'],
            'http_errors' => $config['guzzle']['http_errors'],
        ]);
    }

    public static function createGeoClient(array $config): Client
    {
        return new Client([
            'base_uri' => $config['openweather']['base_uri_geo'],
            'timeout' => $config['guzzle']['connect_timeout'],
            'http_errors' => $config['guzzle']['http_errors'],
        ]);
    }
}
