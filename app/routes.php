<?php

use Aura\SqlQuery\QueryFactory;
use DI\ContainerBuilder;
use FastRoute\RouteCollector;
use Delight\Auth\Auth;
use League\Plates\Engine;
use Tuupola\Middleware\HttpBasicAuthentication;
use Tuupola\Middleware\HttpBasicAuthentication\PdoAuthenticator;

$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions([
    Engine::class    =>  function() {
        return new Engine('../app/Views');
    },

    PDO::class => function() {
        $driver = config('database.driver');
        $host = config('database.host');
        $database_name = config('database.database_name');
        $username = config('database.username');
        $password = config('database.password');

        return new PDO("$driver:host=$host;dbname=$database_name;charset=utf8", $username, $password);
    },
    Delight\Auth\Auth::class   =>  function($container) {
        return new Auth($container->get('PDO'),'','',false);
    },

    QueryFactory::class  =>  function() {
        return new QueryFactory('mysql');
    },

    HttpBasicAuthentication::class => function($container) {
     return new HttpBasicAuthentication([
          "users" =>[
              'admin' => '123',
          ],
         "authenticator" => new PdoAuthenticator([
             "pdo" => $container->get('PDO'),
             "table" => 'users',
             'user' => 'name',
             'hash' => 'password',
              "users" => 'admin',
         ])
     ]);
    }
]);

$container = $containerBuilder->build();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->get('/page/{id:\d+}', ['App\Controllers\TaskController', 'index']);
    $r->get('/', ['App\Controllers\TaskController', 'index']);
    $r->get('/create', ['App\Controllers\TaskController', 'create']);
    $r->post('/task/store', ['App\Controllers\TaskController', 'store']);
    $r->get('/show', ['App\Controllers\TaskController', 'show']);
    $r->get('/task/{id:\d+}/edit', ['App\Controllers\TaskController', 'edit']);
    $r->post('/task/{id:\d+}/update', ['App\Controllers\TaskController', 'update']);
    $r->post('/task/{id:\d+}/destroy', ['App\Controllers\TaskController', 'destroy']);
    $r->post('/login', ['App\Controllers\UserController', 'login']);
    $r->get('/logout', ['App\Controllers\UserController', 'logout']);
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
// ... 404 Not Found
        echo '<h1> 404 Страница не найдена </h1>';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
// ... 405 Method Not Allowed
        echo '<h1> Метод запроса не верный </h1>';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $container->call($handler, $vars);
// ... call $handler with $vars
        break;
}