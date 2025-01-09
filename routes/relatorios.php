<?php

// RELATORIOS VISITAS
$router->namespace("Agencia\Close\Controllers\Site\Relatorios");
$router->get("/visitas/relatorios", "RelatoriosController:visitas");
$router->post("/visitas/relatorios/mapa", "RelatoriosController:mapa");

// RELATORIOS PALESTRAS
$router->namespace("Agencia\Close\Controllers\Site\Relatorios");
$router->get("/palestras/relatorios", "RelatoriosPalestrasController:visitas");
$router->post("/palestras/relatorios/mapa", "RelatoriosPalestrasController:mapa");