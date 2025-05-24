<?php

// PAINEL CONFIGURAÇÃO DE EMAILS
$router->namespace("Agencia\Close\Controllers\Painel\Config");
$router->get("/painel/configuracoes/emails", "EmailsController:index");
$router->post("/painel/configuracoes/emails/salvar", "EmailsController:salvar"); 