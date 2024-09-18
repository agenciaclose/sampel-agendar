<?php

//PAINEL PRODUTOS
$router->namespace("Agencia\Close\Controllers\Painel\ProdutosPainel");
$router->get("/painel/produtos", "ProdutosPainelController:index");
$router->get("/painel/produtos/add", "ProdutosPainelController:productAdd");
$router->get("/painel/produtos/edit/{id}", "ProdutosPainelController:productEdit");
$router->post("/painel/produtos/add/save", "ProdutosPainelController:productAddSave");
$router->post("/painel/produtos/edit/save", "ProdutosPainelController:productEditSave");
$router->post("/painel/produtos/status", "ProdutosPainelController:productStatus");
$router->get("/painel/produtos/user/{id}", "ProdutosPainelController:productsByUser");
