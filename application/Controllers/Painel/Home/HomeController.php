<?php

namespace Agencia\Close\Controllers\Painel\Home;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\SaquesPainel;
use Agencia\Close\Models\Painel\ServicosPainel;
use Agencia\Close\Models\Painel\ProdutosPainel;
use Agencia\Close\Models\Admin\Servicos;

class HomeController extends Controller
{
	
    public function index($params)
    {
        $this->setParams($params);
        $this->render('painel/pages/home/home.twig', ['menu' => 'home']);
    }

}