<?php
// PAINEL ORÇAMENTOS
$router->namespace("Agencia\Close\Controllers\Painel\Orcamentos");
$router->get("/painel/orcamento/get/terms", "OrcamentosPainelController:getTerms");
$router->get("/painel/orcamento/tags/get/terms", "OrcamentosPainelController:getTermsTags");
$router->get("/painel/{tipo}/orcamento/{id}", "OrcamentosPainelController:lista");
$router->get("/painel/{tipo}/orcamento/add/{id}", "OrcamentosPainelController:addOrcamento");
$router->get("/painel/{tipo}/orcamento/edit/{id}/{id_edit}", "OrcamentosPainelController:editOrcamento");
$router->post("/painel/orcamento/add/save", "OrcamentosPainelController:addOrcamentoSave");
$router->post("/painel/orcamento/edit/save", "OrcamentosPainelController:editOrcamentoSave");
$router->post("/painel/orcamento/remove/{id}", "OrcamentosPainelController:removeOrcamento");
$router->post("/painel/orcamento/tipo_contrato", "OrcamentosPainelController:tipoContrato");