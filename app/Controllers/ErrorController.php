<?php

namespace App\Controllers;

use App\Helpers\ErrorRenderer;

/**
 * ErrorController
 *
 * This controller handles HTTP errors and returns proper error pages. It uses ErrorRenderer helper
 * to generate the HTML for error responses.
 */
class ErrorController
{
    /**
     * @var ErrorRenderer
     */
    private ErrorRenderer $renderer;

    /**
     * Constructor
     *
     * Inject the ErrorRenderer so controller can render error pages.
     *
     * @param ErrorRenderer $renderer Service to render error pages
     */
    public function __construct(ErrorRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function handle(int $code, ?string $message = null): string
    {
        return $this->renderer->render($code, $message);
    }

    public function notFound(): string
    {
        return $this->handle(404, "Page not found.");
    }

    public function methodNotAllowed(): string
    {
        return $this->handle(405, "Method not allowed.");
    }
}
