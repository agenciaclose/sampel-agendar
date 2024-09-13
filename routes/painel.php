<?php

//PAINEL HOME
$router->namespace("Agencia\Close\Controllers\Painel\Home");
$router->get("/painel", "HomeController:index");
$router->get("/painel/dashboard-pedidos", "DashboardPedidosController:index");

// PAINEL LOGIN
$router->namespace("Agencia\Close\Controllers\Painel\LoginPainel");
$router->get("/painel/login", "LoginPainelController:index");
$router->get("/painel/login/recover", "LoginPainelController:recover");
$router->post("/painel/login/sign", "LoginPainelController:sign");
$router->get("/painel/login/logout", "LoginPainelController:logout");

// PAINEL CURRICULUM
$router->namespace("Agencia\Close\Controllers\Painel\Curriculum");
$router->get("/painel/curriculum", "CurriculumController:index");
$router->post("/painel/curriculum/account", "CurriculumController:accountUpdate");
$router->post("/painel/curriculum/security", "CurriculumController:accountSecurity");
$router->post("/painel/curriculum/terms", "CurriculumController:accountTerms");

// PAINEL VISTAS
$router->namespace("Agencia\Close\Controllers\Painel\Visitas");
$router->get("/painel/visitas", "VisitasController:index");
$router->get("/painel/visita/ver/{id}", "VisitasController:view");
$router->get("/painel/visita/status/aprovar/{id}", "VisitasController:aprovar");
$router->get("/painel/visita/status/reprovar/{id}", "VisitasController:reprovar");
$router->get("/painel/visita/excluir/{id}", "VisitasController:excluir");
$router->get("/painel/inscricao/edit/{codigo}", "VisitasController:editarInscricao");
$router->post("/painel/inscricao/edit/save", "VisitasController:editarInscricaoSave");

$router->get("/painel/inscricao/visitas/exportemails", "VisitasController:exportVisitantes");

// PAINEL VISITAS CRIAR
$router->get("/painel/visitas/add", "VisitasController:criar");
$router->get("/painel/visitas/editar/{id}", "VisitasController:editar");

// PAINEL VISITAS INSCRICAO
$router->namespace("Agencia\Close\Controllers\Painel\Inscricao");
$router->get("/painel/visita/inscricao/{id}", "InscricaoController:inscricao");
$router->post("/painel/visita/inscricao/cadastro", "InscricaoController:inscricaoCadastro");
$router->post("/painel/visita/inscricao/cadastro-qrcode", "InscricaoController:inscricaoCadastroQRcode");

// PAINEL VISITAS CONFIGURACOES
$router->namespace("Agencia\Close\Controllers\Painel\Config");
$router->get("/painel/visitas/config", "ConfigController:index");
$router->post("/painel/visitas/config/save", "ConfigController:save");
$router->post("/painel/visitas/config/save/regras", "ConfigController:saveRegras");
$router->post("/painel/visitas/config/save/opcional", "ConfigController:saveOpcional");
$router->post("/painel/visitas/config/edit/opcional", "ConfigController:editOpcional");
$router->post("/painel/visitas/config/delete/opcional", "ConfigController:deleteOpcional");

// PAINEL PALESTRAS
$router->namespace("Agencia\Close\Controllers\Painel\Palestras");
$router->get("/painel/palestras", "PalestrasController:index", "index");
$router->get("/painel/palestra/ver/{id}", "PalestrasController:view", "view");
$router->get("/painel/palestra/ver/{id}/{id_inscricao}", "PalestrasController:viewEdit");

$router->get("/painel/inscricao/palestras/exportemails", "PalestrasController:exportPalestrasParticipantes");

// PAINEL PALESTRAS CRIAR
$router->get("/painel/palestras/add", "PalestrasController:criar");
$router->get("/painel/palestras/editar/{id}", "PalestrasController:editar");
$router->post("/painel/palestras/cadastro", "PalestrasController:SaveCadastro");
$router->post("/painel/palestras/editar", "PalestrasController:SaveEditar");
$router->post("/painel/palestras/excluir", "PalestrasController:palestraExcluir");

// PAINEL PALESTRAS PARTICIPANTES
$router->post("/painel/palestras/participante/cadastro", "PalestrasController:SaveCadastroParticipante");
$router->post("/painel/palestras/participante/editar", "PalestrasController:SaveEditarParticipante");
$router->post("/painel/palestras/participante/excluir", "PalestrasController:excluirParticipante");
$router->post("/painel/palestras/participante/importar", "PalestrasController:importar");

// PAINEL FEEDBACK
$router->namespace("Agencia\Close\Controllers\Painel\Feedback");
$router->get("/painel/feedback/perguntas", "FeedbackController:perguntas");
$router->post("/painel/feedback/perguntas/save", "FeedbackController:savePerguntas");
$router->get("/painel/feedback/perguntas/excluir/{id}", "FeedbackController:excluirPergunta");
$router->post("/painel/feedback/perguntas/order", "FeedbackController:ordernarPergunta");

$router->get("/painel/feedback/lista", "FeedbackController:feedbacks");
$router->get("/painel/feedback/ver/{id}", "FeedbackController:feedbacksList");

// PAINEL EQUIPE
$router->namespace("Agencia\Close\Controllers\Painel\Equipes");
$router->get("/painel/equipes", "EquipesController:equipes");
$router->get("/painel/equipe/cadastro", "EquipesController:cadastro");
$router->post("/painel/equipe/cadastro", "EquipesController:cadastroSave");
$router->get("/painel/equipe/editar/{id}", "EquipesController:editar");
$router->post("/painel/equipe/editarSave", "EquipesController:editarSave");
$router->post("/painel/equipe/status", "EquipesController:statusEquipe");

// PAINEL CARGOS
$router->namespace("Agencia\Close\Controllers\Painel\Cargos");
$router->get("/painel/equipes/cargos", "CargosController:lista");
$router->get("/painel/equipes/cargos/add", "CargosController:addCargo");
$router->get("/painel/equipes/cargos/edit/{id}", "CargosController:editCargo");
$router->post("/painel/equipes/cargos/add/save", "CargosController:addCargoSave");
$router->post("/painel/equipes/cargos/edit/save", "CargosController:editCargoSave");
$router->post("/painel/equipes/cargos/remove", "CargosController:deleteCargo");