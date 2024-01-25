<?php

namespace Agencia\Close\Controllers\Site\Relatorios;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\Relatorios;


class RelatoriosController extends Controller
{

    public function visitas($params)
    {
        $this->setParams($params);

        $visitas = new Relatorios();
        $visitas = $visitas->getAllVisitas()->getResult();

        $numeros = new Relatorios();
        $numeros = $numeros->getAllNumeros()->getResult();
        $total = $this->tratarNumeros($numeros);

        $total_setor = new Relatorios();
        $total_setor = $total_setor->getTotalSetor()->getResult();
    
        $this->render('pages/relatorios/visitas.twig', ['menu' => 'relatorios', 'visitas' => $visitas, 'numeros' => $numeros, 'total' => $total, 'total_setor' => $total_setor]);
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