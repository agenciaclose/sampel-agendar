<?php

namespace Agencia\Close\Controllers\Site\Relatorios;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\RelatoriosPalestras;


class RelatoriosPalestrasController extends Controller
{

    public function visitas($params)
    {
        $this->setParams($params);

        $visitas = new RelatoriosPalestras();
        $visitas = $visitas->getAllVisitas()->getResult();

        $numeros = new RelatoriosPalestras();
        $numeros = $numeros->getAllNumeros()->getResult();
        $total = $this->tratarNumeros($numeros);

        $total_setor = new RelatoriosPalestras();
        $total_setor = $total_setor->getTotalSetor()->getResult();

        $total_cidade = new RelatoriosPalestras();
        $total_cidade = $total_cidade->getTotalCidade()->getResult();

        $perguntas = new RelatoriosPalestras();
        $perguntas = $perguntas->getFeedbacksPerguntas()->getResult();

        $i = 0;
        foreach ($perguntas as $pergunta) {

            $feedbacks = new RelatoriosPalestras();
            $perguntas[$i]['estatisticas'] = $feedbacks->getFeedbacksList($pergunta['pergunta'])->getResult();
            
            if($pergunta['tipo'] == 'Texto'){
                $x = 0;
                foreach ($perguntas[$i]['estatisticas'] as $estatisticas){
                    $perguntas[$i]['estatisticas'][$x]['pessoas'] = $feedbacks->getFeedbacksListPessoas($estatisticas['resposta'])->getResult();
                    $x++;
                }
            }

            $i++;
        }
    
        $this->render('pages/relatorios_palestras/palestras.twig', [
            'menu' => 'relatorios',
            'visitas' => $visitas,
            'numeros' => $numeros,
            'total' => $total,
            'total_setor' => $total_setor,
            'total_cidade' => $total_cidade,
            'perguntas' => $perguntas
        ]);
    }


    public function tratarNumeros ($numeros)
    {
        $totais = [
            'total_inscritos' => 0,
            'total_confirmados' => 0,
            'total_no_confirmados' => 0,
            'total_certificados' => 0
        ];
    
        foreach ($numeros as $registro) {
            $totais['total_inscritos'] += $registro['total_inscritos'];
            $totais['total_confirmados'] += $registro['total_confirmados'];
            $totais['total_no_confirmados'] += $registro['total_no_confirmados'];
            $totais['total_certificados'] += $registro['total_certificados'];
        }
    
        return $totais;
    }

}