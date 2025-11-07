<?php

return [
    'name' => 'Weather Information Application',
    'routes' => ['web' => BASE_PATH . '/routes/web.php',],
    'page_titles' => [
        'home_page' => 'Weather Information App - Home'
    ],
    'api_error_messages' => [
        'city_name_required' => 'City name is required.',
        'unexpected_error' => 'Internal server error at provider\'s end. Failed to fetch data.',
        'unauthorised_request' => 'Unauthorised request.',
        'request_quota_exceeded' => 'Request quota exceeded.',
        'invalid_city' => 'Weather data for requested city is not available. N.B. You can only request info for cities in the UK.',
    ],
    'api_error_status' => [
        'unauthorised_request' => 401,
        'bad_request' => 400,
        'request_quota_exceeded' => 429,
        'unexpected_error' => 500,
        'resource_not_found' => 404,
        'unprocessable_request' => 422,
    ],
    'http_error_messages' => [
        404 => 'Page not found.',
        405 => 'Method not allowed.',
        500 => 'Internal Server Error',
    ]
];
