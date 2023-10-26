<?php

use CoffeeCode\Router\Router;

$router = new Router(DOMAIN);

// PAGE HOME
$router->namespace("Agencia\Close\Controllers\Site\Home");
$router->get("/", "HomeController:index", "home");

// PAGE AGENDAR
$router->namespace("Agencia\Close\Controllers\Site\Agendar");
$router->get("/agendar", "AgendarController:index", "index");
$router->post("/agendar/cadastro", "AgendarController:cadastro", "cadastro");
$router->post("/agendar/editar", "AgendarController:editar", "editar");
$router->get("/agendar/checkCloseEventsClose", "AgendarController:checkCloseEventsClose", "checkCloseEventsClose");

// PAGE AGENDAR
$router->namespace("Agencia\Close\Controllers\Site\Visitas");
$router->get("/visitas", "VisitasController:visitas", "visitas");
$router->get("/agendamentos", "VisitasController:agendamentos", "agendamentos");
$router->get("/visita/lista/{id}", "VisitasController:lista", "lista");
$router->get("/visita/inscricao/{id}", "VisitasController:inscricao", "inscricao");
$router->get("/visita/inscricao/{id}/{inscricao}", "VisitasController:inscricao", "inscricao");
$router->post("/visita/inscricao/cadastro", "VisitasController:inscricaoCadastro", "inscricaoCadastro");
$router->post("/visita/inscricao/cadastro-qrcode", "VisitasController:inscricaoCadastroQRcode", "inscricaoCadastroQRcode");
$router->get("/etiqueta/{codigo}", "VisitasController:printEtiqueta", "printEtiqueta");
$router->post("/visita/inscricao/checkCadastroCampo", "VisitasController:checkCadastroCampo", "checkCadastroCampo");

$router->namespace("Agencia\Close\Controllers\Site\Feedback");
$router->get("/visita/feedback/{cpf}/{id}", "FeedbackController:pergunta", "pergunta");

// LOAD LOGIN
$router->namespace("Agencia\Close\Controllers\Site\Login");
$router->get("/login", "LoginController:index", "login");
$router->post("/sign", "LoginController:sign");
$router->get("/logout", "LoginController:logout");
$router->get("/login/recover", "RecoverController:index");
$router->get("/login/recuperar-senha", "RecoverController:recover");
$router->get("/cadastro", "RegisterController:index");
$router->get("/find-cnpj", "RegisterController:cnpj");
$router->post("/cadastro/create-client", "RegisterController:createClient");

//MINHA CONTA
$router->namespace("Agencia\Close\Controllers\Site\MinhaConta");
$router->get("/minha-conta", "MinhaContaController:minhaconta");
$router->get("/minha-conta/orders", "MinhaContaController:orders");
$router->get("/minha-conta/orders/{id}", "MinhaContaController:ordersView");
$router->get("/minha-conta/schedules", "MinhaContaController:schedules");
$router->get("/minha-conta/schedules/{id}", "MinhaContaController:schedulesView");
$router->get("/minha-conta/downloads", "MinhaContaController:downloads");
$router->get("/minha-conta/edit-account", "MinhaContaController:editaccount");

//PAINEL HOME
$router->namespace("Agencia\Close\Controllers\Painel\Home");
$router->get("/painel", "HomeController:index");

// PAINEL LOGIN
$router->namespace("Agencia\Close\Controllers\Painel\LoginPainel");
$router->get("/painel/login", "LoginPainelController:index");
$router->get("/painel/login/recover", "LoginPainelController:recover");
$router->post("/painel/login/sign", "LoginPainelController:sign");
$router->get("/painel/login/logout", "LoginPainelController:logout");

// PAINEL CURRICULUM
$router->namespace("Agencia\Close\Controllers\Painel\Curriculum");
$router->get("/painel/curriculum", "CurriculumController:index", "index");
$router->post("/painel/curriculum/account", "CurriculumController:accountUpdate");
$router->post("/painel/curriculum/security", "CurriculumController:accountSecurity");
$router->post("/painel/curriculum/terms", "CurriculumController:accountTerms");

// PAINEL VISTAS
$router->namespace("Agencia\Close\Controllers\Painel\Visitas");
$router->get("/painel/visitas", "VisitasController:index", "index");
$router->get("/painel/visita/ver/{id}", "VisitasController:view", "view");
$router->get("/painel/visita/status/aprovar/{id}", "VisitasController:aprovar");
$router->get("/painel/visita/status/reprovar/{id}", "VisitasController:reprovar");
$router->get("/painel/visita/excluir/{id}", "VisitasController:excluir");

// PAINEL VISITAS CRIAR
$router->get("/painel/visitas/add", "VisitasController:criar");
$router->get("/painel/visitas/editar/{id}", "VisitasController:editar");

// PAINEL VISITAS INSCRICAO
$router->namespace("Agencia\Close\Controllers\Painel\Inscricao");
$router->get("/painel/visita/inscricao/{id}", "InscricaoController:inscricao", "inscricao");
$router->post("/painel/visita/inscricao/cadastro", "InscricaoController:inscricaoCadastro", "inscricaoCadastro");
$router->post("/painel/visita/inscricao/cadastro-qrcode", "InscricaoController:inscricaoCadastroQRcode", "inscricaoCadastroQRcode");

// PAINEL VISITAS CONFIGURACOES
$router->namespace("Agencia\Close\Controllers\Painel\Config");
$router->get("/painel/visitas/config", "ConfigController:index");
$router->post("/painel/visitas/config/save", "ConfigController:save");
$router->post("/painel/visitas/config/save/regras", "ConfigController:saveRegras");
$router->post("/painel/visitas/config/save/motivo", "ConfigController:saveMotivo");
$router->post("/painel/visitas/config/edit/motivo", "ConfigController:editMotivo");
$router->post("/painel/visitas/config/delete/motivo", "ConfigController:deleteMotivo");

// PAINEL PALESTRAS
$router->namespace("Agencia\Close\Controllers\Painel\Palestras");
$router->get("/painel/palestras", "PalestrasController:index", "index");
$router->get("/painel/palestra/ver/{id}", "PalestrasController:view", "view");
$router->get("/painel/palestra/ver/{id}/{id_inscricao}", "PalestrasController:viewEdit", "viewEdit");

// PAINEL PALESTRAS CRIAR
$router->get("/painel/palestras/add", "PalestrasController:criar");
$router->get("/painel/palestras/editar/{id}", "PalestrasController:editar");
$router->post("/painel/palestras/cadastro", "PalestrasController:SaveCadastro", "SaveCadastro");
$router->post("/painel/palestras/editar", "PalestrasController:SaveEditar", "SaveEditar");
$router->post("/painel/palestras/excluir", "PalestrasController:palestraExcluir", "palestraExcluir");

// PAINEL PALESTRAS PARTICIPANTES
$router->post("/painel/palestras/participante/cadastro", "PalestrasController:SaveCadastroParticipante", "SaveCadastroParticipante");
$router->post("/painel/palestras/participante/editar", "PalestrasController:SaveEditarParticipante", "SaveEditarParticipante");
$router->post("/painel/palestras/participante/excluir", "PalestrasController:excluirParticipante", "excluirParticipante");
$router->post("/painel/palestras/participante/importar", "PalestrasController:importar", "importar");


// ERROR
$router->group("error")->namespace("Agencia\Close\Controllers\Error");
$router->get("/{errorCode}", "ErrorController:show", 'error');

$router->dispatch();
if ($router->error()) {
    echo "Página não encontrada.";
}