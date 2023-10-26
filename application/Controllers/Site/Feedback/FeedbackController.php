<?php

namespace Agencia\Close\Controllers\Site\Feedback;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\Feedback;
use Agencia\Close\Models\Site\Visitas;
use Agencia\Close\Models\User\User;

class FeedbackController extends Controller
{
    public function pergunta($params)
    {
        $this->setParams($params);

        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['id'])->getResult()[0];

        $this->render('pages/feedback/feedback.twig', ['menu' => 'feedback', 'visita' => $visita]);
    }

}