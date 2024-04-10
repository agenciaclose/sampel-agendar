<?php

// PAGE HOME
$router->namespace("Agencia\Close\Controllers\Site\Home");
$router->get("/", "HomeController:index", "home");

// SEND EMAIL
$router->get("/visita/emailEquipeTemplate/{visita_id}", "HomeController:emailEquipeTemplate");
$router->get("/visita/sendEmailEquipe/{visita_id}", "HomeController:sendEmailEquipe");
$router->get("/visita/sendEmailEstatisticas/{visita_id}", "HomeController:sendEmailEstatisticas");
$router->get("/visita/sendEmailCertificado/{visita_id}", "HomeController:sendEmailCertificado");
$router->get("/visita/emailNovoEvento/{visita_id}", "HomeController:sendEmailNovoEvento");

// MINHAS INSCRICOES
$router->namespace("Agencia\Close\Controllers\Site\MinhasInscricoes");
$router->get("/minhas-inscricoes", "MinhasInscricoesController:check");
$router->get("/minhas-inscricoes/lista", "MinhasInscricoesController:lista");
$router->post("/minhas-inscricoes/checkInscricoes", "MinhasInscricoesController:checkInscricoes");

// CERTIFICADOS
$router->namespace("Agencia\Close\Controllers\Site\Certificados");
$router->get("/certificados/visita", "CertificadosController:visita");
$router->post("/certificados/emitirCheckVisita", "CertificadosController:emitirCheckVisita");
$router->get("/certificados/404", "CertificadosController:naoencontrado");

// LOAD LOGIN
$router->namespace("Agencia\Close\Controllers\Site\Login");
$router->get("/login", "LoginController:index", "login");
$router->post("/sign", "LoginController:sign");
$router->get("/logout", "LoginController:logout");
$router->get("/login/recover", "RecoverController:index");
$router->post("/login/recover/save", "RecoverController:privateCodeSave");
$router->post("/login/senha/senhaSave", "RecoverController:senhaSave");
$router->get("/login/senha/{codigo_privado}", "RecoverController:senha");
$router->post("/login/senha/senhaSave", "RecoverController:senhaSave");
#$router->get("/login/recuperar-senha", "RecoverController:recover");
// $router->get("/cadastro", "RegisterController:index");
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

$router->namespace("Agencia\Close\Controllers\Site\Recepcao");
$router->get("/visita/recepcao/{id}", "RecepcaoController:visita");
$router->post("/visita/recepcao/confirmar", "RecepcaoController:confirmarPresenca");

$router->get("/palestras/recepcao/{id}", "RecepcaoController:palestra");
$router->post("/palestras/recepcao/confirmar", "RecepcaoController:confirmarPresencaPalestra");

// PAINEL EQUIPE
$router->namespace("Agencia\Close\Controllers\Site\Equipes");
$router->get("/equipes", "EquipesController:equipes");
$router->get("/equipe/cadastro", "EquipesController:cadastro");
$router->get("/equipe/editar/{id}", "EquipesController:editar");