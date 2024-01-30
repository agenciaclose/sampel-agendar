<?php

namespace Agencia\Close\Controllers\Site\Recepcao;

use Agencia\Close\Models\Site\Visitas;
use Agencia\Close\Models\Site\Recepcao;
use Agencia\Close\Models\Site\Palestras;
use Agencia\Close\Controllers\Controller;

class RecepcaoController extends Controller
{
    public function visita($params)
    {
        $this->setParams($params);
        
        if(!empty($_SESSION['sampel_user_id'])){
            $visita = new Visitas();
            $visita = $visita->listarVisitaID($params['id'])->getResult()[0];

            $total = new Recepcao();
            $total = $total->inscricoesConfirmados($visita['id'])->getResult()[0];

            $this->render('pages/recepcao/visita.twig', ['menu' => 'Recepção', 'visita' => $visita, 'confirmados' => $total]);
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


    public function palestra($params)
    {
        $this->setParams($params);
        
        if(!empty($_SESSION['sampel_user_id'])){
            $palestra = new Palestras();
            $palestra = $palestra->getPalestra($params['id'])->getResult()[0];

            $total = new Recepcao();
            $total = $total->InscricoesConfirmadosPalestra($palestra['id'])->getResult()[0];

            $this->render('pages/recepcao/palestra.twig', ['menu' => 'Recepção', 'palestra' => $palestra, 'confirmados' => $total]);
        }else{
            $this->render('pages/error/no-permition.twig', ['menu' => 'visitas']);
        }
    }


    public function confirmarPresencaPalestra($params)
    {
        $this->setParams($params);
        $update = new Recepcao();
        $update = $update->confirmarPresencaPalestra($params);
        if($update->getResult()){
            echo '0';
        }else{
            echo '1';
        }
    }

}