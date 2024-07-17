<?php

//PAINEL PRODUTOS
$router->namespace("Agencia\Close\Controllers\Painel\EventosPainel");
$router->get("/painel/eventos", "EventosPainelController:index");
$router->get("/painel/eventos/add", "EventosPainelController:productAdd");
$router->get("/painel/eventos/edit/{id}", "EventosPainelController:productEdit");
$router->post("/painel/eventos/add/save", "EventosPainelController:productAddSave");
$router->post("/painel/eventos/edit/save", "EventosPainelController:productEditSave");
$router->post("/painel/eventos/status", "EventosPainelController:productStatus");
