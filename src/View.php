<?php

namespace WeatherApp\Core;

use RuntimeException;

/**
 * Simple PHP template renderer.
 */
class View implements ViewInterface
{
    private string $viewsBasePath;

    /**
     * View constructor.
     *
     * @param string $viewsBasePath Base directory for views
     */
    public function __construct(string $viewsBasePath)
    {
        $this->viewsBasePath = rtrim($viewsBasePath, '/');
    }

    /**
     * Render a PHP view file with optional data.
     *
     * @param string $viewPath Relative path to the view file
     * @param array $data Optional data for the view
     * @return string
     * @throws RuntimeException if view file not found
     */
    public function render(string $viewPath, array $data = []): string
    {
        $fullPath = $this->viewsBasePath . '/' . ltrim($viewPath, '/');

        if (!file_exists($fullPath)) {
            throw new RuntimeException("View file '{$viewPath}' not found at '{$fullPath}'");
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $fullPath;
        return ob_get_clean();
    }
}
