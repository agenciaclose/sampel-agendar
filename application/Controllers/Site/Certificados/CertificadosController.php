<?php

namespace Agencia\Close\Controllers\Site\Certificados; 

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\Certificados;

class CertificadosController extends Controller
{

    public function index()
    {
        $this->render('pages/certificados/index.twig', []);
    }

    public function naoencontrado()
    {
        $this->render('pages/certificados/notFound.twig', []);
    }

    public function emitirCheck($params)
    {

        $this->setParams($params);
        $emitirCheck = new Certificados();
        $emitirCheck = $emitirCheck->emitirCheck($params['cpf']);
        
        if($emitirCheck->getResult()[0]) {
            echo $emitirCheck->getResult()[0]['codigo'];
        }else{
            echo '';
        }
    }

}