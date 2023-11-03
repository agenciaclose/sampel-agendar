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

            $i = 0;
            foreach($visitas as $visita){
                $todasEquipes = new Visitas();
                $todasEquipes = $todasEquipes->listaEquipesVisita($visita['visita_id'])->getResult();
                $visitas[$i]['equipevisita'] = $todasEquipes;
                $i++;
            }

            $minhasvisitas = new Visitas();
            $minhasvisitas = $minhasvisitas->listarVisitas('3')->getResult();
            $i = 0;
            foreach($minhasvisitas as $minhas){
                $todasEquipes = new Visitas();
                $todasEquipes = $todasEquipes->listaEquipesVisita($minhas['visita_id'])->getResult();
                $minhasvisitas[$i]['equipevisita'] = $todasEquipes;
                $i++;
            }

        }else{
            $visitas = array();
            $minhasvisitas = array();
        }


        $this->render('pages/home/home.twig', ['menu' => 'home', 'minhasvisitas' => $minhasvisitas, 'visitas' => $visitas]);
    }
}