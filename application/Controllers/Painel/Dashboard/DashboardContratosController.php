<?php

namespace Agencia\Close\Controllers\Painel\Dashboard;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\ContratosPainel;
use DateTime; 

class DashboardContratosController extends Controller
{
	
    public function index($params)
    {
        $this->setParams($params);

        $ano = date('Y');
        if(isset($_GET['ano'])){
            $ano = $_GET['ano'];
        }

        $data = $this->obterPrimeiroEultimoDiaDoMes($ano);
        
        $model = new ContratosPainel;
        $contratos = $model->getContratosTotal()->getResultSingle();

        $valorPago = $model->getContratosValorPago()->getResultSingle();
        $valorNaoPago = $model->getContratosValorNaoPago()->getResultSingle();
        $pagamentosMes = $model->getPagamentosPorMes()->getResult();

        $lista_orcamentos = $model->getListaOrcamentoPorMes($data['primeiro_dia'], $data['ultimo_dia'])->getResult();

        $this->render('painel/pages/dashboard/contratos.twig', [
            'menu' => 'dashboard', 
            'submenu' => 'contratos', 
            'contratos' => $contratos,
            'valorPago' => $valorPago,
            'valorNaoPago' => $valorNaoPago,
            'pagamentosMes' => $pagamentosMes,
            'lista_orcamentos' => $lista_orcamentos
         ]);
    }

    function obterPrimeiroEultimoDiaDoMes($ano = null, $mes = null) {
        if (!$ano) {
            $ano = date('Y');
        }
        if (!$mes) {
            $mes = date('m');
        }
        $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
        $primeiroDia = "$ano-$mes-01";
        $ultimoDia = date("Y-m-t", strtotime($primeiroDia));
    
        return [
            'primeiro_dia' => $primeiroDia,
            'ultimo_dia' => $ultimoDia
        ];
    }

    private function formatMonthInPortuguese(DateTime $date)
    {
        $months = [
            'January' => 'Janeiro',
            'February' => 'Fevereiro',
            'March' => 'Março',
            'April' => 'Abril',
            'May' => 'Maio',
            'June' => 'Junho',
            'July' => 'Julho',
            'August' => 'Agosto',
            'September' => 'Setembro',
            'October' => 'Outubro',
            'November' => 'Novembro',
            'December' => 'Dezembro',
        ];

        return $months[$date->format('F')];
    }

}