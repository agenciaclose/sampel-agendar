<?php
// PAINEL ORÇAMENTOS
$router->namespace("Agencia\Close\Controllers\Painel\Orcamentos");
$router->get("/painel/orcamento/get/terms", "OrcamentosPainelController:getTerms");
$router->get("/painel/orcamento/tags/get/terms", "OrcamentosPainelController:getTermsTags");
$router->get("/painel/{tipo}/orcamento/{id}", "OrcamentosPainelController:lista");
$router->get("/painel/{tipo}/orcamento/add/{id}", "OrcamentosPainelController:addOrcamento");
$router->get("/painel/{tipo}/orcamento/add/{id}/{fornecedor}", "OrcamentosPainelController:addOrcamento");
$router->get("/painel/{tipo}/orcamento/edit/{id}/{id_edit}", "OrcamentosPainelController:editOrcamento");
$router->get("/painel/{tipo}/orcamento/edit/{id}/{id_edit}/{fornecedor}", "OrcamentosPainelController:editOrcamento");
$router->post("/painel/orcamento/add/save", "OrcamentosPainelController:addOrcamentoSave");
$router->post("/painel/orcamento/edit/save", "OrcamentosPainelController:editOrcamentoSave");
$router->post("/painel/orcamento/remove/{id}", "OrcamentosPainelController:removeOrcamento");
$router->post("/painel/orcamento/tipo_contrato", "OrcamentosPainelController:tipoContrato");

$router->get("/painel/contratos/eventos/get/terms", "OrcamentosPainelController:getTermsEventos");
$router->get("/painel/orcamentos/pagamentos/recorrentes/update", "OrcamentosPainelController:verificarPagamentosRecorrentes");