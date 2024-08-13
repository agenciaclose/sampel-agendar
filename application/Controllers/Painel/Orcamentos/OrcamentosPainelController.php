<?php

namespace Agencia\Close\Controllers\Painel\Orcamentos;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\PedidosPainel;
use Agencia\Close\Models\Painel\OrcamentosPainel;

class OrcamentosPainelController extends Controller
{

    public function lista($params)
    {
        $this->setParams($params);
       
        $model = new PedidosPainel();
        $brindes = $model->getPedidoOrcamentoID($params['id'], $params['tipo'])->getResult();
            
        $this->render('painel/pages/orcamentos/lista.twig', ['menu' => $params['tipo'], 'brindes' => $brindes]);
    }

    public function editar($params)
    {
        $this->setParams($params);

        $visitas = new OrcamentosPainel();
        $visitas = $visitas->getVisitasList()->getResult();
        $this->render('painel/pages/orcamentos/editar.twig', ['menu' => 'orcamentos', 'visitas' => $visitas]);
    }

}