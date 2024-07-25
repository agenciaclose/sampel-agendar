<?php

namespace Agencia\Close\Controllers\Painel\PedidosPainel;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\Result;
use Agencia\Close\Models\Painel\PedidosPainel;
use Agencia\Close\Models\Painel\ProdutosPainel;

class PedidosPainelController extends Controller
{
    public function listaPedidos($params)
    {
        $this->setParams($params);
        $this->permissions('pedidos', '"view"');

        $model = new ProdutosPainel();
        $pedidos = $model->getPedidos()->getResult();

        $this->render('painel/pages/pedidos/lista.twig', ['menu' => 'pedidos',  'pedidos' => $pedidos]);
    }

    public function addPedido($params)
    {
        $this->setParams($params);
        $this->permissions('pedidos', '"add"');

        $model = new ProdutosPainel();
        $produtos = $model->getProdutos()->getResult();

        $this->render('painel/pages/pedidos/add.twig', ['menu' => 'pedidos',  'produtos' => $produtos]);
    }

    public function getTipoEvento($params)
    {
        $this->setParams($params);
        $model = new PedidosPainel();

        if ($params['tipo'] == 'visitas') {
            $eventos = $model->getTipoVisitas()->getResult();
            echo json_encode($eventos, JSON_UNESCAPED_UNICODE);
        }

        if ($params['tipo'] == 'palestras') {
            $eventos = $model->getTipoPalestras()->getResult();
            echo json_encode($eventos, JSON_UNESCAPED_UNICODE);
        }

        if ($params['tipo'] == 'eventos') {
            $eventos = $model->getTipoEventos()->getResult();
            echo json_encode($eventos, JSON_UNESCAPED_UNICODE);
        }
    }

    public function addPedidoSave($params)
    {
        $this->setParams($params);
        $model = new PedidosPainel();
        $save = $model->addProductSave($params);
        if($save === 'success'){
           echo '1'; 
        }
    }
    
}