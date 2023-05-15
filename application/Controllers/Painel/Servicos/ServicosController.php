<?php

namespace Agencia\Close\Controllers\Painel\Servicos;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\Upload;
use Agencia\Close\Models\Painel\ServicosPainel;

class ServicosController extends Controller
{
    public $dias = [];
    public $dias_completos = [];
    public $horarios = [];

    public function index($params)
    {
        $this->setParams($params);
        $servicos = new ServicosPainel();
        $servicos_lista = $servicos->getServicos()->getResult();
        $this->render('painel/pages/servicos/lista.twig', ['menu' => 'servicos', 'servicos' => $servicos_lista]);
    }


    public function add($params)
    {
        $this->setParams($params);
        
        $tipos = new ServicosPainel();
        $tipo_lista = $tipos->getTipos()->getResult();
        $categorias = new ServicosPainel();
        $categorias_lista = $categorias->getCategorias()->getResult();

        $this->render('painel/pages/servicos/form.twig', ['menu' => 'servicos', 'tipos' => $tipo_lista, 'categorias' => $categorias_lista]);
    }

    public function edit($params)
    {
        $this->setParams($params);

        $servicos = new ServicosPainel();
        $servicos_lista = $servicos->getServicoID($this->params['id'])->getResult()[0];

        $tipos = new ServicosPainel();
        $tipo_lista = $tipos->getTipos()->getResult();
        $categorias = new ServicosPainel();
        $categorias_lista = $categorias->getCategorias()->getResult();

        $this->render('painel/pages/servicos/form.twig', ['menu' => 'servicos', 'servicos' => $servicos_lista, 'tipos' => $tipo_lista, 'categorias' => $categorias_lista]);
    }

    public function horarios($params)
    {
        $this->setParams($params);

        $dias_user = new ServicosPainel();
        $dias_user = $dias_user->getHorarios();

        if($dias_user->getResult()){
            $this->dias = $dias_user->getResult();
        }else{
            $this->dias = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
        }

        $this->horarios = array('00:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00');
        $this->render('painel/pages/servicos/horarios.twig', ['menu' => 'servicos', 'dias' => $this->dias, 'horarios' => $this->horarios]);


    }

    public function save($params)
    {
        $this->setParams($params);
        $servicos = new ServicosPainel();

        if($_FILES['imagem']['tmp_name']) {
            $upload = new Upload;
            $upload->Image($_FILES['imagem'], microtime(), null, 'servicos/'.$_SESSION['sampel_user_slug']);
        }

        if(isset($upload) && $upload->getResult()) {
            $this->params['imagem'] = $upload->getResult();
        }

        if($this->params['id']) {
            $result = $servicos->updateServicos($this->params);
            if($result->getResult()) {
                $id = $this->params['id'];
            }
        } else {
            $result = $servicos->createServicos($this->params);
            $id = $result->getResult();
        }
        echo $id;
    }


    public function horariosSave($params)
    {
        $this->setParams($params);
        $horarios = new ServicosPainel();
        if($this->params['id']){
            $horarios->updateHorarios($this->params);
        }else{
            $horarios->createHorarios($this->params);
        }
        echo '1';
    }

    public function agendamentos($params)
    {
        $this->setParams($params);
        $agendamentos = new ServicosPainel();
        $agendamentos_lista = $agendamentos->getAgendamentos()->getResult();
        $this->render('painel/pages/servicos/agendamentos.twig', ['menu' => 'agendamentos', 'agendamentos' => $agendamentos_lista]);
    }

    public function agendamentoView($params)
    {
        $this->setParams($params);
        $venda = new ServicosPainel();
        $venda = $venda->getAgendamentoView($_SESSION['sampel_user_id'], $this->params['id'])->getResult()[0];

        $horarios = new ServicosPainel();
        $horarios = $horarios->getHorariosAgendamento($this->params['id'])->getResult();

        $notas = new ServicosPainel();
        $notas = $notas->getNotasAgendamento($this->params['id'])->getResult();

        $this->render('painel/pages/servicos/view.twig', ['menu' => 'agendamentos', 'venda' => $venda, 'horarios' => $horarios, 'notas' => $notas]);
    }

    public function agendamentoConcluido($params)
    {
        $this->setParams($params);

        $venda = new ServicosPainel();
        $venda = $venda->getAgendamentoView($_SESSION['sampel_user_id'], $this->params['item'])->getResult()[0];

        $horarios = new ServicosPainel();
        $horarios = $horarios->getHorariosAgendamento($this->params['item'])->getResult()[0];

        $date = $horarios['dia_agendamento'];
        $date1 = str_replace('-', '/', $date);
        $dia_agendamento = date('Y-m-d',strtotime($date1 . "+1 days"));

        if ( ($dia_agendamento) <= date('Y-m-d') && ($venda['situacao'] == 'Pendente')){
             $update = new ServicosPainel();
             $update->updateConcluido($this->params['item']);
             echo '1';
         }else{
            echo '0';
        }

    }

    public function modalLink($params){
        $this->setParams($params);
        $this->render('painel/pages/servicos/agendamentos.twig', []);
    }

     public function linkSave($params)
    {
        $this->setParams($params);
        $linkSave = new ServicosPainel();
        $linkSave->postLinkSave($this->params);
        echo '1';
    }



}