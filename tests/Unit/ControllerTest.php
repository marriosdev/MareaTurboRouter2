<?php

class CtrlDep {}
class CtrlWithDeps
{
    public function __construct(public CtrlDep $dep, public int $limit = 10, $untyped = null) {}
    public function show($request) { return [$this->dep, $this->limit, $request->all()]; }
}
class CtrlUnresolvable
{
    public function __construct(int $required) {}
    public function show($request) {}
}

function matchedRequest(): \MareaTurbo\Request
{
    $route = new \MareaTurbo\Route("/x/{id}", "GET");
    $route->isMatch(new \MareaTurbo\Route("/x/7", "GET"));
    return new \MareaTurbo\Request($route);
}

test("builds class dependencies and uses defaults for scalar parameters", function () {
    [$dep, $limit, $params] = (new \MareaTurbo\Controller(CtrlWithDeps::class))->runMethod("show", matchedRequest());

    expect($dep)->toBeInstanceOf(CtrlDep::class)
        ->and($limit)->toBe(10)
        ->and($params)->toBe(["id" => "7"]);
});

test("throws a clear exception when a dependency cannot be resolved", function () {
    (new \MareaTurbo\Controller(CtrlUnresolvable::class))->runMethod("show", matchedRequest());
})->throws(\MareaTurbo\MareaTurboException::class, 'Cannot resolve dependency $required');

test("throws when the method does not exist", function () {
    (new \MareaTurbo\Controller(CtrlWithDeps::class))->runMethod("nope", matchedRequest());
})->throws(\MareaTurbo\MareaTurboException::class, "Method not found");
