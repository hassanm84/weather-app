<?php

namespace App\Helpers;

/**
 * UnitsHelper
 *
 * This helper class provides units for temperature and wind speed
 * based on a given units category.
 *
 * Example categories:
 * - "metric"   => Celsius and meters per second
 * - "imperial" => Fahrenheit and miles per hour
 * - "standard" => Kelvin and meters per second
 */
class UnitsHelper
{
    /**
     * Return appropriate units for temperature and wind speed based on configured units category.
     *
     * @param string $unitsCategory 'metric', 'imperial', or 'standard'
     * @return array
     */
    public static function getUnits(string $unitsCategory): array
    {
        $mapping = [
            'metric'   => ['temperature' => '°C', 'wind' => 'm/s'],
            'imperial' => ['temperature' => 'F', 'wind' => 'mph'],
            'standard' => ['temperature' => 'K',  'wind' => 'm/s'],
        ];

        return $mapping[$unitsCategory];
    }
}
