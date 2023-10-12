<?php
declare(strict_types=1);

namespace MareaTurbo;

use ReflectionClass;

class Router
{
    private array $controllers = [];

    public function controllers(array $controllers = [])
    {
        $this->controllers = $controllers;
    }
}



















// public function route()
// {
//     $reflection = new ReflectionClass(RouteTeste::class);
//     foreach($reflection->getMethods() as $method) {
//         foreach($method->getAttributes() as $attribute) {
//             $attribute->newInstance();
//             if($attribute->newInstance()->path == "/teste2") {
//             var_dump($method->invoke(new ($reflection->getName())));
//             }
//         }
//     }
// }