<?php

namespace Agencia\Close\Controllers\Painel\Home;

use Agencia\Close\Conn\Read;
use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\HomePainel;
use Agencia\Close\Models\Painel\PedidosPainel;
use Agencia\Close\Models\Painel\ProdutosPainel;

use DateTime; 

class DashboardPedidosController extends Controller
{
	
    public function index($params)
    {
        $this->setParams($params);

        $backgroundColors = [
            'bg-primary',
            'bg-success',
            'bg-danger',
            'bg-warning',
            'bg-info'
        ];

        $produtos = new ProdutosPainel();
        $produtosSemPDV = $produtos->getProdutosSemPDV()->getResult();
        $produtosPDV = $produtos->getProdutosPDV()->getResult();

        $produtosEstoqueBaixo = $produtos->getProdutosEstoqueBaixo()->getResult();
        $valorTotalEstoque = $produtos->valorTotalEstoqueSemPDV()->getResult()[0];
        $valorTotalEstoquePDV = $produtos->valorTotalEstoquePDV()->getResult()[0];
        $valorTotalEstoqueBaixo = $produtos->valorTotalEstoqueBaixo()->getResult()[0];

        $pedidos = new PedidosPainel();
        $pedidosTotal = $pedidos->getPedidos()->getResult();
        $PedidosValorTotal = $pedidos->getPedidosValorTotal()->getResult()[0];

        $pedidos = new PedidosPainel();
        $transportadoras = $pedidos->getPedidosTransportadoras()->getResult();
        $transportadorasTotal = $pedidos->getPedidosTransportadorasTotal()->getResult()[0];

        $retirada = $pedidos->getPedidosRetirada()->getResult();
        $retiradaTotal = $pedidos->getPedidosRetiradaTotal()->getResult()[0];

        $model = new HomePainel();
        $eventosGastos = $model->getForEventosYear()->getResult();
        $pedidosMensais = $model->getPedidosMensais()->getResult();
        $pedidosSemanais = $model->getPedidosSemanais()->getResult();

        $date = new DateTime();

        // Calcula o início da semana (domingo)
        $date = new DateTime();
        $dayOfWeek = $date->format('N');
        $startOfWeek = (clone $date)->modify('-' . ($dayOfWeek - 1) . ' days');
        $endOfWeek = (clone $startOfWeek)->modify('+6 days');
        $weekNumber = $startOfWeek->format("W");
        $startDay = $startOfWeek->format('d');
        $endDay = $endOfWeek->format('d');
        $month = $this->formatMonthInPortuguese($startOfWeek);

        $pedidos = new PedidosPainel();
        $pedidosEstados = $pedidos->getpedidosPorEstados()->getResult();

        $pedidos = new PedidosPainel();
        $pedidosEquipe = $pedidos->getpedidosPorEquipe()->getResult();

        $pedidos = new PedidosPainel();
        $curvaABC = $pedidos->getCurvaABCProdutos()->getResult();

        $this->render('painel/pages/home/dashboard-pedidos.twig', [
            'menu' => 'dashboard-pedidos', 
            'loop' => $backgroundColors,
            'produtosEstoqueBaixo' => $produtosEstoqueBaixo,
            'produtosSemPDV' => $produtosSemPDV,
            'produtosPDV' => $produtosPDV,
            'valorTotalEstoque' => $valorTotalEstoque,
            'valorTotalEstoquePDV' => $valorTotalEstoquePDV,
            'valorTotalEstoqueBaixo' => $valorTotalEstoqueBaixo,
            'pedidosTotal' => $pedidosTotal,
            'PedidosValorTotal' => $PedidosValorTotal,
            'eventosGastos' => $eventosGastos,
            'transportadoras' => $transportadoras,
            'transportadorasTotal' => $transportadorasTotal,
            'retirada' => $retirada,
            'retiradaTotal' => $retiradaTotal,
            'pedidosMensais' => $pedidosMensais,
            'pedidosSemanais' => $pedidosSemanais,
            'weekNumber' => $weekNumber,
            'startDay' => $startDay,
            'endDay' => $endDay,
            'month' => $month,
            'pedidosEstados' => $pedidosEstados,
            'curvaABC' => $curvaABC,
            'pedidosEquipe' => $pedidosEquipe
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