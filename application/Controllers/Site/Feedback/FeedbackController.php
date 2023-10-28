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
        $visita = $visita->listarVisitaID($params['id'])->getResult()[0];

        $user = new Feedback();
        $user = $user->getUserVisita($params['id'], $params['cpf'])->getResult()[0];

        $check = new Feedback();
        $check = $check->checkFeedback($params['id'], $params['cpf']);
        if($check){
            $check = $check->getResult()[0];
        }
        $perguntas = new Feedback();
        $perguntas = $perguntas->getPerguntas()->getResult();

        $this->render('pages/feedback/feedback.twig', ['menu' => 'feedback', 'visita' => $visita, 'perguntas' => $perguntas, 'user' => $user, 'check' => $check]);
    }

    public function saveFeedback($params)
    {
        $this->setParams($params);
        $save = new Feedback();
        $save = $save->saveFeedback($params);
        echo '1';
    }

}