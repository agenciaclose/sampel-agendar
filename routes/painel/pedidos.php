<?php

//PAINEL PEDIDOS
$router->namespace("Agencia\Close\Controllers\Painel\PedidosPainel");
$router->get("/painel/pedidos", "PedidosPainelController:listaPedidos");
$router->get("/painel/pedidos/add", "PedidosPainelController:addPedido");
$router->post("/painel/pedidos/tipo", "PedidosPainelController:getTipoEvento");
$router->post("/painel/pedidos/add/save", "PedidosPainelController:addPedidoSave");