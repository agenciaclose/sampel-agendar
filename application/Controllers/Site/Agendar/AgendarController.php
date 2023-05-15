<?php

namespace Agencia\Close\Controllers\Site\Agendar;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\Agendar;

class AgendarController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);
        $this->render('pages/agendar/agendar.twig', ['menu' => 'agendar']);
    }

    public function cadastro($params)
    {
    	$this->setParams($params);
        $save = new Agendar();
        $save = $save->saveCadastro($this->params);
        if ($save) {
            echo '1';
        } else {
            echo '0';
        }
    }
}