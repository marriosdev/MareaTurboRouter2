<?php

test("must return true when the http routes and methods are the same as the request", function () {
    $route = new \MareaTurbo\Route("/post/1", "GET");
    $currentRoute = new \MareaTurbo\Route("/post/1", "GET");
    
    $match = $route->isMatch($currentRoute);
    
    expect($match)->toBeTrue();
});

test("must return true when it is equal and contains dynamic parameters", function () {
    $route = new \MareaTurbo\Route("category/{id_category}/post/{id}", "GET");
    $currentRoute = new \MareaTurbo\Route("category/10/post/1", "GET");
    
    $match = $route->isMatch($currentRoute);
    
    expect($match)->toBeTrue();
});

test("should return false when different", function () {
    $route = new \MareaTurbo\Route("/post/{id}/limit", "GET");
    $currentRoute = new \MareaTurbo\Route("/post/1", "GET");
    
    $match = $route->isMatch($currentRoute);
    
    expect($match)->toBeFalse();
});

test("must return false when the method is different", function () {
    $route = new \MareaTurbo\Route("/post/{id}/limit", "GET");
    $currentRoute = new \MareaTurbo\Route("/post/1", "POST");
    
    $match = $route->isMatch($currentRoute);
    
    expect($match)->toBeFalse();
});

test("input bag is empty, not an error, for routes without parameters", function () {
    $route = new \MareaTurbo\Route("/post", "GET");
    $route->isMatch(new \MareaTurbo\Route("/post", "GET"));

    expect($route->getInputBag())->toBe([]);
});

test("leading and trailing slashes do not matter", function () {
    $route = new \MareaTurbo\Route("/post/{id}", "GET");

    expect($route->isMatch(new \MareaTurbo\Route("post/1/", "GET")))->toBeTrue();
});

test("dynamic parameters are url-decoded and captured", function () {
    $route = new \MareaTurbo\Route("/user/{name}", "GET");
    $route->isMatch(new \MareaTurbo\Route("/user/jo%C3%A3o", "GET"));

    expect($route->getInputBag())->toBe(["name" => "joão"]);
});
