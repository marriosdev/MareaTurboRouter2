<?php

namespace MareaTurbo;

class HttpMethod
{

    public String $name;

    public function __construct(String $name)
    {
        $this->name = $this->validateName($name);
    }

    private function validateName(String $name) : String
    {
        if (!in_array($name, ["GET", "POST", "PUT", "DELETE", "PATCH", "OPTIONS", "HEAD"])) {
            throw new \Exception("Invalid http method");
        }
        return $name;
    }
}