<?php

namespace Agencia\Close\Controllers\Site\Recepcao;

use Agencia\Close\Models\Site\Visitas;
use Agencia\Close\Models\Site\Recepcao;
use Agencia\Close\Controllers\Controller;

class RecepcaoController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);
        
        if(!empty($_SESSION['sampel_user_id'])){
            $visita = new Visitas();
            $visita = $visita->listarVisitaID($params['id'])->getResult()[0];
            $this->render('pages/recepcao/index.twig', ['menu' => 'Recepção', 'visita' => $visita]);
        }else{
            $this->render('pages/error/no-permition.twig', ['menu' => 'visitas']);
        }
    }

    public function confirmarPresenca($params)
    {
        $this->setParams($params);
        $update = new Recepcao();
        $update = $update->confirmarPresenca($params);
        if($update->getResult()){
            echo '0';
        }else{
            echo '1';
        }
    }

}