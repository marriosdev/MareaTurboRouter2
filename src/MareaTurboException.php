<?php
declare(strict_types=1);

namespace MareaTurbo;
use Exception;

class MareaTurboException extends Exception
{
    public function __construct(String $message)
    {
        parent::__construct($message);
    }
}