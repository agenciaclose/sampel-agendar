<?php

namespace Agencia\Close\Controllers\Painel\PedidosPainel;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\Result;
use Agencia\Close\Models\Painel\PedidosPainel;
use Agencia\Close\Models\Painel\ProdutosPainel;

class PedidosPainelController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);
        $this->permissions('pedidos', '"view"');

        $model = new ProdutosPainel();
        $produtos = $model->getProdutos()->getResult();

        $this->render('painel/pages/pedidos/index.twig', ['menu' => 'pedidos',  'produtos' => $produtos]);
    }

    public function getTipoEvento($params)
    {
        $this->setParams($params);
        $model = new PedidosPainel();

        var_dump($params['tipo']);

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
    
}