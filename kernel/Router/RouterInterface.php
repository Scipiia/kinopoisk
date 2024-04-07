<?php

namespace App\Kernel\Router;

interface RouterInterface
{
    public function dispatch($uri, $method): void;
}