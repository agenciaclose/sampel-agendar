<?php

namespace Agencia\Close\Controllers\Site\Home;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\Visitas;

class HomeController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);
        if(!empty($_SESSION['sampel_user_id'])){
            $visitas = new Visitas();
            $visitas = $visitas->listarVisitasOutros()->getResult();
        }else{
            $visitas = array();
        }

        $this->render('pages/home/home.twig', ['menu' => 'home', 'visitas' => $visitas]);
    }
}