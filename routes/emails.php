<?php

// SEND EMAIL
$router->namespace("Agencia\Close\Controllers\Site\Home");
$router->get("/visita/emailEquipeTemplate/{visita_id}", "HomeController:emailEquipeTemplate");
$router->get("/visita/sendEmailEquipe/{visita_id}", "HomeController:sendEmailEquipe");
$router->get("/visita/sendEmailEstatisticas/{visita_id}", "HomeController:sendEmailEstatisticas");
$router->get("/visita/sendEmailCertificado/{visita_id}", "HomeController:sendEmailCertificado");

// SEND EMAIL EVENTO
$router->get("/eventos/sendEmailEquipeEventos/{evento_id}", "HomeController:sendEmailEquipeEventos");

// SEND EMAIL PRODUTOS
$router->namespace("Agencia\Close\Controllers\Site\Emails");
$router->post('/emails/novo-pedido', 'EmailController:enviarEmailNovoPedido');  
$router->post('/emails/status-pedido', 'EmailController:enviarEmailStatusPedido');
$router->post('/emails/estoque-minimo', 'EmailController:enviarEmailEstoqueMinimo');
$router->post('/emails/estoque-zerado', 'EmailController:enviarEmailEstoqueZerado');