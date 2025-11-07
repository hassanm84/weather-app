<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use App\Services\Factories\WeatherServiceFactory;
use App\Services\Factories\GeoServiceFactory;
use App\Factories\HttpClientFactory;
use WeatherApp\Core\Http\Kernel;
use WeatherApp\Core\Http\Request;
use WeatherApp\Core\View;
use App\Helpers\ErrorRenderer;
use App\Controllers\ErrorController;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

// Load environment variables
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Load configuration
$appConfig = require BASE_PATH . '/config/app.php';
$servicesConfig = require BASE_PATH . '/config/services.php';

// Merge configs for convenience
$config = array_merge($appConfig, $servicesConfig);

// Create guzzle clients
$weatherClient = HttpClientFactory::createWeatherClient($config);
$geoClient = HttpClientFactory::createGeoClient($config);

// Create WeatherService via factory
$factory = new WeatherServiceFactory($config);
$weatherService = $factory->create($weatherClient);

// Create GeoService via factory
$factory = new GeoServiceFactory($config);
$geoService = $factory->create($geoClient );

$view = new View(BASE_PATH . '/app/Views');

$errorRenderer = new ErrorRenderer(BASE_PATH . '/app/Views/errors.php');

// Prerequisites mixed array for new Kernel instance
$prereqs = [
    'core' => [
        'config' => $appConfig,
        'view' => $view,
    ],
    'controllers' => [
        ErrorController::class => new ErrorController($errorRenderer),
    ],
    'services' => [
        \App\Services\WeatherService::class => $weatherService,
        \App\Services\GeoService::class => $geoService,
    ],
    'parameters' => [
        'unitsCategory' => $servicesConfig['openweather']['units'], 
        'pageTitle' => $appConfig['page_titles']['home_page'],
    ],
];


// Instantiate Bootstrap Kernel 
$kernel = new Kernel($prereqs, $appConfig['routes']['web']);

// Handle incoming request
$request = Request::create();

$response = $kernel->handle($request);

$response->send();

