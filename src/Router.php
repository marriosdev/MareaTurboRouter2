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
        return $this;
    }

    private function run()
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
}
