<?php

// PAINEL CONFIGURAÇÃO DE EMAILS
$router->namespace("Agencia\Close\Controllers\Painel\Config");
$router->get("/painel/configuracoes/emails-config", "ConfigEmailsController:index", "index");
$router->post("/painel/configuracoes/emails/salvar", "ConfigEmailsController:salvar", "salvar");