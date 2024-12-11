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
        
        $model = new ContratosPainel;
        $contratos = $model->getContratosTotal()->getResultSingle();

        $valorPago = $model->getContratosValorPago()->getResultSingle();
        $valorNaoPago = $model->getContratosValorNaoPago()->getResultSingle();
        $pagamentosMes = $model->getPagamentosPorMes()->getResult();

        $this->render('painel/pages/dashboard/contratos.twig', [
            'menu' => 'dashboard', 
            'submenu' => 'contratos', 
            'contratos' => $contratos,
            'valorPago' => $valorPago,
            'valorNaoPago' => $valorNaoPago,
            'pagamentosMes' => $pagamentosMes
         ]);
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