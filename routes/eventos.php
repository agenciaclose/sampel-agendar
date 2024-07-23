<?php

// PAGE EVENTOS
$router->namespace("Agencia\Close\Controllers\Site\Eventos");
$router->get("/eventos", "EventosController:eventos");
$router->get("/eventos/add", "EventosController:addEvento");
$router->get("/eventos/edit/{id}", "EventosController:editEvento");
$router->post("/eventos/add/save", "EventosController:addEventoSave");
$router->post("/eventos/edit/save", "EventosController:editEventoSave");

//CONTROLE DE BRINDES
