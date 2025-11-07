<?php

namespace WeatherApp\Core\Http;

use FastRoute\RouteCollector;
use ReflectionClass;
use ReflectionParameter;
use ReflectionNamedType;
use RuntimeException;
use App\Controllers\ErrorController;

use function FastRoute\simpleDispatcher;

/**
 * Class Kernel
 *
 * Handles incoming HTTP requests, resolves controllers, and returns responses.
 */
class Kernel
{
    /**
     * @var array Grouped prereqs  container (core, controllers, prereqs , etc.)
     */
    private array $prereqs;

    /**
     * @var string Path to routes file (web.php)
     */
    private string $routesPath;

    /**
     * Kernel constructor.
     *
     * @param array  $prereqs     Grouped service container
     * @param string $routesPath  Path to routes file
     */
    public function __construct(array $prereqs, string $routesPath)
    {
        $this->prereqs  = $prereqs;
        $this->routesPath = $routesPath;
    }

    /**
     * Handle an incoming HTTP request.
     */
    public function handle(Request $request): Response
    {
        $dispatcher = $this->createDispatcher();
        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getUri());

        try {
            switch ($routeInfo[0]) {
                case \FastRoute\Dispatcher::NOT_FOUND:
                    return $this->handleError(404);

                case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                    return $this->handleError(405);

                case \FastRoute\Dispatcher::FOUND:
                    [$controllerClass, $method] = $routeInfo[1];
                    $vars = $routeInfo[2] ?? [];
                    return $this->dispatchController($controllerClass, $method, $vars);

                default:
                    return $this->handleError(500);
            }
        } catch (\Throwable $e) {
            return $this->handleError(500, $e->getMessage());
        }
    }

    /**
     * Dispatch a controller method and return a Response.
     */
    private function dispatchController(string $controllerClass, string $method, array $vars = []): Response
    {
        $controller = $this->resolveController($controllerClass);
        $content = call_user_func_array([$controller, $method], $vars);

        return $this->makeResponse($content);
    }

    /**
     * Resolve a controller using constructor injection.
     */
    private function resolveController(string $controllerClass)
    {
        $refClass = new ReflectionClass($controllerClass);
        $constructor = $refClass->getConstructor();

        if (!$constructor) {
            return new $controllerClass();
        }

        $dependencies = [];
        foreach ($constructor->getParameters() as $param) {
            $dependencies[] = $this->resolveDependency($param);
        }

        return $refClass->newInstanceArgs($dependencies);
    }

    /**
     * Resolve a single dependency from grouped prereqs .
     */
    private function resolveDependency(ReflectionParameter $param)
    {
        $type = $param->getType();
        $typeName = $type instanceof ReflectionNamedType ? $type->getName() : null;
        $name = $param->getName();

        // Loop through all service groups
        foreach ($this->prereqs as $group) {
            if ($typeName && isset($group[$typeName])) {
                return $group[$typeName];
            }

            if (isset($group[$name])) {
                return $group[$name];
            }
        }

        if ($param->isDefaultValueAvailable()) {
            return $param->getDefaultValue();
        }

        throw new RuntimeException(
            "Cannot resolve dependency '{$name}' for " .
                $param->getDeclaringClass()->getName()
        );
    }


    /**
     * Create the FastRoute dispatcher.
     */
    private function createDispatcher()
    {
        return simpleDispatcher(function (RouteCollector $routeCollector) {
            $routes = include $this->routesPath;
            foreach ($routes as $route) {
                $routeCollector->addRoute(...$route);
            }
        });
    }

    /**
     * Handle HTTP errors using the ErrorController.
     */
    protected function handleError(int $code, ?string $message = null): Response
    {
        /** @var ErrorController $controller */
        $controller = $this->prereqs['controllers'][ErrorController::class];

        // Pull default error messages from config if not provided
        $config = $this->prereqs['core']['config'] ?? [];
        $message ??= $config['app_error_messages'][$code] ?? 'An unexpected error occurred.';

        $content = $controller->handle($code, $message);
        return new Response($content, $code);
    }

    /**
     * Convert any content to a Response.
     */
    private function makeResponse($content, int $status = 200): Response
    {
        if (is_array($content)) {
            return new Response(json_encode($content), $status, ['Content-Type' => 'application/json']);
        }

        if (is_string($content)) {
            return new Response($content, $status, ['Content-Type' => 'text/html']);
        }

        if ($content instanceof Response) {
            return $content;
        }

        return new Response('', $status);
    }
}
