<?php

namespace Agencia\Close\Controllers\Painel\Feedback;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\FeedbackPainel;

class FeedbackController extends Controller
{

    public function perguntas()
    {
        $perguntas = new FeedbackPainel();
        $perguntas = $perguntas->getPerguntas()->getResult();
        $this->render('painel/pages/feedback/perguntas.twig', ['menu' => 'perguntas', 'perguntas' => $perguntas]);
    }

    public function savePerguntas($params)
    {
        $this->setParams($params);
        $save = new FeedbackPainel();
        $save = $save->savePerguntas($params);
        if($save){echo '1';}else{echo '0';}
    }

    public function excluirPergunta($params)
    {
        $this->setParams($params);
        $pergunta = new FeedbackPainel();
        $pergunta = $pergunta->excluirPergunta($params['id']);
    }
}