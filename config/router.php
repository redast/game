<?php

/**
 * Load the routes into the router, this file is included from
 * `htdocs/index.php` during the bootstrapping to prepare for the request to
 * be handled.
 */

declare(strict_types=1);

use FastRoute\RouteCollector;

$router = $router ?? null;

$router->addRoute("GET", "/test", function () {
    // A quick and dirty way to test the router or the request.
    return "Testing response";
});

$router->addRoute("GET", "/", "\Mos\Controller\Index");
$router->addRoute("GET", "/debug", "\Mos\Controller\Debug");
$router->addRoute("GET", "/twig", "\Mos\Controller\TwigView");

$router->addGroup("/session", function (RouteCollector $router) {
    $router->addRoute("GET", "", ["\Mos\Controller\Session", "index"]);
    $router->addRoute("GET", "/destroy", ["\Mos\Controller\Session", "destroy"]);
});

$router->addGroup("/some", function (RouteCollector $router) {
    $router->addRoute("GET", "/where", ["\Mos\Controller\Sample", "where"]);
});

$router->addGroup("/form", function (RouteCollector $router) {
    $router->addRoute("GET", "/view", ["\Mos\Controller\Game21", "view"]);
    $router->addRoute("POST", "/process", ["\Mos\Controller\Game21", "process"]);
});

$router->addRoute("GET", "/dice", ["\Mos\Controller\Game", "playGame"]);

$router->addRoute("POST", "/dice", ["\Mos\Controller\Game", "stopGame"]);

$router->addRoute("GET", "/dice/reset", ["\Mos\Controller\Game", "resetGame"]);

$router->addGroup("/yatzy-form", function (RouteCollector $router) {
    $router->addRoute("GET", "/view", ["\Mos\Controller\Yatzy", "view"]);
    $router->addRoute("POST", "/process", ["\Mos\Controller\Yatzy", "process"]);
});

$router->addGroup("/yatzy", function (RouteCollector $router) {
    $router->addRoute("GET", "/play", ["\Mos\Controller\YatzyGame", "playGame"]);
    $router->addRoute("POST", "/roll", ["\Mos\Controller\YatzyGame", "roll"]);
   //$router->addRoute("GET", "/roll", ["\Mos\Controller\YatzyGame", "roll"]);
});