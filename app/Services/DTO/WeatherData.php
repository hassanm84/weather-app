<?php

namespace App\Services\DTO;

/**
 * WeatherData
 *
 * This Data Transfer Object (DTO) stores weather information for a city. It includes temperature,
 * description, icon code, humidity, wind speed/direction, and optional error information for scenarion if
 * something goes wrong.
 */
class WeatherData
{
    //Weather fields
    public ?string $city = null;
    public ?float $temperature = null;
    public ?string $description = null;
    public ?string $icon = null;
    public ?int $humidity = null;
    public ?float $windSpeed = null;
    public ?int $windDirection = null;

    //Error fields
    public bool $error = false;
    public ?int $errorCode = null;
    public ?string $errorMessage = null;

    /**
     * Constructor
     *
     * @param string|null $city
     * @param float|null $temperature
     * @param string|null $description
     * @param string|null $icon
     * @param int|null $humidity
     * @param float|null $windSpeed
     * @param int|null $windDirection
     * @param bool $error
     * @param int|null $errorCode
     * @param string|null $errorMessage
     */
    public function __construct(
        ?string $city = null,
        ?float $temperature = null,
        ?string $description = null,
        ?string $icon = null,
        ?int $humidity = null,
        ?float $windSpeed = null,
        ?int $windDirection = null,
        bool $error = false,
        ?int $errorCode = null,
        ?string $errorMessage = null
    ) {
        $this->city = $city;
        $this->temperature = $temperature;
        $this->description = $description;
        $this->icon = $icon;
        $this->humidity = $humidity;
        $this->windSpeed = $windSpeed;
        $this->windDirection = $windDirection;

        $this->error = $error;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }

    /**
     * Check if this instance represents an error.
     *
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->error;
    }
}
