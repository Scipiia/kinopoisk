<?php

namespace App\Kernel\Router;

class Route
{

    public function __construct(
        private string $uri,
        private $action,
        private array $middlewares,
        private string $method,
    )
    {}

    public static function get($uri, $action, $middlewares = []): static {
        return new static($uri, $action, $middlewares, method: "GET");
    }

    public static function post($uri, $action, $middlewares = []): static {
        return new static($uri, $action, $middlewares, method: "POST");
    }


    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getAction(): mixed
    {
        return $this->action;
    }

    public function hasMiddlewares(): bool {
        return !empty($this->middlewares);
    }

    public function getMiddlewares(): array {
        return $this->middlewares;
    }
}
