<?php

namespace Agencia\Close\Controllers\Site\Home;

use Agencia\Close\Controllers\Controller;

class HomeController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);
        $this->render('pages/home/home.twig', ['menu' => 'home']);
    }
}