<?php

// CADASTRO
$router->namespace("Agencia\Close\Controllers\Site\Palestras");
$router->get("/palestras", "PalestrasController:index");
$router->get("/palestras/cadastro", "PalestrasController:cadastro");
$router->post("/palestras/cadastro/salvar", "PalestrasController:palestraSave");
$router->post("/palestras/editar/salvar", "PalestrasController:palestraEditar");
$router->post("/palestras/cadastro/save-qrcode-feedback", "PalestrasController:palestraQRcodeFeedbackSave");
$router->post("/palestras/cadastro/save-qrcode", "PalestrasController:palestraQRcodeSave");

//INSCRICAO
$router->get("/palestras/inscricao/{id}", "PalestrasController:inscricao");
$router->get("/palestras/inscricao/{id}/{inscricao}", "PalestrasController:inscricao");
$router->get("/palestras/qrcode/{id}", "PalestrasController:palestraGetQRcode");

$router->get("/palestras/inscricao/lista/{id}", "PalestrasController:palestraInscritos");

$router->post("/palestras/inscricao/cadastro", "PalestrasController:inscricaoCadastro");
$router->post("/palestras/inscricao/cadastro-qrcode", "PalestrasController:inscricaoCadastroQRcode");
$router->post("/palestras/inscricao/checkCadastroCampo", "PalestrasController:checkCadastroCampo");

$router->get("/palestras/etiqueta/{codigo}", "PalestrasController:printEtiqueta");

//SORTEIO
$router->post("/palestras/sortear", "PalestrasController:sortear");
$router->get("/palestras/sorteados/{id}", "PalestrasController:sorteados");

// FEEDBACK
$router->namespace("Agencia\Close\Controllers\Site\FeedbackPalestras");
$router->get("/palestras/feedback/{cpf}/{id}", "FeedbackPalestrasController:feedback");
$router->get("/palestras/feedback/{id}", "FeedbackPalestrasController:feedback");
$router->post("/palestras/feedback/checkInscricao", "FeedbackPalestrasController:checkInscricao");
$router->post("/palestras/feedback/save", "FeedbackPalestrasController:saveFeedback");

// CERTIFICADOS
$router->namespace("Agencia\Close\Controllers\Site\CertificadosPalestras");
$router->get("/certificados/palestras", "CertificadosPalestrasController:palestra");
$router->post("/certificados/palestras/emitirCheckVisita", "CertificadosPalestrasController:emitirCheckPalestra");