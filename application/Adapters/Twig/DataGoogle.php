<?php

namespace Agencia\Close\Adapters\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DataGoogle extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('DataGoogle', [$this, 'DataGoogle']),
        ];
    }

    public function DataGoogle($dataInicio, $dataFim)
    {
        $formatoEntrada = 'd/m/Y H:i';
        $formatoSaida = 'Ymd\THis\Z';

        // Criando objetos DateTime com as datas de início e fim
        $inicio = \DateTime::createFromFormat($formatoEntrada, $dataInicio, new \DateTimeZone('UTC'));
        $fim = \DateTime::createFromFormat($formatoEntrada, $dataFim, new \DateTimeZone('UTC'));

        // Formatando as datas para o formato do Google Calendar
        $inicioFormatado = $inicio->format($formatoSaida);
        $fimFormatado = $fim->format($formatoSaida);

        return "$inicioFormatado/$fimFormatado";
    }
}