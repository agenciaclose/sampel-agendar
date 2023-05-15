<?php

namespace Agencia\Close\Controllers\Admin\Servicos;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\Upload;
use Agencia\Close\Models\Admin\Servicos;

class ServicosController extends Controller
{

    public function agendamentos($params)
    {
        $this->setParams($params);

        $agendamentos = new Servicos();
        $agendamentos = $agendamentos->getAgendamentos()->getResult();
        $this->render('painel/admin/servicos/agendamentos.twig', ['menu' => 'agendamentos', 'agendamentos' => $agendamentos]);
    }

    public function agendamentoView($params)
    {
        $this->setParams($params);
        $venda = new Servicos();
        $venda = $venda->getAgendamentoView($this->params['id'])->getResult()[0];

        $horarios = new Servicos();
        $horarios = $horarios->getHorariosAgendamento($this->params['id'])->getResult();

        $notas = new Servicos();
        $notas = $notas->getNotasAgendamento($this->params['id'])->getResult();

        $this->render('painel/admin/servicos/view.twig', ['menu' => 'agendamentos', 'venda' => $venda, 'horarios' => $horarios, 'notas' => $notas]);
    }


}