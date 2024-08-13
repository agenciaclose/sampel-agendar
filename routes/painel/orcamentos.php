<?php
// PAINEL ORÇAMENTOS
$router->namespace("Agencia\Close\Controllers\Painel\Orcamentos");
$router->get("/painel/{tipo}/orcamento/{id}", "OrcamentosPainelController:lista");
// $router->get("/painel/orcamento/editar/{id}", "OrcamentosPainelController:editar");