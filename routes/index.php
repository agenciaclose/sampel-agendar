<?php

use CoffeeCode\Router\Router;

$router = new Router(DOMAIN);

require  __DIR__ . '/site.php';
require  __DIR__ . '/emails.php';
require  __DIR__ . '/visita.php';
require  __DIR__ . '/palestras.php';
require  __DIR__ . '/relatorios.php';
require  __DIR__ . '/eventos.php';
require  __DIR__ . '/pedidos.php';

//PAINEL
require  __DIR__ . '/painel.php';
require  __DIR__ . '/painel/produtos.php';
require  __DIR__ . '/painel/eventos.php';
require  __DIR__ . '/painel/pedidos.php';
require  __DIR__ . '/painel/orcamentos.php';
require  __DIR__ . '/painel/patrocinios.php';
require  __DIR__ . '/painel/empenho.php';
require  __DIR__ . '/painel/contratos.php';
require  __DIR__ . '/painel/dashboard/contratos.php';
require  __DIR__ . '/painel/dashboard/visitas.php';
require  __DIR__ . '/painel/importar.php';
require  __DIR__ . '/painel/fornecedores.php';

// CONFIGURAÇÃO DE EMAILS
require  __DIR__ . '/painel/configemails.php';

// ERROR
$router->group("error")->namespace("Agencia\Close\Controllers\Error");
$router->get("/{errorCode}", "ErrorController:show", 'error');

$router->dispatch();
if ($router->error()) {
    echo "Página não encontrada.";
}