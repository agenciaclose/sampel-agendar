<?php

//PAINEL PATROCINIOS
$router->namespace("Agencia\Close\Controllers\Painel\ContratosPainel");
$router->get("/painel/contratos", "ContratosPainelController:index");
$router->get("/painel/contratos/add", "ContratosPainelController:productAdd");
$router->get("/painel/contratos/edit/{id}", "ContratosPainelController:productEdit");
$router->post("/painel/contratos/add/save", "ContratosPainelController:addContratoSave");
$router->post("/painel/contratos/edit/save", "ContratosPainelController:editContratoSave");
$router->post("/painel/contratos/status", "ContratosPainelController:productStatus");
