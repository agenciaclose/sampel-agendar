<?php

//PEDIDOS
$router->namespace("Agencia\Close\Controllers\Site\Pedidos");
$router->get("/pedidos", "PedidosController:listPedidos");
$router->get("/pedidos/add", "PedidosController:addPedido");
$router->get("/pedidos/edit/{id}", "PedidosController:editPedido");
$router->get("/pedidos/view/{id}", "PedidosController:viewPedido");
$router->get("/pedidos/print/{id}", "PedidosController:printPedido");
$router->post("/pedidos/moderate/{id}", "PedidosController:showModerate");
$router->post("/pedidos/tipo", "PedidosController:getTipoEvento");
$router->post("/pedidos/add/save", "PedidosController:addPedidoSave");
$router->post("/pedidos/edit/save", "PedidosController:editPedidoSave");
$router->post("/pedidos/add/status", "PedidosController:statusPedidoSave");