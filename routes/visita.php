<?php

// PAGE AGENDAR VISITA
$router->namespace("Agencia\Close\Controllers\Site\Agendar");
$router->get("/agendar", "AgendarController:index");
$router->post("/agendar/cadastro", "AgendarController:cadastro");
$router->post("/agendar/editar", "AgendarController:editar");
$router->get("/agendar/checkCloseEventsClose", "AgendarController:checkCloseEventsClose");
$router->get("/agendar/checkEventsConcluido", "AgendarController:checkEventsConcluido");

// PAGE VISITA
$router->namespace("Agencia\Close\Controllers\Site\Visitas");
$router->get("/visitas", "VisitasController:visitas");
$router->get("/visitas", "VisitasController:agendamentos");
$router->get("/visitas/outras", "VisitasController:outras");
$router->get("/visitas/concluidas", "VisitasController:concluidas");
$router->get("/visitas/relatorios", "VisitasController:relatorios");
$router->get("/visita/lista/{id}", "VisitasController:lista");

$router->get("/visita/inscricao/{id}", "VisitasController:inscricao");
$router->get("/visita/inscricao/{id}/{inscricao}", "VisitasController:inscricao");
$router->post("/visita/inscricao/cadastro", "VisitasController:inscricaoCadastro");
$router->post("/visita/inscricao/cadastro-qrcode", "VisitasController:inscricaoCadastroQRcode");
$router->get("/etiqueta/{codigo}", "VisitasController:printEtiqueta");
$router->get("/etiqueta/all/{id}", "VisitasController:printEtiqueatAll");
$router->post("/visita/inscricao/checkCadastroCampo", "VisitasController:checkCadastroCampo");
$router->get("/visita/inscricao/cpfautocomplete", "VisitasController:CPFAutoComplete");
$router->post("/visita/listaEquipesSave", "VisitasController:listaEquipesSave");

//SORTEIO
$router->post("/visita/sortear", "VisitasController:sortear");
$router->get("/visita/sorteados/{id}", "VisitasController:sorteados");

// FEEDBACK
$router->namespace("Agencia\Close\Controllers\Site\Feedback");
$router->get("/feedback/ver/{id}", "FeedbackController:feedbacksEstatisticas");

$router->namespace("Agencia\Close\Controllers\Site\Feedback");
$router->get("/visita/feedback/{cpf}/{id}", "FeedbackController:feedback");
$router->post("/visita/feedback/save", "FeedbackController:saveFeedback");
