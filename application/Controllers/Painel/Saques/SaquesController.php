<?php

namespace Agencia\Close\Controllers\Painel\Saques;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\SaquesPainel;

class SaquesController extends Controller
{

    public function index($params)
    {
        $this->setParams($params);
        $contas_bancarias = $this->contas_bancarias();
        $carteira = $this->carteira();
        $saques = $this->saques();
        $this->render('painel/pages/saques/saques.twig', ['menu' => 'saques', 'contas' => $contas_bancarias, 'carteira' => $carteira, 'saques' => $saques]);
    }

    public function SaqueModal()
    {
        $carteira = $this->carteira();
        $contas_bancarias = $this->contas_bancarias();
        $this->render('painel/src/modal/saques/criar.twig', ['contas' => $contas_bancarias, 'carteira' => $carteira]);
    }

    public function SaveSaque($params)
    {
        $this->setParams($params);
        $conta = new SaquesPainel();

        $CarteiraCheck = $this->CarteiraCheckReturn($this->params['valor']);

        if($CarteiraCheck == '0') {
            $conta->createSaque($this->params, $_SESSION['sampel_user_id']);
            echo "0";
        } else {
            echo '1';
        }
    }

    public function ContaCriar()
    {
        $this->render('painel/src/modal/conta/criar.twig');
    }

    public function ContaEditar($params)
    {
        $this->setParams($params);
        $conta = new SaquesPainel();
        $conta = $conta->getContaID($_SESSION['sampel_user_id'], $this->params['id'])->getResult()[0];
        $this->render('painel/src/modal/conta/editar.twig', ['conta' => $conta]);
    }

    public function ContaSalvar($params)
    {
        $this->setParams($params);
        $conta = new SaquesPainel();
        if($this->params['id'] != '-1') {
            $conta->updateConta($this->params, $_SESSION['sampel_user_id'], $this->params['id']);
        } else {
            $conta->createConta($this->params, $_SESSION['sampel_user_id']);
        }
        echo '1';
    }

    private function contas_bancarias()
    {
        $result = new SaquesPainel();
        return $result->getContas_Bancarias($_SESSION['sampel_user_id'])->getResult();
    }

    //PEGAR O VALOR EM CAIXA
    private function carteira(){

        $vendas_agendamentos = new SaquesPainel();
        $vendas_agendamentos = $vendas_agendamentos->getTotalAgendamentos($_SESSION['sampel_user_id']);
        if ($vendas_agendamentos->getResult()) {
            $vendas_agendamentos_total = $vendas_agendamentos->getResult()[0]['total'];
        }else{
            $vendas_agendamentos_total = 0.00;
        }

        $vendas_produtos = new SaquesPainel();
        $vendas_produtos = $vendas_produtos->getTotalProdutos($_SESSION['sampel_user_id']);
        if ($vendas_produtos->getResult()) {
            $vendas_produtos_total = $vendas_produtos->getResult()[0]['total'];
        }else{
            $vendas_produtos_total = 0.00;
        }

        $saques_realizados = new SaquesPainel();
        $saques_realizados = $saques_realizados->getTotalSaques($_SESSION['sampel_user_id']);
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

    public function CarteiraCheck(){

        $vendas_agendamentos = new SaquesPainel();
        $vendas_agendamentos = $vendas_agendamentos->getTotalAgendamentos($_SESSION['sampel_user_id']);
        if ($vendas_agendamentos->getResult()) {
            $vendas_agendamentos_total = $vendas_agendamentos->getResult()[0]['total'];
        }else{
            $vendas_agendamentos_total = 0.00;
        }

        $vendas_produtos = new SaquesPainel();
        $vendas_produtos = $vendas_produtos->getTotalProdutos($_SESSION['sampel_user_id']);
        if ($vendas_produtos->getResult()) {
            $vendas_produtos_total = $vendas_produtos->getResult()[0]['total'];
        }else{
            $vendas_produtos_total = 0.00;
        }

        $saques_realizados = new SaquesPainel();
        $saques_realizados = $saques_realizados->getTotalSaques($_SESSION['sampel_user_id']);
        if ($saques_realizados->getResult()) {
            $saques_realizados_total = $saques_realizados->getResult()[0]['total'];
        }else{
            $saques_realizados_total = 0.00;
        }

        $carteira = ($vendas_agendamentos_total + $vendas_produtos_total - $saques_realizados_total);

        $valor_solicitado = str_replace(',', '.', str_replace('.', '', $_POST['valor']));


        if($valor_solicitado > $carteira){
            echo '1';
        }else{
            echo '0';
        }
        
    }

    public function CarteiraCheckReturn($valor_enviado){

        $vendas_agendamentos = new SaquesPainel();
        $vendas_agendamentos = $vendas_agendamentos->getTotalAgendamentos($_SESSION['sampel_user_id']);
        if ($vendas_agendamentos->getResult()) {
            $vendas_agendamentos_total = $vendas_agendamentos->getResult()[0]['total'];
        }else{
            $vendas_agendamentos_total = 0.00;
        }

        $vendas_produtos = new SaquesPainel();
        $vendas_produtos = $vendas_produtos->getTotalProdutos($_SESSION['sampel_user_id']);
        if ($vendas_produtos->getResult()) {
            $vendas_produtos_total = $vendas_produtos->getResult()[0]['total'];
        }else{
            $vendas_produtos_total = 0.00;
        }

        $saques_realizados = new SaquesPainel();
        $saques_realizados = $saques_realizados->getTotalSaques($_SESSION['sampel_user_id']);
        if ($saques_realizados->getResult()) {
            $saques_realizados_total = $saques_realizados->getResult()[0]['total'];
        }else{
            $saques_realizados_total = 0.00;
        }

        $carteira = ($vendas_agendamentos_total + $vendas_produtos_total - $saques_realizados_total);

        $valor_solicitado = str_replace(',', '.', str_replace('.', '', $valor_enviado));


        if($valor_solicitado > $carteira){
            return '1';
        }else{
            return '0';
        }
        
    }

    private function saques()
    {
        $result = new SaquesPainel();
        return $result->getSaques($_SESSION['sampel_user_id'])->getResult();
    }


}