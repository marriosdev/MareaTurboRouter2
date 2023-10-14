### Marea Turbo Router
#### PHP Version 8.1^
<img src="mareaGif/marea.gif" alt="VRUMMMMMMMMMMMMMMMM" width="50%"/>

## Copy
```php

<?php

require_once("vendor/autoload.php");

use MareaTurbo\Router;

use MareaTurbo\Route;

class ControllerTeste
{
    public function __construct()
    {}


    #[Route("/teste/{id}", "GET")]
    public function teste($parameters)
    {
        echo $parameters->id;
    }
}

// Register  controllers
(new Router())->controllers([
    ControllerTeste::class
]);
```

In your browser, access the URL: http://localhost/teste/123
<br>
And you will see the result: 123