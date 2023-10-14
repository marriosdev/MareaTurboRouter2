<?php
declare(strict_types=1);

namespace MareaTurbo;

class HttpMethod
{

    public String $name;

    public function __construct(String $name)
    {
        $this->name = $this->validateName(strtoupper($name));
    }

    protected function validateName(String $name) : String
    {
        if (!in_array($name, ["GET", "POST", "PUT", "DELETE", "PATCH", "OPTIONS", "HEAD"])) {
            throw new \MareaTurbo\MareaTurboException("Invalid http method");
        }
        return $name;
    }
}