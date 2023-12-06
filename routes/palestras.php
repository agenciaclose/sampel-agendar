<?php

// CADASTRO
$router->namespace("Agencia\Close\Controllers\Site\Palestras");
$router->get("/palestras", "PalestrasController:index");
$router->get("/palestras/cadastro", "PalestrasController:cadastro");
$router->post("/palestras/cadastro/salvar", "PalestrasController:palestraSave");
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