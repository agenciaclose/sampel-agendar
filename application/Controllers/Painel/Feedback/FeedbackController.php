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

    public function feedbacks()
    {
        $visitas = new FeedbackPainel();
        $visitas = $visitas->getVisitasList()->getResult();

        $this->render('painel/pages/feedback/feedbacks.twig', ['menu' => 'feedback', 'visitas' => $visitas]);
    }

    public function feedbacksList($params)
    {
        $this->setParams($params);

        $perguntas = new FeedbackPainel();
        $perguntas = $perguntas->getFeedbacksPerguntas()->getResult();

        $i = 0;
        foreach ($perguntas as $pergunta) {
            $feedbacks = new FeedbackPainel();
            $perguntas[$i]['estatisticas'] = $feedbacks->getFeedbacksList($params['id'], $pergunta['pergunta'])->getResult();
            $i++;
        }

        $this->render('painel/pages/feedback/ver.twig', ['menu' => 'feedback', 'perguntas' => $perguntas]);
    }

    public function ordernarPergunta($params)
    {
        $this->setParams($params);
        $ordenar = new FeedbackPainel();
        $ordenar = $ordenar->getFeedbacksPerguntasOrdenar($params);
    }

}