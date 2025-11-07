<?php

namespace App\Services\DTO;

/**
 * GeoData
 *
 * This Data Transfer Object (DTO) stores geographical information for a city. It includes latitude,
 * longitude, country, and optional error information.
 */
class GeoData
{
    public ?string $city = null;
    public ?float $lat = null;
    public ?float $lon = null;
    public ?string $country = null;

    // Error properties
    public bool $error = false;
    public ?int $errorCode = null;
    public ?string $errorMessage = null;

    public function __construct(
        ?string $city = null,
        ?float $lat = null,
        ?float $lon = null,
        ?string $country = null,
        bool $error = false,
        ?int $errorCode = null,
        ?string $errorMessage = null
    ) {
        $this->city = $city;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->country = $country;

        $this->error = $error;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }
}
