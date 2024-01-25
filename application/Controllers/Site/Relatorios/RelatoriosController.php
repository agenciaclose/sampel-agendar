<?php

namespace Agencia\Close\Controllers\Site\Relatorios;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\Relatorios;


class RelatoriosController extends Controller
{

    public function visitas($params)
    {
        $this->setParams($params);

        $perguntas = new Relatorios();
        $perguntas = $perguntas->getFeedbacksPerguntas()->getResult();

        $i = 0;
        foreach ($perguntas as $pergunta) {
            $feedbacks = new Relatorios();
            $perguntas[$i]['estatisticas'] = $feedbacks->getFeedbacksList($pergunta['pergunta'])->getResult();
            $i++;
        }

        $this->render('pages/relatorios/visitas.twig', ['menu' => 'relatorios', 'perguntas' => $perguntas]);
    }

}