<?php

// MAILCHIMP INTEGRATION - FOCUS ON "SAMPEL - EVENTOS" TAG
$router->namespace("Agencia\Close\Controllers\Site\Mailchimp");

// Processar novo contato e adicionar etiqueta "Sampel - Eventos"
$router->post("/mailchimp/processar-contato", "MailchimpController:processarContato", "mailchimp.processarContato");

// Listar contatos com etiqueta "Sampel - Eventos"
$router->get("/mailchimp/listar-contatos", "MailchimpController:listarContatos", "mailchimp.listarContatos");

// Adicionar etiqueta "Sampel - Eventos" a um contato existente
$router->post("/mailchimp/adicionar-etiqueta", "MailchimpController:adicionarEtiqueta", "mailchimp.adicionarEtiqueta");

// Remover etiqueta "Sampel - Eventos" de um contato
$router->post("/mailchimp/remover-etiqueta", "MailchimpController:removerEtiqueta", "mailchimp.removerEtiqueta");

// Obter estatísticas dos contatos com etiqueta "Sampel - Eventos"
$router->get("/mailchimp/estatisticas", "MailchimpController:obterEstatisticas", "mailchimp.estatisticas");

// Exportar contatos com etiqueta "Sampel - Eventos" para CSV
$router->get("/mailchimp/exportar-csv", "MailchimpController:exportarCSV", "mailchimp.exportarCSV");

// Receber contato via API externa
$router->post("/mailchimp/api-contato", "MailchimpController:receberContatoAPI", "mailchimp.apiContato");

// Verificar status da API do Mailchimp
$router->get("/mailchimp/status-api", "MailchimpController:verificarStatusAPI", "mailchimp.statusAPI");
