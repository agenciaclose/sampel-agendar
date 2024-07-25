<?php

//PAINEL BRINDES
$router->namespace("Agencia\Close\Controllers\Painel\PedidosPainel");
$router->get("/painel/pedidos/add", "PedidosPainelController:index");
$router->post("/painel/pedidos/tipo", "PedidosPainelController:getTipoEvento");
