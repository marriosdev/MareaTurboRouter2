# Marea Turbo Router

<p>
    <a href="https://packagist.org/packages/marrios/mareaturborouter"><img src="https://img.shields.io/packagist/dt/marrios/mareaturborouter" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/marrios/mareaturborouter"><img src="https://img.shields.io/packagist/v/marrios/mareaturborouter" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/marrios/mareaturborouter"><img src="https://img.shields.io/packagist/l/marrios/mareaturborouter" alt="License"></a>
</p>

A tiny, zero-dependency HTTP router for PHP 8.1+. Routes are declared with PHP attributes right on your controller methods: no route files, no config.

```php
#[Route("/users/{id}", "GET")]
public function show($request) { ... }
```

Created by [@marriosdev](https://github.com/marriosdev).

## Contents

- [Installation](#installation)
- [Quick start](#quick-start)
- [Web server setup](#web-server-setup)
- [Defining routes](#defining-routes)
- [Reading parameters](#reading-parameters)
- [Dependency injection](#dependency-injection)
- [Middleware](#middleware)
- [CLI mode](#cli-mode)
- [How matching works](#how-matching-works)
- [Running the tests](#running-the-tests)

## Installation

```bash
composer require marrios/mareaturborouter
```

Requires **PHP 8.1 or newer**.

## Quick start

Create `index.php`:

```php
<?php

require_once "vendor/autoload.php";

use MareaTurbo\Router;
use MareaTurbo\Route;

class PostController
{
    #[Route("/posts/{id}", "GET")]
    public function show($request)
    {
        echo "Post " . $request->only(["id"])["id"];
    }
}

(new Router())->controllers([
    PostController::class,
]);
```

Run it with PHP's built-in server:

```bash
php -S localhost:8000 index.php
```

Open http://localhost:8000/posts/123 and you'll see `Post 123`.

## Web server setup

Every request has to reach `index.php`. The built-in server above already does that. In production:

**Apache** (`.htaccess` next to `index.php`):

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```

**Nginx**:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## Defining routes

```php
#[Route(string $path, string $method, string $name = '')]
```

| Argument  | Description |
|-----------|-------------|
| `$path`   | URL path. Segments wrapped in `{}` are dynamic parameters. |
| `$method` | `GET`, `POST`, `PUT`, `PATCH`, `DELETE`, `OPTIONS` or `HEAD` (case-insensitive). Anything else throws `MareaTurboException`. |
| `$name`   | Optional label for the route (informational for now). |

A method can carry several routes:

```php
class UserController
{
    #[Route("/users", "GET")]
    public function index($request) { ... }

    #[Route("/users", "POST")]
    public function store($request) { ... }

    #[Route("/users/{id}", "PUT")]
    #[Route("/users/{id}", "PATCH")]
    public function update($request) { ... }

    #[Route("/categories/{category}/posts/{post}", "GET")]
    public function postInCategory($request) { ... }
}
```

Register every controller with the router:

```php
(new Router())->controllers([
    UserController::class,
    PostController::class,
]);
```

## Reading parameters

Every route method gets a `MareaTurbo\Request` argument:

```php
#[Route("/categories/{category}/posts/{post}", "GET")]
public function show($request)
{
    $request->all();               // ["category" => "10", "post" => "5"]
    $request->only(["post"]);      // ["post" => "5"]
}
```

- Values are URL-decoded (`/users/jo%C3%A3o` gives `joão`).
- Values are escaped with `htmlspecialchars` and `addslashes`, so treat them as strings.
- `Request` only holds **path** parameters. Read the query string and body the usual way (`$_GET`, `$_POST`, `file_get_contents('php://input')`).

## Dependency injection

Controller constructors get their dependencies automatically, recursively:

```php
class UserRepository
{
    public function __construct(private Database $db) {}
}

class UserController
{
    public function __construct(private UserRepository $users, private int $perPage = 20) {}

    #[Route("/users", "GET")]
    public function index($request) { ... }
}
```

How each constructor parameter gets its value:

1. A class type → built recursively.
2. Otherwise, its default value.
3. Otherwise, `null` if it's nullable or has no type.
4. Otherwise → `MareaTurboException("Cannot resolve dependency $param of Class")`.

Interfaces can't be resolved. Type-hint concrete classes.

## Middleware

A middleware is any class with a `handle(): bool` method. Return `false` to stop the request with the given HTTP status code:

```php
class AuthMiddleware
{
    public function handle(): bool
    {
        return isset($_SERVER['HTTP_AUTHORIZATION']);
    }
}

(new Router())
    ->middleware(AuthMiddleware::class, 401)
    ->controllers([UserController::class]);
```

> Call `middleware()` **before** `controllers()`. `controllers()` dispatches the request and ends the script, so nothing chained after it runs. Middleware applies to every controller registered on that router.

## CLI mode

Routes can be called from the terminal, which is handy for scripts and debugging:

```bash
php index.php GET /posts/123
```

## How matching works

- Controllers are checked in the order you register them, then their public methods in order. **The first match wins**, its method runs, and the script ends.
- Leading and trailing slashes are ignored: `/posts/1`, `posts/1` and `/posts/1/` are the same.
- The query string is ignored when matching: `/posts/1?page=2` matches `/posts/{id}`.
- Without a match, the response status is `404` and nothing is printed. Print your own "not found" page after `controllers()`:

```php
(new Router())->controllers([...]);

echo "Page not found"; // only reached when no route matched
```

## Running the tests

```bash
composer install
vendor/bin/pest
```

## License

[GPL-3.0](https://www.gnu.org/licenses/gpl-3.0.html)
