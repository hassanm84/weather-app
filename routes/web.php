<?php

use App\Controllers\HomeController;
use App\Controllers\WeatherInfoController;

/**
 *  Mapping of HTTP endpoints to corresponding controllers
 *
 *  @return array list of route definitions.
 */

return [
   ['GET', '/', [HomeController::class, 'index']],
   ['GET', '/weather-info', [WeatherInfoController::class, 'getWeather']],
];
