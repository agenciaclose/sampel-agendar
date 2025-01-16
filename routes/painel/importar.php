<?php

//PAINEL IMPORTAR
$router->namespace("Agencia\Close\Controllers\Painel\ImportarPainel");
$router->get("/painel/importar", "ImportarPainelController:index");
$router->post("/painel/importar/salvar", "ImportarPainelController:salvar");