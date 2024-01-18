<?php

namespace Agencia\Close\Controllers\Site\Recepcao;

use Agencia\Close\Models\Site\Recepcao;
use Agencia\Close\Controllers\Controller;

class RecepcaoController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);

        $this->render('pages/recepcao/index.twig', ['menu' => 'Recepção']);
    }

    public function confirmarPresenca($params)
    {
        $this->setParams($params);
        $update = new Recepcao();
        $update = $update->confirmarPresenca($params);
        if($update){
            echo '0';
        }else{
            echo '1';
        }
    }

}