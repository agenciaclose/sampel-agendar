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

        $anos = $model->getAnosContratos()->getResult();

        $total_geral = $model->getContratosTotalGeral()->getResultSingle();
        $total_geral_ativos = $model->getContratosTotalGeralAtivos()->getResult();
        $total_geral_a_vencer = $model->getContratosTotalGeralAVencer()->getResult();
        $total_geral_vencidos = $model->getContratosTotalGeralVencidos()->getResult();

        // ESTATISTICAS POR ANO
        $ano = date('Y');
        if(isset($_GET['ano'])){
            $ano = $_GET['ano'];
        }

        $datasMes = $this->inicioFimCadaMes($ano);
        $data = $this->obterPrimeiroEultimoDiaDoMesAtual($ano);
        $model = new ContratosPainel;
        $contratos = $model->getContratosTotal()->getResultSingle();
        $valorPago = $model->getContratosValorPago()->getResultSingle();
        $valorNaoPago = $model->getContratosValorNaoPago()->getResultSingle();
        $pagamentosMes = $model->getPagamentosPorMes()->getResult();

        // Associa o período a cada pagamento usando referência
        foreach ($pagamentosMes as $key => &$pagamento) {
            $pagamento['periodo'] = $datasMes[$key];
        }
        unset($pagamento);

        $lista_orcamentos = $model->getListaOrcamentoPorMes($data['primeiro_dia'], $data['ultimo_dia'])->getResult();
        $valorPagoMes = $model->getValoresPagosMes($data['primeiro_dia'], $data['ultimo_dia'])->getResultSingle();
        $valorNaoPagoMes = $model->getValoresNaoPagosMes($data['primeiro_dia'], $data['ultimo_dia'])->getResultSingle();
        // 

        $this->render('painel/pages/dashboard/contratos.twig', [
            'menu' => 'dashboard', 
            'submenu' => 'contratos',
            'anos' => $anos,
            'totalGeral' => $total_geral,
            'totalGeralAtivos' => $total_geral_ativos,
            'totalGeralAtivosAVencer' => $total_geral_a_vencer,
            'totalGeralAtivosVencidos' => $total_geral_vencidos,
            'contratos' => $contratos,
            'valorPago' => $valorPago,
            'valorNaoPago' => $valorNaoPago,
            'pagamentosMes' => $pagamentosMes,
            'valorPagoMes' => $valorPagoMes['valor_total_pago'],
            'valorNaoPagoMes' => $valorNaoPagoMes['valor_nao_total_pago'],
            'lista_orcamentos' => $lista_orcamentos
         ]);
    }

    public function getListaOrcamentoPorMes($params){

        $this->setParams($params);
        $model = new ContratosPainel;
        $lista_orcamentos = $model->getListaOrcamentoPorMes($params['primeiro_dia'], $params['ultimo_dia'])->getResult();

        $this->render('components/dashboard/contratos/relatorioMes.twig', ['lista_orcamentos' => $lista_orcamentos]);
    }

    public function getListaValorTotalPorMes($params){

        $this->setParams($params);
        $model = new ContratosPainel;

        $valorPagoMes = $model->getValoresPagosMes($params['primeiro_dia'], $params['ultimo_dia'])->getResultSingle();
        $valorNaoPagoMes = $model->getValoresNaoPagosMes($params['primeiro_dia'], $params['ultimo_dia'])->getResultSingle();
        
        echo $valorPagoMes['valor_total_pago'].'/'.$valorNaoPagoMes['valor_nao_total_pago'];
    }

    function obterPrimeiroEultimoDiaDoMesAtual($ano = null, $mes = null) {
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

    public function inicioFimCadaMes($ano)
    {
        $datasMeses = array();

        for ($mes = 1; $mes <= 12; $mes++) {
            $mesFormatado = str_pad($mes, 2, '0', STR_PAD_LEFT);
            $primeiroDia = "$ano-$mesFormatado-01";
            $ultimoDia = date("Y-m-t", strtotime($primeiroDia));
            $datasMeses[] = array(
                'primeiro_dia' => $primeiroDia,
                'ultimo_dia' => $ultimoDia
            );
        }

        return $datasMeses;
    }

}