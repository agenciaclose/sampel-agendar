<?php

// PAINEL CONFIGURAÇÃO DE EMAILS
$router->namespace("Agencia\Close\Controllers\Painel\Emailsconfig");
$router->get("/painel/configuracoes/emailsconfig", "ConfigemailsController:configemails", "configemails");
$router->post("/painel/configuracoes/emailsconfig/salvar", "ConfigemailsController:salvar", "salvar");