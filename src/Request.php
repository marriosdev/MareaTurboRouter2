<?php

declare(strict_types=1);

namespace MareaTurbo;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class Request
{
    /**
     * 
     */
    public function __construct(\MareaTurbo\Route $route)
    {
        $this->route = $route;
    }

    /**
     * 
     */
    public function all()
    {
        return $this->route->getInputBag();
    }

    /**
     * 
     */
    public function only(array $keys = [])
    {
        $inputBag = $this->route->getInputBag();
        $filtered = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $inputBag)) {
                $filtered[$key] = $inputBag[$key];
            }
        }
        return $filtered;
    }
}
