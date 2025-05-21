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

//PAINEL PRODUTOS VISIBILIDADE
$router->get("/painel/produtos/visibilidade", "ProdutosVisibilidadeController:index");
$router->get("/painel/produtos/visibilidade/create", "ProdutosVisibilidadeController:create");
$router->post("/painel/produtos/visibilidade/store", "ProdutosVisibilidadeController:store");
$router->get("/painel/produtos/visibilidade/edit/{id}", "ProdutosVisibilidadeController:edit");
$router->post("/painel/produtos/visibilidade/update/{id}", "ProdutosVisibilidadeController:update");
$router->get("/painel/produtos/visibilidade/delete/{id}", "ProdutosVisibilidadeController:delete");
