<?php

//PAINEL EMPENHO
$router->namespace("Agencia\Close\Controllers\Painel\Empenho");
$router->get("/painel/empenho", "EmpenhoController:index");
$router->get("/painel/empenho/add", "EmpenhoController:empenhoAdd");
$router->get("/painel/empenho/edit/{id}", "EmpenhoController:empenhoEdit");
$router->post("/painel/empenho/add/save", "EmpenhoController:addEmpenhoSave");
$router->post("/painel/empenho/edit/save", "EmpenhoController:editEmpenhoSave");
$router->post("/painel/empenho/remove", "EmpenhoController:removeEmpenho");
