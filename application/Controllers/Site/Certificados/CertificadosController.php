<?php

namespace Agencia\Close\Controllers\Site\Certificados; 

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\Certificados;

class CertificadosController extends Controller
{

    public function visita()
    {
        $this->render('pages/certificados/visita.twig', []);
    }

    public function naoencontrado()
    {
        $this->render('pages/certificados/notFound.twig', []);
    }

    public function emitirCheckVisita($params)
    {

        $this->setParams($params);
        $emitirCheck = new Certificados();
        $emitirCheck = $emitirCheck->emitirCheckVisita($params['cpf']);
        
        if($emitirCheck->getResult()) {

            $update = new Certificados();
            $update->certificadoUpdate($emitirCheck->getResult()[0]['codigo']);

            echo $emitirCheck->getResult()[0]['codigo'];
        }else{
            echo '';
        }
    }

}