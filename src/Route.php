<?php

declare(strict_types=1);

namespace MareaTurbo;

use Attribute;

#[Attribute]
class Route
{
    public String $path;
    public HttpMethod $method;
    public string $name;
    protected array $inputBag = [];

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
                $this->inputBag[substr($value, 1, -1)] = htmlspecialchars(trim(addslashes(rawurldecode($accessedRoute[$key]))));
            }
        }
        return true;
    }
    
    /**
     * 
     */
    public function routeStringToArray(string $string): array
    {
        // "/post/1", "post/1" and "post/1/" are the same route
        return explode('/', trim($string, '/'));
    }

    public function getInputBag(): array
    {
        return $this->inputBag;
    }
}
