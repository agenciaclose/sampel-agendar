<?php

namespace Agencia\Close\Controllers\Painel\Home;

use Agencia\Close\Controllers\Controller;

class HomeController extends Controller
{
	
    public function index($params)
    {
        $this->setParams($params);
        $this->render('painel/pages/home/home.twig', ['menu' => 'home']);
    }

}