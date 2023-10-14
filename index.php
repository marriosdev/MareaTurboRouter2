<?php
require_once("vendor/autoload.php");
use MareaTurbo\Route;

class ControllerTeste
{
    public function __construct(){}

    #[Route("/teste", "POST")]
    public function teste()
    {
        echo "EITA";
    }

    #[Route("/teste/{id}/teste", "GET")]
    public function teste2($parameters)
    {
        echo "EITA 2" . $parameters->id;
    }
}

(new MareaTurbo\Router)->controllers([
    ControllerTeste::class,
]);