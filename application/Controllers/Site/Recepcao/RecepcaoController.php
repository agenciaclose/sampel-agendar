<?php

namespace Agencia\Close\Controllers\Site\Recepcao;

use Agencia\Close\Controllers\Controller;

class RecepcaoController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);

        $this->render('pages/recepcao/index.twig', ['menu' => 'Recepção']);
    }

}