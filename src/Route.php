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
    public string $name;
    protected array $inputBag;

    /**
     * 
     */
    public function __construct(String $path, String $method, string $name = '')
    {
        $this->path = $path;
        $this->method = new HttpMethod($method);
        $this->name = $name;
    }

    /**
     * 
     */
    public function isMatch(Route $route): bool
    {
        $currentRoute = $this->routeStringToArray($this->path);
        $accessedRoute = $this->routeStringToArray($route->path);

        if ($route->method->name != $this->method->name) {
            return false;
        }

        if (count($accessedRoute) != count($currentRoute)) {
            return false;
        }

        foreach ($currentRoute as $key => $value) {
            if ($value != $accessedRoute[$key]) {
                if (substr($value, 0, 1) != "{" || substr($value, -1) != "}") {
                    return false;
                }
                $this->inputBag[substr($value, 1, -1)] = htmlspecialchars(trim(addslashes($accessedRoute[$key])));
            }
        }
        return true;
    }
    
    /**
     * 
     */
    public function routeStringToArray(string $string): array
    {
        if (substr($string, -1) == "/") {
            $string = substr($string, 0, -1);
        }
        return explode('/', $string);
    }

    public function getInputBag(): array
    {
        return $this->inputBag;
    }
}
