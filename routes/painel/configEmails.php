<?php

// PAINEL CONFIGURAÇÃO DE EMAILS
$router->namespace("Agencia\Close\Controllers\Painel\Config");
$router->get("/painel/configuracoes/emails-config", "ConfigemailsController:configEmails", "configEmails");
$router->post("/painel/configuracoes/emails/salvar", "ConfigemailsController:salvar", "salvar");