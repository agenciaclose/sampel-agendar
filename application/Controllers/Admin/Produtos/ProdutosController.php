<?php

namespace Agencia\Close\Controllers\Admin\Produtos;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Admin\ProdutosPainel;

class ProdutosController extends Controller
{

    public function vendas($params)
    {
        $this->setParams($params);
        $vendas = new ProdutosPainel();
        $vendas = $vendas->getVendasAdmin()->getResult();
        $this->render('painel/admin/produtos/vendas.twig', ['menu' => 'vendas', 'vendas' => $vendas]);
    }

    public function vendaView($params)
    {
        $this->setParams($params);
        $venda = new ProdutosPainel();
        $venda = $venda->getVendaView($this->params['id'])->getResult()[0];

        $notas = new ProdutosPainel();
        $notas = $notas->getNotasVenda($this->params['id'])->getResult();

        $this->render('painel/admin/produtos/view.twig', ['menu' => 'vendas', 'venda' => $venda, 'notas' => $notas]);
    }

}