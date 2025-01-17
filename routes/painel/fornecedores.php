<?php

//PAINEL FORNECEDORES
$router->namespace("Agencia\Close\Controllers\Painel\Fornecedores");
$router->get("/painel/contratos/fornecedores/get/terms", "FornecedoresController:getTerms");
$router->get("/painel/orcamento/get/terms", "OrcamentosPainelController:getTerms");
$router->get("/painel/contratos/fornecedores/add", "FornecedoresController:itemAdd");
$router->get("/painel/contratos/fornecedores/edit/{id}", "FornecedoresController:itemEdit");
$router->post("/painel/contratos/fornecedores/add/save", "FornecedoresController:itemAddSave");
$router->post("/painel/contratos/fornecedores/edit/save", "FornecedoresController:itemEditave");
$router->post("/painel/contratos/fornecedores/status", "FornecedoresController:itemStatus");

$router->get("/painel/contratos/fornecedores/find-cnpj", "FornecedoresController:findCNPJ");