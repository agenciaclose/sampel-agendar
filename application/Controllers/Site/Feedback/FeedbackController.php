<?php

namespace Agencia\Close\Controllers\Site\Feedback;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\Feedback;
use Agencia\Close\Models\Site\Visitas;
use Agencia\Close\Models\User\User;

class FeedbackController extends Controller
{
    public function feedback($params)
    {
        $this->setParams($params);

        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['id']);
        if($visita->getResult()){
            $visita = $visita->getResult()[0];
        }

        if(isset($params['cpf'])){
            $user = new Feedback();
            $user = $user->getUserVisita($params['id'], $params['cpf']);
            if($user->getResult()){
                $user = $user->getResult()[0];
            }

            $check = new Feedback();
            $check = $check->checkFeedback($params['id'], $params['cpf']);
            if($check->getResult()){
                $check = $check->getResult()[0];
            }

        }else{
            $user = '';
            $check = '';
        }

        $perguntas = new Feedback();
        $perguntas = $perguntas->getPerguntas()->getResult();

        $this->render('pages/feedback/feedback.twig', ['menu' => 'feedback', 'visita' => $visita, 'perguntas' => $perguntas, 'user' => $user, 'check' => $check]);
    }

    public function checkInscricao($params)
    {
        $this->setParams($params);

        $user = new Feedback();
        $user = $user->getUserVisita($params['id_visita'], $params['cpf']);
        if($user->getResult()){
            echo $user->getResult()[0]['cpf'].'/'.$user->getResult()[0]['id_visita'];
        }else{
            echo '0';
        }

    }

    public function saveFeedback($params)
    {
        $this->setParams($params);
        $save = new Feedback();
        $save = $save->saveFeedback($params);
        echo '1';
    }

    public function feedbacksEstatisticas($params)
    {
        $this->setParams($params);

        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['id']);
        if($visita->getResult()){
            $visita = $visita->getResult()[0];
        }

        $perguntas = new Feedback();
        $perguntas = $perguntas->getFeedbacksPerguntas()->getResult();

        $i = 0;
        foreach ($perguntas as $pergunta) {

            $feedbacks = new Feedback();
            $perguntas[$i]['estatisticas'] = $feedbacks->getFeedbacksList($params['id'], $pergunta['pergunta'])->getResult();
            
            if($pergunta['tipo'] == 'Texto'){
                $x = 0;
                foreach ($perguntas[$i]['estatisticas'] as $estatisticas){
                    $perguntas[$i]['estatisticas'][$x]['pessoas'] = $feedbacks->getFeedbacksListPessoas($params['id'], $estatisticas['resposta'])->getResult();
                    $x++;
                }
            }

            $i++;
        }

        $this->render('pages/feedback/ver.twig', ['menu' => 'feedback', 'perguntas' => $perguntas, 'visita' => $visita]);
    }

}