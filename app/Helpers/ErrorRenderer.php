<?php

namespace App\Helpers;

class ErrorRenderer
{
    private string $viewFile;

    public function __construct(string $viewFile)
    {
        $this->viewFile = $viewFile;
    }

    /**
     * Render the error view with given code and message
     */
    public function render(int $code, ?string $message = null): string
    {
        $errorCode = $code;
        $errorMessage = $message ?? "An unexpected error occurred.";

        if (file_exists($this->viewFile)) {
            ob_start();
            require $this->viewFile;
            return ob_get_clean();
        }

        // Fallback if the view file is missing
        return "<h1>{$errorCode} Error</h1><p>{$errorMessage}</p><a href='/'>Return Home</a>";
    }
}
