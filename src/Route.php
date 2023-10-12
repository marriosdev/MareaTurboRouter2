<?php

namespace MareaTurbo;

use Attribute;

#[Attribute]
class Router
{
    public String $path;
    public String $method;

    public function __construct(String $path = '/', String $method = "GET")
    {
        $this->path = $path;
        $this->method = $method;
    }
}