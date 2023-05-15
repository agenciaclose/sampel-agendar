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
$router->get("/painel/manuais-consultor", "HomeController:manuaisConsultor", "manuaisConsultor");

// PAINEL LOGIN
$router->namespace("Agencia\Close\Controllers\Painel\LoginPainel");
$router->get("/painel/login", "LoginPainelController:index");
$router->get("/painel/login/recover", "LoginPainelController:recover");
$router->post("/painel/login/sign", "LoginPainelController:sign");
$router->get("/painel/login/logout", "LoginPainelController:logout");

// PAINEL SAQUES
$router->namespace("Agencia\Close\Controllers\Painel\Saques");
$router->get("/painel/saques", "SaquesController:index", "index");
$router->get("/painel/saques/conta/add-saque", "SaquesController:SaqueModal", "SaqueModal");
$router->post("/painel/saques/conta/check", "SaquesController:CarteiraCheck", "CarteiraCheck");
$router->post("/painel/saques/conta/save-saque", "SaquesController:SaveSaque", "SaveSaque");

$router->get("/painel/saques/conta/criar", "SaquesController:ContaCriar", "ContaCriar");
$router->get("/painel/saques/conta/editar/{id}", "SaquesController:ContaEditar", "ContaEditar");
$router->post("/painel/saques/conta/salvar", "SaquesController:ContaSalvar", "ContaSalvar");

// PAINEL CURRICULUM
$router->namespace("Agencia\Close\Controllers\Painel\Curriculum");
$router->get("/painel/curriculum", "CurriculumController:index", "index");
$router->post("/painel/curriculum/account", "CurriculumController:accountUpdate", "accountUpdate");
$router->post("/painel/curriculum/security", "CurriculumController:accountSecurity", "accountSecurity");
$router->post("/painel/curriculum/terms", "CurriculumController:accountTerms", "accountTerms");

// PAINEL SERVICOS
$router->namespace("Agencia\Close\Controllers\Painel\Servicos");
$router->get("/painel/servicos", "ServicosController:index", "index");
$router->get("/painel/servicos/add", "ServicosController:add", "add");
$router->get("/painel/servicos/edit/{id}", "ServicosController:edit", "edit");
$router->get("/painel/servicos/horarios", "ServicosController:horarios", "horarios");
$router->post("/painel/servicos/horarios/save", "ServicosController:horariosSave", "horariosSave");
$router->post("/painel/servicos/save", "ServicosController:save", "save");
$router->get("/painel/servicos/agendamentos", "ServicosController:agendamentos", "agendamentos");
$router->post("/painel/servicos/agendamentos/link", "ServicosController:linkSave", "linkSave");
$router->get("/painel/servicos/agendamentos/modal-link", "ServicosController:modalLink", "modalLink");
$router->get("/painel/servicos/agendamento/{id}", "ServicosController:agendamentoView", "agendamentoView");
$router->get("/painel/servicos/agendamentos/agendamento-concluido/{item}", "ServicosController:agendamentoConcluido", "agendamentoConcluido");

// PAINEL PRODUTOS
$router->namespace("Agencia\Close\Controllers\Painel\Produtos");
$router->get("/painel/produtos", "ProdutosController:index", "index");
$router->get("/painel/produtos/add", "ProdutosController:add", "add");
$router->get("/painel/produtos/edit/{id}", "ProdutosController:edit", "edit");
$router->get("/painel/produtos/vendas", "ProdutosController:vendas", "vendas");
$router->post("/painel/produtos/save_draft", "ProdutosController:save_draft", "save_draft");
$router->post("/painel/produtos/update", "ProdutosController:update", "update");
$router->post("/painel/produtos/excluir", "ProdutosController:excluir", "excluir");
$router->post("/painel/produtos/delete-file", "ProdutosController:deleteFile", "deleteFile");
$router->get("/painel/produtos/vendas", "ProdutosController:vendas", "vendas");
$router->get("/painel/produtos/venda/{id}", "ProdutosController:vendaView", "vendaView");

$router->namespace("Agencia\Close\Controllers\Painel\Produtos");
$router->get("/painel/produtos/categorias", "CategoriasController:index", "index");


// PAINEL ADMIN

// ADMIN USUARIOS
$router->namespace("Agencia\Close\Controllers\Admin\Usuarios");
$router->get("/painel/admin/usuarios", "UsuariosController:list", "list");
$router->post("/painel/admin/usuarios/status", "UsuariosController:status", "status");
$router->get("/painel/admin/usuarios/editar/{id}", "UsuariosController:editar", "editar");
$router->get("/painel/admin/manuais-admin", "UsuariosController:manuaisAdmin", "manuaisAdmin");

// SAQUES
$router->namespace("Agencia\Close\Controllers\Admin\Saques");
$router->get("/painel/admin/saques", "SaquesController:index", "index");
$router->get("/painel/admin/saques/verificarModal/{sampel_user_id}", "SaquesController:verificarModal", "verificarModal");
$router->get("/painel/admin/saques/statusModal/{id}", "SaquesController:statusModal", "statusModal");
$router->post("/painel/admin/saques/statusSave", "SaquesController:statusSave", "statusSave");

// ADMIN TIPOS SERVICOS
$router->namespace("Agencia\Close\Controllers\Admin\Servicos");
$router->get("/painel/admin/servicos/tipos", "TiposController:index", "index");
$router->get("/painel/admin/servicos/tipos/{id}", "TiposController:edit", "edit");
$router->post("/painel/admin/servicos/tipos/save", "TiposController:save", "save");
//AGENDAMENTOS
$router->get("/painel/admin/servicos/agendamentos", "ServicosController:agendamentos", "agendamentos");
$router->get("/painel/admin/servicos/agendamento/{id}", "ServicosController:agendamentoView", "agendamentoView");

//ADMIN PRODUTOS
$router->namespace("Agencia\Close\Controllers\Admin\Produtos");
$router->get("/painel/admin/produtos/vendas", "ProdutosController:vendas", "vendas");
$router->get("/painel/admin/produtos/venda/{id}", "ProdutosController:vendaView", "vendaView");



// ADMIN CATEGORIAS SERVICOS
$router->namespace("Agencia\Close\Controllers\Admin\Servicos");
$router->get("/painel/admin/servicos/categorias", "CategoriasController:index", "index");
$router->get("/painel/admin/servicos/categorias/{id}", "CategoriasController:edit", "edit");
$router->post("/painel/admin/servicos/categorias/save", "CategoriasController:save", "save");


// ERROR
$router->group("error")->namespace("Agencia\Close\Controllers\Error");
$router->get("/{errorCode}", "ErrorController:show", 'error');

$router->dispatch();
if ($router->error()) {
    echo "Página não encontrada.";
}