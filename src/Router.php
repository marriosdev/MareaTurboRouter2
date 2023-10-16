<?php
declare(strict_types=1);

namespace MareaTurbo;

use MareaTurbo\Controller;
use ReflectionClass;

class Router
{
    private array $controllers = [];

    public function controllers(array $controllers = [])
    {
        $this->controllers = $controllers;
        $this->run();
    }

    public function run()
    {
        $acessedRoute = $this->getAccessedRoute();
        
        foreach($this->controllers as $controller) {
            $reflectionController = new ReflectionClass($controller);
            foreach($reflectionController->getMethods() as $method) {
                foreach($method->getAttributes() as $route) {
                    $routeInstance = $route->newInstance();
                    if($routeInstance->isMatch($acessedRoute)) {
                        return (new Controller($controller))->runMethod(
                            $method->getName(), 
                            $routeInstance->getDynamicParameters($acessedRoute)
                        );
                    }
                }
            }
        }
    }

    public function isCli()
    {
        return php_sapi_name() === 'cli';
    }

    public function getAccessedRoute()
    {
        global $argv;
        $route = $this->isCli() ? $argv[1] : $_SERVER['REQUEST_URI'];
        $method = $this->isCli() ? "GET" : $_SERVER['REQUEST_METHOD'];

        return  new Route($route, $method);
    }
}
