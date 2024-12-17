<?php

namespace Agencia\Close\Controllers\Painel\ContratosPainel;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\ContratosPainel;
use Agencia\Close\Models\Painel\PedidosPainel;

class ContratosPainelController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);
        $this->permissions('contratos', '"view"');

        $model = new ContratosPainel();
        $contratos = $model->getContratos($params)->getResult();

        $this->render('painel/pages/contratos/index.twig', ['menu' => 'contratos', 'contratos' => $contratos]);
    }


}