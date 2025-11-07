<?php

namespace WeatherApp\Core\Http;

/**
 * Class Request
 *
 * Singleton class representing an HTTP request.
 *
 * @package WeatherApp\Core\Http
 */
class Request
{
    /**
     * @var self|null singleton instance that can either be a Request object or null
     */
    private static $instance = null;


    /**
     * Constructor
     *
     * @param array $server $_SERVER superglobal
     * @param array $get $_GET superglobal
     * @param array $post $_POST superglobal
     * @param array $files $_FILES superglobal
     * @param array $cookie $_COOKIE superglobal
     * @param array $env $_ENV superglobal
     */
    private function __construct(
        private array $server,
        private array $get,
        private array $post,
        private array $files,
        private array $cookie,
        private array $env,
    ) {
    }


    /**
     * Create the singleton instance of Request.
     *
     * @return static
     */
    public static function create(): static
    {
        if (null === static::$instance) {
            static::$instance = new static(
                $_SERVER,
                $_GET,
                $_POST,
                $_FILES,
                $_COOKIE,
                $_ENV,
            );
        }

        return static::$instance;
    }

    /**
     * Determine the HTTP request method
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    /**
     * Get the request URI
     *
     * @return string
     */
    public function getUri(): string
    {
        $uri = parse_url($this->server['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';
        return $uri;
    }

    /**
     * Retrieve value for a post variable
     *
     * @param string $name
     * @return string
     */
    public function getPostParams(string $name): string
    {
        return $this->post[$name];
    }


    /**
     * Retrieve get query parameter
     *
     * @param string $name
     * @return string
     */
    public function getQueryParams(string $name): string
    {
        return $this->get[$name];
    }
}
