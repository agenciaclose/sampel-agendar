<?php

namespace Agencia\Close\Controllers\Site\FeedbackPalestras;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\FeedbackPalestras;
use Agencia\Close\Models\Site\Palestras;

class FeedbackPalestrasController extends Controller
{
    public function feedback($params)
    {
        $this->setParams($params);

        $palestra = new Palestras();
        $palestra = $palestra->getPalestra($params['id']);
        if($palestra->getResult()){
            $palestra = $palestra->getResult()[0];
        }

        if(isset($params['cpf'])){

            $user = new FeedbackPalestras();
            $user = $user->getUserVisita($params['id'], $params['cpf']);
            if($user->getResult()){
                $user = $user->getResult()[0];
            }

            $check = new FeedbackPalestras();
            $check = $check->checkFeedback($params['id'], $params['cpf']);
            if($check->getResult()){
                $check = $check->getResult()[0];
            }

        }else{
            $user = '';
            $check = '';
        }

        $perguntas = new FeedbackPalestras();
        $perguntas = $perguntas->getPerguntas()->getResult();

        $this->render('pages/palestras/feedback/feedback.twig', ['menu' => 'feedback', 'palestra' => $palestra, 'perguntas' => $perguntas, 'user' => $user, 'check' => $check]);
    }

    public function checkInscricao($params)
    {
        $this->setParams($params);

        $user = new FeedbackPalestras();
        $user = $user->getUserVisita($params['id_palestra'], $params['cpf']);
        if($user->getResult()){
            echo $user->getResult()[0]['cpf'].'/'.$user->getResult()[0]['id_palestra'];
        }else{
            echo '0';
        }

    }

    // public function saveFeedback($params)
    // {
    //     $this->setParams($params);
    //     $save = new Feedback();
    //     $save = $save->saveFeedback($params);
    //     echo '1';
    // }

    // public function feedbacksEstatisticas($params)
    // {
    //     $this->setParams($params);

    //     $visita = new Visitas();
    //     $visita = $visita->listarVisitaID($params['id']);
    //     if($visita->getResult()){
    //         $visita = $visita->getResult()[0];
    //     }

    //     $perguntas = new Feedback();
    //     $perguntas = $perguntas->getFeedbacksPerguntas()->getResult();

    //     $i = 0;
    //     foreach ($perguntas as $pergunta) {
    //         $feedbacks = new Feedback();
    //         $perguntas[$i]['estatisticas'] = $feedbacks->getFeedbacksList($params['id'], $pergunta['pergunta'])->getResult();
    //         $i++;
    //     }

    //     $this->render('pages/feedback/ver.twig', ['menu' => 'feedback', 'perguntas' => $perguntas, 'visita' => $visita]);
    // }
}