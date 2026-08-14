<?php

namespace Agencia\Close\Controllers\Site\Agendar;

use Agencia\Close\Models\Site\Agendar;
use Agencia\Close\Models\Site\Visitas;
use Agencia\Close\Adapters\EmailAdapter;
use Agencia\Close\Controllers\Controller;

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
            if(isset($this->params['notification_sand'])){
                $this->sendEmailNovoEvento($this->params['id']);
            }
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

    public function checkEventsConcluido()
    {
        $editar = new Agendar();
        $editar = $editar->listcheckEventsConcluido();
    }
    
    public function sendEmailNovoEvento($id)
    {
        $visita = new Visitas();
        $visita = $visita->listarVisitaID($id)->getResult()[0];

        $equipe = new Visitas();
        $equipe = $equipe->listaEquipesVisita($id)->getResult() ?: [];

        $dataVisita = new \DateTime($visita['data_visita']);
        $dataFormatada = $dataVisita->format('d/m/Y');

        $email = null;
        foreach($equipe as $lista){
            if (empty($lista['email'])) {
                continue;
            }
            $data = ['visita' => $visita];
            $email = new EmailAdapter();
            $email->setSubject('Sampel - Eventos - Informações do evento: '. $dataFormatada);
            $email->setBody('components/email/emailNovoEvento.twig', $data);
            $email->addAddress($lista['email']);
            $email->send('Email enviado com sucesso');

        }
        if ($email) {
            $email->getResult();
        }
    }
}