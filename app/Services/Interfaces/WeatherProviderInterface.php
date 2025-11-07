<?php

namespace App\Services\Interfaces;

interface WeatherProviderInterface
{
    /**
     * Get weather by geo location coordinates
     *
     * @param float $lat
     * @param float $lon
     * @return array
     */
    public function getCurrentWeatherByCoordinates(float $lat, float $lon): array;
}
