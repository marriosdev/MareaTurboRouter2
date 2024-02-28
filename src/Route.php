<?php
declare(strict_types=1);

namespace MareaTurbo;

use MareaTurbo\RouteParameters;
use Attribute;

#[Attribute]
class Route
{
    public String $path;
    public HttpMethod $method;

    public function __construct(String $path, String $method) {
        $this->path = $path;
        $this->method = new HttpMethod($method);
    }

    public function isMatch(Route $route) : bool
    {
        $currentRoute = $this->routeStringToArray($this->path);
        $accessedRoute = $this->routeStringToArray($route->path);
        
        if($route->method->name != $this->method->name) {
            return false;
        }

        if(count($accessedRoute) != count($currentRoute)) {
            return false;
        }

        for($i = 0; $i < count($accessedRoute); $i++) {
            if($accessedRoute[$i] != $currentRoute[$i]) {
                if(strpos($currentRoute[$i], '{') !== false) {
                    continue;
                }
                return false;
            }
        }
        return true;
    }

    public function getDynamicParameters(Route $acessedRoute) : RouteParameters
    {
        $currentRoute  = $this->routeStringToArray($this->path);
        $accessedRoute = $this->routeStringToArray($acessedRoute->path);

        $parameters = new RouteParameters();
        
        for($i=0; $i < count($accessedRoute); $i++) {
            if($accessedRoute[$i] != $currentRoute[$i]) {
                if(strpos($currentRoute[$i], '{') !== false) {
                    $parameters->{str_replace(['{', '}'], '', $currentRoute[$i])} = $accessedRoute[$i];
                }
            }
        }
        return $parameters;
    }

    public function routeStringToArray(string $string) : array
    {
        if(substr($string, -1) == "/") {
            $string = substr($string, 0, -1);
        }
        return explode('/', $string);
    }
}
