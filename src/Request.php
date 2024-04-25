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
        return get_object_vars($this);
    }

    /**
     * 
     */
    public function only(array $keys = [])
    {
        return array_filter($keys, function ($key) {
            return $this->$key;
        });
    }
}
