<?php

namespace Agencia\Close\Controllers\Admin\Saques;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Admin\SaquesAdmin;

class SaquesController extends Controller
{

    public function index($params)
    {
        $this->setParams($params);
        $saques = $this->saquesAdmin();
        $this->render('painel/admin/saques/saques.twig', ['menu' => 'saques', 'saques' => $saques]);
    }

    public function verificarModal($params)
    {
        $this->setParams($params);
        $carteira = $this->carteiraVerificar($this->params['sampel_user_id']);
        $this->render('painel/src/modal/admin/saques/verificar.twig', ['carteira' => $carteira]);
    }

    public function statusModal($params)
    {
        $this->setParams($params);
        $saque = new SaquesAdmin();
        $saque = $saque->getSaque($this->params['id'])->getResult()[0];
        $this->render('painel/src/modal/admin/saques/status.twig', ['saque' => $saque]);
    }

    private function saquesAdmin()
    {
        $result = new SaquesAdmin();
        return $result->getSaques()->getResult();
    }

    public function statusSave($params)
    {
        $this->setParams($params);
        $statusSave = new SaquesAdmin();
        $statusSave = $statusSave->statusSave($this->params);
        echo "0";
    }

    private function carteiraVerificar($sampel_user_id){

        $vendas_agendamentos = new SaquesAdmin();
        $vendas_agendamentos = $vendas_agendamentos->getTotalAgendamentos($sampel_user_id);
        if ($vendas_agendamentos->getResult()) {
            $vendas_agendamentos_total = $vendas_agendamentos->getResult()[0]['total'];
        }else{
            $vendas_agendamentos_total = 0.00;
        }

        $vendas_produtos = new SaquesAdmin();
        $vendas_produtos = $vendas_produtos->getTotalProdutos($sampel_user_id);
        if ($vendas_produtos->getResult()) {
            $vendas_produtos_total = $vendas_produtos->getResult()[0]['total'];
        }else{
            $vendas_produtos_total = 0.00;
        }

        $saques_realizados = new SaquesAdmin();
        $saques_realizados = $saques_realizados->getTotalSaques($sampel_user_id);
        if ($saques_realizados->getResult()) {
            $saques_realizados_total = $saques_realizados->getResult()[0]['total'];
        }else{
            $saques_realizados_total = 0.00;
        }

        $total_valor_vendas = ($vendas_agendamentos_total + $vendas_produtos_total);

        $arrecadado = $total_valor_vendas * (VALOR_GANHO / 100);
        $valor_base = $total_valor_vendas - $arrecadado;

        $carteira = ($valor_base - $saques_realizados_total);

        return $carteira;

    }

}