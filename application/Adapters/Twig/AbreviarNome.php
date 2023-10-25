<?php

namespace Agencia\Close\Adapters\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AbreviarNome extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('AbreviarNome', [$this, 'AbreviarNome']),
        ];
    }

    public function abreviarNome($nomeCompleto): string
    {
        $ignorar = ['da', 'das', 'de', 'des', 'di', 'dis', 'do', 'dos'];
        $partes = explode(" ", $nomeCompleto);
    
        $nome = isset($partes[0]) ? $partes[0] : "";
        $sobrenome = "";
    
        for ($i = count($partes) - 1; $i > 0; $i--) {
            if (!in_array(strtolower($partes[$i]), $ignorar)) {
                $sobrenome = $partes[$i];
                break;
            }
        }
    
        return $nome.' '.$sobrenome;
    }
}