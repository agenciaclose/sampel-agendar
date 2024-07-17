<?php

use CoffeeCode\Router\Router;

$router = new Router(DOMAIN);

require  __DIR__ . '/site.php';
require  __DIR__ . '/emails.php';
require  __DIR__ . '/visita.php';
require  __DIR__ . '/palestras.php';
require  __DIR__ . '/relatorios.php';
require  __DIR__ . '/painel.php';
require  __DIR__ . '/painel_produtos.php';
require  __DIR__ . '/feiras_produtos.php';

// ERROR
$router->group("error")->namespace("Agencia\Close\Controllers\Error");
$router->get("/{errorCode}", "ErrorController:show", 'error');

$router->dispatch();
if ($router->error()) {
    echo "Página não encontrada.";
}