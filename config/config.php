<?php
    define('DOMAIN', 'http://localhost/evento');
    define('PATH', 'http://localhost/evento');
    define('NAME', 'Sampel - Eventos');
    define('PRODUCTION', false);

    define('DOMAIN_MAIN', 'https://sampel.com.br/eventos');
    define('PATH_MAIN', 'https://sampel.com.br/eventos');

    // CONFIGURAÇÕES DO BANCO ########################
    define('HOST_MAIN', '177.234.145.178');
    define('USER_MAIN', 'sampel_evento');
    define('PASS_MAIN', 'oG7ElprDRWDiRWNAEL');
    define('DBSA_MAIN', 'sampel_evento');

    // SMTP local do próprio servidor (mesmo padrão dos outros projetos que funcionam com Cloudflare):
    // conexão em localhost:25 sem TLS não passa por DNS externo, então o proxy não interfere.
    define('MAIL_HOST', 'localhost');
    define('MAIL_PORT', 25);
    define('MAIL_ENCRYPTION', 'none');
    define('MAIL_EMAIL', 'nao_responda@buscanarede.com.br');
    define('MAIL_USER', 'nao_responda@buscanarede.com.br');
    define('MAIL_PASSWORD', 'Close235689#');