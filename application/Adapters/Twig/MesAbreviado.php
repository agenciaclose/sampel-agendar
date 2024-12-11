<?php

namespace Agencia\Close\Adapters\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MesAbreviado extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('MesAbreviado', [$this, 'MesAbreviado']),
        ];
    }

    public function MesAbreviado($mes): string
    {
        $mes = str_pad($mes, 2, "0", STR_PAD_LEFT);
        
        switch ($mes) {
            case '01': $mes = 'JAN'; break;
            case '02': $mes = 'FEV'; break;
            case '03': $mes = 'MAR'; break;
            case '04': $mes = 'ABR'; break;
            case '05': $mes = 'MAI'; break;
            case '06': $mes = 'JUN'; break;
            case '07': $mes = 'JUL'; break;
            case '08': $mes = 'AGO'; break;
            case '09': $mes = 'SET'; break;
            case '10': $mes = 'OUT'; break;
            case '11': $mes = 'NOV'; break;
            case '12': $mes = 'DEZ'; break;
        }
        return $mes;
    }
}