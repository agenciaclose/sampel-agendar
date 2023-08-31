<?php

namespace Agencia\Close\Controllers\Painel\Config;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\Upload;
use Agencia\Close\Models\Painel\ConfigPainel;

class ConfigController extends Controller
{

    public function index($params)
    {
        $this->setParams($params);

        $estados = new ConfigPainel();
        $estados = $estados->getEstados()->getResult();

        $this->render('painel/pages/visitas/configuracoes.twig', ['menu' => 'visitas', 'estados' => $estados]);
    }

    public function save($params)
    {
        $this->setParams($params);

        $limites = new ConfigPainel();
        $limites = $limites->SaveLimites($this->params)->getResult();
        echo "1";
    }


}
