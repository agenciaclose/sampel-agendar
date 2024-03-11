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

    define('MAIL_HOST', 'mail.grupoestevaocaputto.com.br');
    define('MAIL_EMAIL', 'mkt@grupoestevaocaputto.com.br');
    define('MAIL_USER', 'mkt@grupoestevaocaputto.com.br');
    define('MAIL_PASSWORD', '@Sampel2310#');

    if(PRODUCTION === true) {
        define('TJ_TOKEN', '');
        define('TJ_URL_API', 'http://www.tjsp.jus.br/integracoesadm/pro/spd-backend/integracao/v1');
    }else{
        define('TJ_TOKEN', 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJhdWQiOlsic3BkLWJhY2tlbmQiXSwic2NvcGUiOlsid3JpdGUiLCJyZWFkIl0sImV4cCI6MTcxMDA5ODg3MiwiYXV0aG9yaXRpZXMiOlsiUEVESURPX0FKVVNURV9DT05TVUxUQSIsIlBFRElET19SRVBPU0lDQU9fQ09OU1VMVEEiLCJDT05GSVJNQUNBT19SRUNFQklNRU5UT19DUkVBVEUiXSwianRpIjoiZDJiNGFmYWUtZTljYi00ZmM0LWI0MzYtZjgyOWM4MjU0ODFhIiwiY2xpZW50X2lkIjoiTUJTIn0.RWpYy-_QCI-V2VF_FOO0sSC1X8Fn-Y9I7YxzMIZaIVavA8h_0uhMVStJsauiFpD_hZD5Eey25Eo0lsmh8tcq_RFheeNmdzAhdjNgPPzLKVyfmjyDhYVTpaQh7_6WRmApxUFjg00L5CYzMYwE5lGO3c4Z-SXxVFabsWYphFvnkONMH8R3bryTD4XiKTeS1xIKJilvwMxmx0_FzBskOJt3njejeArwZLvQu36SJvvllwq3Sh5GY3SeFvCEn4X_4a7tl9P0E6F2O4ftP1C7_o59v4G__XyDiKfTKUtlGgMW0DNBtuPehrSVh1-3x6NFPL6kJMFYbbSw-oL73QRlSTkBXg');
        define('TJ_URL_API', 'http://www.tjsp.jus.br/integracoesadm/hml/spd-backend/integracao/v1');
    }
