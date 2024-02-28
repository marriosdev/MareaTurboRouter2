<?php declare(strict_types=1);

namespace MareaTurbo;

use MareaTurbo\Controller;
use ReflectionClass;
use ReflectionMethod;

class Router
{
    private array $controllers = [];

    public function controllers(array $controllers = [])
    {
        $this->controllers = $controllers;
        $this->run();
        return $this;
    }

    public function middleware(String $middleware, $httpStatusCode)
    {
        $reflectionClass = new ReflectionClass($middleware);
        $middlewareInstance = $reflectionClass->newInstance();
        if (!$middlewareInstance->handle()) {
            http_response_code($httpStatusCode);
            exit;    
        }
        return $this;
    }
    
    private function run()
    {
        foreach($this->controllers as $controller) {
            $reflectionController = new ReflectionClass($controller);
            foreach($reflectionController->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $this->getRouteAttribute($method, $controller);
            }
        }

        http_response_code(404);
        echo "Route not found";
    }
    
    private function getRouteAttribute($method, $controller)
    {
        if($method->getAttributes()) {
            foreach($method->getAttributes() as $route) {
                $routeInstance = $route->newInstance();
                if($routeInstance->isMatch($this->getAccessedRoute())) {
                    return $this->invokeController($method, $controller, $routeInstance);
                }
            }
        }
    }

    private function invokeController($method, $controller, $routeInstance)
    {
        (new Controller($controller))->runMethod(
            $method->getName(), 
            $routeInstance->getDynamicParameters($this->getAccessedRoute())
        );

        $this->endLifeCycle();
    }

    private function isCli()
    {
        return php_sapi_name() === 'cli';
    }

    private function getAccessedRoute()
    {
        global $argv;
        $route = $this->isCli() ? $argv[2] : explode("?", $_SERVER['REQUEST_URI'])[0];
        $method = $this->isCli() ? $argv[1] : explode("?", $_SERVER['REQUEST_METHOD'])[0];
        return  new Route($route, $method);
    }

    private function endLifeCycle()
    {
        exit;
    }
}
