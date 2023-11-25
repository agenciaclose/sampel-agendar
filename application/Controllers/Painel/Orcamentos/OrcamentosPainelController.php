<?php

namespace Agencia\Close\Controllers\Painel\Orcamentos;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\OrcamentosPainel;

class OrcamentosPainelController extends Controller
{

    public function lista()
    {
        $visitas = new OrcamentosPainel();
        $visitas = $visitas->getVisitasList()->getResult();
        $this->render('painel/pages/orcamentos/lista.twig', ['menu' => 'orcamentos', 'visitas' => $visitas]);
    }

    public function editar($params)
    {
        $this->setParams($params);

        $visitas = new OrcamentosPainel();
        $visitas = $visitas->getVisitasList()->getResult();
        $this->render('painel/pages/orcamentos/editar.twig', ['menu' => 'orcamentos', 'visitas' => $visitas]);
    }

}