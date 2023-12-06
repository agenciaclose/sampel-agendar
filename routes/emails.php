<?php

// SEND EMAIL
$router->get("/visita/emailEquipeTemplate/{visita_id}", "HomeController:emailEquipeTemplate");
$router->get("/visita/sendEmailEquipe/{visita_id}", "HomeController:sendEmailEquipe");
$router->get("/visita/sendEmailEstatisticas/{visita_id}", "HomeController:sendEmailEstatisticas");
$router->get("/visita/sendEmailCertificado/{visita_id}", "HomeController:sendEmailCertificado");