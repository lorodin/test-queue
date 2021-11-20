<?php

namespace App\Services;

use App\Middlewares\Middleware;
use DI\Container;

class ControllersManagerService
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    private function validateAction(string $controller, string $action) : ?array
    {
        $action = "controller.$controller.$action";

        if (!$this->container->has($action)) {
            return null;
        }

        $action = $this->container->get($action);

        if (!isset($action['action']) || !is_array($action['action']) || count($action['action']) != 2) {
            return null;
        }

        $action['action'][1] .= 'Action';

        if (!is_callable($action['action'])) {
            return null;
        }

        return $action;
    }

    public function callAction(string $controller, string $action, array $data) {
        $action = $this->validateAction($controller, $action);

        if (!$action) {
            throw new \InvalidArgumentException("Unknown action: {$controller}.{$action}Action");
        }

        $middlewares = $action['middlewares'] ?? [];

        $request = $data;

        foreach ($middlewares as $middleware) {
            if (!$this->container->has("middleware.$middleware")) {
                throw new \InvalidArgumentException("Unknown middleware: $middleware");
            }

            $middlewareObj = $this->container->make("middleware.$middleware");

            if (!($middlewareObj instanceof Middleware)) {
                print_r($middlewareObj);
                throw new \InvalidArgumentException("Middleware $middleware not implement interface App\\Middlewares\\Middleware");
            }

            $this->container->call(
                [$middlewareObj, "next"],
                [$request, function ($req) use (&$request) {
                    $request = $req;
                }]
            );
        }

        $this->container->call(
            $action['action'],
            [$request]
        );
    }
}
