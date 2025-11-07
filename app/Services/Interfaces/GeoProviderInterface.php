<?php

namespace App\Services\Interfaces;

interface GeoProviderInterface
{
    /**
     * Fetch geographical coordinates (latitude, longitude) for a given city name.
     *
     * @param string $city The city name to geocode.
     * @return array
     */
    public function getCoordinates(string $city): ?array;
}
