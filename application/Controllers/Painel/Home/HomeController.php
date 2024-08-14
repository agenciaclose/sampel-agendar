<?php

namespace Agencia\Close\Controllers\Painel\Home;

use Agencia\Close\Conn\Read;
use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\HomePainel;
use Agencia\Close\Models\Painel\PedidosPainel;
use Agencia\Close\Models\Painel\ProdutosPainel;

class HomeController extends Controller
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

        $produtosSemEstoque = $produtos->getProdutosSemEstoque()->getResult();

        $valorTotalEstoque = $produtos->valorTotalEstoqueSemPDV()->getResult()[0];
        $valorTotalEstoquePDV = $produtos->valorTotalEstoquePDV()->getResult()[0];

        $pedidos = new PedidosPainel();
        $pedidosTotal = $pedidos->getPedidos()->getResult();

        $model = new HomePainel();
        $eventosGastos = $model->getFourEventosYear()->getResult();

        $this->render('painel/pages/home/home.twig', [
            'menu' => 'home', 
            'loop' => $backgroundColors,
            'produtosSemEstoque' => $produtosSemEstoque,
            'produtosSemPDV' => $produtosSemPDV,
            'produtosPDV' => $produtosPDV,
            'valorTotalEstoque' => $valorTotalEstoque,
            'valorTotalEstoquePDV' => $valorTotalEstoquePDV,
            'pedidosTotal' => $pedidosTotal,
            'eventosGastos' => $eventosGastos
        ]);
    }

}