<?php

// RELATORIOS VISITAS
$router->namespace("Agencia\Close\Controllers\Site\Relatorios");
$router->get("/visitas/relatorios", "RelatoriosController:visitas");

// RELATORIOS PALESTRAS
$router->namespace("Agencia\Close\Controllers\Site\Relatorios");
$router->get("/palestras/relatorios", "RelatoriosPalestrasController:visitas");