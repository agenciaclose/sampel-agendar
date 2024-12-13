<?php

//PAINEL PATROCINIOS
$router->namespace("Agencia\Close\Controllers\Painel\Dashboard");
$router->get("/painel/dashboard/contratos", "DashboardContratosController:index");
$router->post("/painel/dashboard/contratos/getListaOrcamentoPorMes", "DashboardContratosController:getListaOrcamentoPorMes");