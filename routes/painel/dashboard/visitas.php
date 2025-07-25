<?php

//PAINEL VISITAS DASHBORAD
$router->namespace("Agencia\Close\Controllers\Painel\Dashboard");
$router->get("/painel/dashboard/visitas", "DashboardVisitasController:index");
// $router->post("/painel/dashboard/visitas/orcamento-por-mes", "DashboardVisitasController:getListaOrcamentoPorMes");
// $router->post("/painel/dashboard/visitas/valores-por-mes", "DashboardVisitasController:getListaValorTotalPorMes");

$router->namespace("Agencia\Close\Controllers\Painel\Visitas");
$router->get("/painel/visitas/reprovar-teste/{id}", "VisitasController:reprovarTeste");