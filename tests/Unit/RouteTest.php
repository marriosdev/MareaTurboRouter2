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