<?php

//PAINEL PEDIDOS
$router->namespace("Agencia\Close\Controllers\Painel\PedidosPainel");
$router->get("/painel/pedidos", "PedidosPainelController:listPedidos");
$router->get("/painel/pedidos/add", "PedidosPainelController:addPedido");
$router->get("/painel/pedidos/edit/{id}", "PedidosPainelController:editPedido");
$router->get("/painel/pedidos/view/{id}", "PedidosPainelController:viewPedido");
$router->get("/painel/pedidos/print/{id}", "PedidosPainelController:printPedido");
$router->get("/painel/pedidos/print-faturamento/{id}", "PedidosPainelController:printFaturamentoPedido");
$router->get("/painel/pedidos/user/{id}", "PedidosPainelController:userPedidosProdutos");
$router->post("/painel/pedidos/moderate/{id}", "PedidosPainelController:showModerate");
$router->post("/painel/pedidos/moderate/save", "PedidosPainelController:statusPedidoSave");
$router->post("/painel/pedidos/tipo", "PedidosPainelController:getTipoEvento");
$router->post("/painel/pedidos/add/save", "PedidosPainelController:addPedidoSave");
$router->post("/painel/pedidos/edit/save", "PedidosPainelController:editPedidoSave");
$router->post("/painel/pedidos/emitente", "PedidosPainelController:getEmitentData");