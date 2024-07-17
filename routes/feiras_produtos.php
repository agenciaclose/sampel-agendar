<?php

//PAINEL PRODUTOS
$router->namespace("Agencia\Close\Controllers\Painel\FeirasPainel");
$router->get("/painel/feiras", "FeirasPainelController:index");
$router->get("/painel/feiras/add", "FeirasPainelController:productAdd");
$router->get("/painel/feiras/edit/{id}", "FeirasPainelController:productEdit");
$router->post("/painel/feiras/add/save", "FeirasPainelController:productAddSave");
$router->post("/painel/feiras/edit/save", "FeirasPainelController:productEditSave");
$router->post("/painel/feiras/status", "FeirasPainelController:productStatus");
