<?php

namespace WeatherApp\Core;

/**
 * Interface for rendering views.
 */
interface ViewInterface
{
    /**
     * Render a view file with optional data.
     *
     * @param string $viewPath Relative path to the view file
     * @param array $data Optional data for the view
     * @return string Rendered output
     */
    public function render(string $viewPath, array $data = []): string;
}
