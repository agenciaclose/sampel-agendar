<?php

namespace Agencia\Close\Controllers\Site\CertificadosPalestras; 

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\CertificadosPalestras;

class CertificadosPalestrasController extends Controller
{

    public function palestra()
    {
        $this->render('pages/palestras/certificados/check.twig', []);
    }

    public function naoencontrado()
    {
        $this->render('pages/certificados/notFound.twig', []);
    }

    public function emitirCheckPalestra($params)
    {

        $this->setParams($params);
        $emitirCheck = new CertificadosPalestras();
        $emitirCheck = $emitirCheck->emitirCheckPalestra($params['cpf']);
        
        if($emitirCheck->getResult()) {

            $update = new CertificadosPalestras();
            $update->certificadoUpdate($emitirCheck->getResult()[0]['codigo']);

            echo $emitirCheck->getResult()[0]['codigo'];
        }else{
            echo '';
        }
    }

}