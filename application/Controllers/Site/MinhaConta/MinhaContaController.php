<?php

namespace Agencia\Close\Controllers\Site\MinhaConta;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\MinhaConta;
use Agencia\Close\Models\Painel\CurriculumPainel;

class MinhaContaController extends Controller
{
    public function minhaconta($params)
    {
        $this->setParams($params);
        $this->render('pages/minha-conta/minha-conta.twig', ['active' => 'minha-conta']);
    }

    public function schedules($params)
    {
        $this->setParams($params);
        $schedules = new  MinhaConta();
        $schedules = $schedules->getSchedules($_SESSION['sampel_user_id'])->getResult();
        $this->render('pages/minha-conta/schedules.twig', ['active' => 'schedules', 'schedules' => $schedules]);
    }

    public function schedulesView($params)
    {
        $this->setParams($params);

        $venda = new MinhaConta();
        $venda = $venda->getAgendamentoView($_SESSION['sampel_user_id'], $this->params['id'])->getResult()[0];

        $horarios = new MinhaConta();
        $horarios = $horarios->getHorariosAgendamento($this->params['id'])->getResult();

        $notas = new MinhaConta();
        $notas = $notas->getNotasAgendamento($this->params['id'])->getResult();

        $this->render('pages/minha-conta/schedulesView.twig', ['active' => 'schedules', 'venda' => $venda, 'horarios' => $horarios, 'notas' => $notas]);
    }

    public function orders($params)
    {
        $this->setParams($params);
        $orders = new  MinhaConta();
        $orders = $orders->getOrders($_SESSION['sampel_user_id'])->getResult();
        $this->render('pages/minha-conta/orders.twig', ['active' => 'orders', 'orders' => $orders]);
    }

    public function ordersView($params)
    {
        $this->setParams($params);

        $venda = new MinhaConta();
        $venda = $venda->getOrdersView($_SESSION['sampel_user_id'], $this->params['id'])->getResult()[0];

        $notas = new MinhaConta();
        $notas = $notas->getNotasVenda($this->params['id'])->getResult();

        $arquivos = new MinhaConta();
        $arquivos = $arquivos->getArquivos($this->params['id'])->getResult();

        $this->render('pages/minha-conta/ordersView.twig', ['active' => 'orders', 'venda' => $venda, 'notas' => $notas, 'arquivos' => $arquivos]);
    }



    public function editaccount($params)
    {
        $this->setParams($params);
        $user = new CurriculumPainel();
        $user = $user->getUser($_SESSION['sampel_user_id'])->getResult()[0];
        $this->render('pages/minha-conta/edit-account.twig', ['active' => 'edit-account', 'user' => $user]);
    }
}