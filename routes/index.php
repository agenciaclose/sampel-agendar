<?php

use CoffeeCode\Router\Router;

$router = new Router(DOMAIN);

require  __DIR__ . '/site.php';
require  __DIR__ . '/emails.php';
require  __DIR__ . '/visita.php';
require  __DIR__ . '/palestras.php';
require  __DIR__ . '/painel.php';

// ERROR
$router->group("error")->namespace("Agencia\Close\Controllers\Error");
$router->get("/{errorCode}", "ErrorController:show", 'error');

$router->dispatch();
if ($router->error()) {
    echo "Página não encontrada.";
}