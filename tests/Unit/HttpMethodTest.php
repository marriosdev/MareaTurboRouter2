<?php

test('the name must be valid in upper case - GET, POST, etc', function () {
    $httpMethod = new \MareaTurbo\HttpMethod("GET");
    expect($httpMethod->name)->toBe("GET");    
});

test('the name must be valid - get, post, etc', function () {
    $httpMethod = new \MareaTurbo\HttpMethod("post");
    expect($httpMethod->name)->toBe("POST");    
});

test('an exception should occur if the name is invalid', function () { 
    $httpMethod = new \MareaTurbo\HttpMethod("INVALID");
})->throws(\MareaTurbo\MareaTurboException::class, "Invalid http method");

