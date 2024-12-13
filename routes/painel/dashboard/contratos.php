<?php

//PAINEL PATROCINIOS
$router->namespace("Agencia\Close\Controllers\Painel\Dashboard");
$router->get("/painel/dashboard/contratos", "DashboardContratosController:index");
$router->post("/painel/dashboard/contratos/orcamento-por-mes", "DashboardContratosController:getListaOrcamentoPorMes");
$router->post("/painel/dashboard/contratos/valores-por-mes", "DashboardContratosController:getListaValorTotalPorMes");