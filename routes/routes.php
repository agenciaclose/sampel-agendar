<?php

// PAGE HOME
$router->namespace("Agencia\Close\Controllers\Site\Home");
$router->get("/", "HomeController:index", "home");

// SEND EMAIL
$router->get("/visita/emailEquipeTemplate/{visita_id}", "HomeController:emailEquipeTemplate");
$router->get("/visita/sendEmailEquipe/{visita_id}", "HomeController:sendEmailEquipe");
$router->get("/visita/sendEmailEstatisticas/{visita_id}", "HomeController:sendEmailEstatisticas");
$router->get("/visita/sendEmailCertificado/{visita_id}", "HomeController:sendEmailCertificado");

// PAGE AGENDAR
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
$router->post("/visita/inscricao/checkCadastroCampo", "VisitasController:checkCadastroCampo");

$router->get("/visita/inscricao/cpfautocomplete", "VisitasController:CPFAutoComplete");

$router->post("/visita/listaEquipesSave", "VisitasController:listaEquipesSave");

//SORTEIO
$router->post("/visita/sortear", "VisitasController:sortear");
$router->get("/visita/sorteados/{id}", "VisitasController:sorteados");

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

// FEEDBACK
$router->namespace("Agencia\Close\Controllers\Site\Feedback");
$router->get("/feedback/ver/{id}", "FeedbackController:feedbacksEstatisticas");

$router->namespace("Agencia\Close\Controllers\Site\Feedback");
$router->get("/visita/feedback/{cpf}/{id}", "FeedbackController:feedback");
$router->post("/visita/feedback/save", "FeedbackController:saveFeedback");

// LOAD LOGIN
$router->namespace("Agencia\Close\Controllers\Site\Login");
$router->get("/login", "LoginController:index", "login");
$router->post("/sign", "LoginController:sign");
$router->get("/logout", "LoginController:logout");
$router->get("/login/recover", "RecoverController:index");
$router->get("/login/recuperar-senha", "RecoverController:recover");
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

$router->get("/painel/feedback/lista", "FeedbackController:feedbacks");
$router->get("/painel/feedback/ver/{id}", "FeedbackController:feedbacksList");

// PAINEL ORÇAMENTOS
$router->namespace("Agencia\Close\Controllers\Painel\Orcamentos");
$router->get("/painel/orcamentos/lista", "OrcamentosPainelController:lista");
$router->get("/painel/orcamentos/editar/{id}", "OrcamentosPainelController:editar");

// PAINEL EQUIPE
$router->namespace("Agencia\Close\Controllers\Painel\Equipes");
$router->get("/painel/equipes", "EquipesController:equipes");
$router->get("/painel/equipe/cadastro", "EquipesController:cadastro");
$router->post("/painel/equipe/cadastro", "EquipesController:cadastroSave");
$router->get("/painel/equipe/editar/{id}", "EquipesController:editar");
$router->post("/painel/equipe/editarSave", "EquipesController:editarSave");

// ERROR
$router->group("error")->namespace("Agencia\Close\Controllers\Error");
$router->get("/{errorCode}", "ErrorController:show", 'error');

$router->dispatch();
if ($router->error()) {
    echo "Página não encontrada.";
}