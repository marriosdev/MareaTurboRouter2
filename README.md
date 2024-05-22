# Marea Turbo Router
#### PHP Version 8.1^
<img src="mareaGif/marea.gif" alt="VRUMMMMMMMMMMMMMMMM" width="50%"/>

<p align="center">
    <a href="https://packagist.org/packages/marrios/router"><img src="https://img.shields.io/packagist/dt/marrios/mareaturborouter" alt="Total Downloads"</a>
    <a href="https://packagist.org/packages/marrios/router"><img src="https://img.shields.io/packagist/v/marrios/mareaturborouter" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/marrios/router"><img src="https://img.shields.io/packagist/l/marrios/mareaturborouter" alt="License"></a>
</p>

@marriosdev

<br>

# Starting

```php
composer require marrios/mareaturborouter
```

### Copy
```php

<?php

require_once("vendor/autoload.php");

use MareaTurbo\Router;

use MareaTurbo\Route;

class ControllerTeste
{
    public function __construct()
    {}

    #[Route("/teste/{id}", "GET", "route.name")]
    public function teste($request)
    {
        // get all parameters
        $request->all();

        // get only id parameter
        echo $request->only(["id"])['id'];
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

<br>

You can run this route in CLI mode

```shell
php .\index.php GET /teste/123
```
