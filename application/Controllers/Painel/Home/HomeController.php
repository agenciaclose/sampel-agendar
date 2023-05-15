<?php

namespace Agencia\Close\Controllers\Painel\Home;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\SaquesPainel;
use Agencia\Close\Models\Painel\ServicosPainel;
use Agencia\Close\Models\Painel\ProdutosPainel;
use Agencia\Close\Models\Admin\Servicos;

class HomeController extends Controller
{
	
    public function index($params)
    {
        $this->setParams($params);
        $carteira = $this->carteira();

        if($_SESSION['sampel_user_id'] == 2){
            $agendamentos = new ServicosPainel();
            $agendamentos_lista = $agendamentos->getAgendamentos()->getResult();
        }else{
            $agendamentos = new Servicos();
            $agendamentos_lista = $agendamentos->getAgendamentos()->getResult();
        }
        
        if($_SESSION['sampel_user_id'] == 2){
            $produtosVendas = new ProdutosPainel();
            $produtosVendas = $produtosVendas->getVendas($_SESSION['sampel_user_id'])->getResult();
        }else{
            $produtosVendas = new ProdutosPainel();
            $produtosVendas = $produtosVendas->getVendasAdmin()->getResult();
        }
        
        $last = new ServicosPainel();

        if($_SESSION['sampel_user_id'] == 2){
            if ($last->getAgendamentoViewLast($_SESSION['sampel_user_id'])->getResult()) {
                $last = $last->getAgendamentoViewLast($_SESSION['sampel_user_id'])->getResult()[0];
            }else{
                $last = array();
            }
        }else{
            $last = array();
        }

        $horarios = new ServicosPainel();

        if(($_SESSION['sampel_user_id'] == 2) && (!empty($last['id']))){
            
            if ($horarios->getHorariosAgendamento($last['id'])->getResult()) {
                $horarios = $horarios->getHorariosAgendamento($last['id'])->getResult();
            }else{
                $horarios = array();
            }

        }else{
            $horarios = array();
        }

        $clientesAgendamentos = new ProdutosPainel();
        $clientesAgendamentos = $clientesAgendamentos->getClientesAgendamentos($_SESSION['sampel_user_id'])->getResult();

        $clientesVendas = new ProdutosPainel();
        $clientesVendas = $clientesVendas->getClientesVendas($_SESSION['sampel_user_id'])->getResult();

        $totalAgendamentos = new ProdutosPainel();
        $totalAgendamentos = $totalAgendamentos->getTotalAgendamentos($_SESSION['sampel_user_id'])->getResult()[0];

        $totalVendas = new ProdutosPainel();
        $totalVendas = $totalVendas->getTotalVendas($_SESSION['sampel_user_id'])->getResult()[0];

        $total_valor = ($totalAgendamentos['total'] + $totalVendas['total']);

        $this->render('painel/pages/home/home.twig', ['menu' => 'home', 'carteira' => $carteira, 'agendamentos' => $agendamentos_lista, 'vendas' => $produtosVendas, 'last' => $last, 'horarios' => $horarios, 'clientesAgendamentos' => $clientesAgendamentos, 'clientesVendas' => $clientesVendas, 'total' => $total_valor]);
    }

    //PEGAR O VALOR EM CAIXA
    public function carteira()
    {

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

    public function manuaisConsultor()
    {
        $manuais = new ServicosPainel();
        $manuais = $manuais->getManuais()->getResult();
        $this->render('painel/pages/manuais/index.twig', ['menu' => 'manuais', 'manuais' => $manuais]);

    }

}