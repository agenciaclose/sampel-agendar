<?php

//PAINEL PATROCINIOS
$router->namespace("Agencia\Close\Controllers\Painel\PatrociniosPainel");
$router->get("/painel/patrocinios", "PatrociniosPainelController:index");
$router->get("/painel/patrocinios/add", "PatrociniosPainelController:productAdd");
$router->get("/painel/patrocinios/edit/{id}", "PatrociniosPainelController:productEdit");
$router->post("/painel/patrocinios/add/save", "PatrociniosPainelController:addPatrocinioSave");
$router->post("/painel/patrocinios/edit/save", "PatrociniosPainelController:editPatrocinioSave");
$router->post("/painel/patrocinios/status", "PatrociniosPainelController:productStatus");
