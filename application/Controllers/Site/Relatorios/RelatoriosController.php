<?php

namespace Agencia\Close\Controllers\Site\Relatorios;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\Relatorios;


class RelatoriosController extends Controller
{

    public function visitas($params)
    {
        $this->setParams($params);

        $model = new Relatorios();
        
        $ano = '';
        if(isset($_GET['ano'])){
            $ano = $_GET['ano'];
        }

        $anos = $model->getAnosVisitas()->getResult();
        $visitas = $model->getAllVisitas($ano)->getResult();
        $numeros = $model->getAllNumeros($ano)->getResult();
        $total = $this->tratarNumeros($numeros);
        $total_setor = $model->getTotalSetor($ano)->getResult();
        $total_setor_equipe = $model->getTotalSetorEquipe($ano)->getResult();
        $total_cidade = $model->getTotalCidade($ano)->getResult();
        $total_equipe = $model->getTotalEquipeByVisita($ano)->getResult();
        $perguntas = $model->getFeedbacksPerguntas()->getResult();

        $i = 0;
        foreach ($perguntas as $pergunta) {

            $perguntas[$i]['estatisticas'] = $model->getFeedbacksList($pergunta['pergunta'], $ano)->getResult();
            
            if($pergunta['tipo'] == 'Texto'){
                $x = 0;
                foreach ($perguntas[$i]['estatisticas'] as $estatisticas){
                    $perguntas[$i]['estatisticas'][$x]['pessoas'] = $model->getFeedbacksListPessoas($estatisticas['resposta'], $ano)->getResult();
                    $x++;
                }
            }

            $i++;
        }
    
        $this->render('pages/relatorios/visitas.twig', [
            'menu' => 'relatorios',
            'visitas' => $visitas,
            'numeros' => $numeros,
            'total' => $total,
            'total_setor' => $total_setor,
            'total_setor_equipe' => $total_setor_equipe,
            'total_cidade' => $total_cidade,
            'total_equipe' => $total_equipe,
            'perguntas' => $perguntas,
            'anos' => $anos,
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