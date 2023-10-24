<?php

namespace Agencia\Close\Controllers\Site\Agendar;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\Agendar;

class AgendarController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);

        $estados = new Agendar();
        $estados = $estados->getEstados()->getResult();

        $configuracoes = new Agendar();
        $configuracoes = $configuracoes->getConfiguracoes()->getResult()[0];
        
        $motivos = new Agendar();
        $motivos = $motivos->getMotivos()->getResult();

        $this->render('pages/agendar/agendar.twig', ['menu' => 'agendar', 'estados' => $estados, 'config' => $configuracoes, 'motivos' => $motivos]);
    }

    public function cadastro($params)
    {
    	$this->setParams($params);
        $save = new Agendar();
        $save = $save->saveCadastro($this->params);
        $last = new Agendar();
        $last = $last->getLast()->getResult()[0];
        if ($save) {
            echo $last['id'];
        } else {
            echo '0';
        }
    }

    public function editar($params)
    {
        $this->setParams($params);
        $editar = new Agendar();
        $editar = $editar->saveEditar($this->params);
        if ($editar) {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function checkCloseEventsClose()
    {
        $editar = new Agendar();
        $editar = $editar->listCheckEventsClose();
    }

}