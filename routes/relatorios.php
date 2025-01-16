<?php

// RELATORIOS VISITAS
$router->namespace("Agencia\Close\Controllers\Site\Relatorios");
$router->get("/visitas/relatorios", "RelatoriosController:visitas");
$router->post("/visitas/relatorios/mapa", "RelatoriosController:mapa");
$router->get("/visitas/relatorios/mapa/iframe", "RelatoriosController:mapaIframe");

// RELATORIOS PALESTRAS
$router->namespace("Agencia\Close\Controllers\Site\Relatorios");
$router->get("/palestras/relatorios", "RelatoriosPalestrasController:visitas");
$router->post("/palestras/relatorios/mapa", "RelatoriosPalestrasController:mapa");
$router->get("/palestras/relatorios/mapa/iframe", "RelatoriosPalestrasController:mapaIframe");
$router->get("/palestras/relatorios/mapa/exportCidadesJson", "RelatoriosPalestrasController:exportCidadesJson");