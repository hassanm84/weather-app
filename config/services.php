<?php
/**
 *  Config for third party services
 * 
 * 
 */

return [
    'openweather' => [
        'base_uri_weather' => $_ENV['OPENWEATHER_WEATHER_API_BASE_URI'],
        'endpoint_weather' => $_ENV['OPENWEATHER_WEATHER_API_ENDPPOINT'],
        'base_uri_geo' => $_ENV['OPENWEATHER_GEO_API_BASE_URI'],
        'endpoint_geo' => $_ENV['OPENWEATHER_GEO_API_ENDPOINT'],
        'api_key' => $_ENV['OPENWEATHER_API_KEY'],
        'units' => $_ENV['OPENWEATHER_UNITS'],
        'country_code' => $_ENV['OPENWEATHER_COUNTRY_CODE'],  
        'location_limit' =>  $_ENV['OPENWEATHER_LOC_LIMIT']      
    ],
    'guzzle' => [
        'connect_timeout' => (float)$_ENV['GUZZLE_CONNECT_TIMEOUT'],
        'http_errors' => filter_var($_ENV['GUZZLE_HTTP_ERRORS'], FILTER_VALIDATE_BOOLEAN)
    ]
];
