<?php

namespace Agencia\Close\Controllers\Painel\FeirasPainel;

use Agencia\Close\Helpers\Upload;
use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\FeirasPainel;

class FeirasPainelController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);
        $this->permissions('feiras', '"view"');
        $this->render('painel/pages/feiras/index.twig', ['menu' => 'feiras']);
    }

    public function productAdd($params)
    {
        $this->setParams($params);
        $this->render('painel/pages/feiras/form.twig', []);
    }

    public function productEdit($params)
    {
        $this->setParams($params);

        $produto = new FeirasPainel();
        $produto = $produto->getProdutoID($params['id'])->getResult();
        $this->render('painel/pages/feiras/form.twig', ['product' => $produto[0]]);
    }

}