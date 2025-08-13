<?php

namespace Agencia\Close\Adapters\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PedidoStatusColor extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('pedidoStatusColor', [$this, 'pedidoStatusColor']),
        ];
    }

    public function pedidoStatusColor($status): string
    {
        switch ($status) {
            case '0': $return = 'red'; break;
            case '1': $return = 'yellow'; break;
            case '2': $return = 'green'; break;
            case '3': $return = 'orange'; break;
            case '4': $return = 'gray'; break;
            case '5': $return = 'pink'; break;
            case '6': $return = 'orange'; break;
            case '7': $return = 'blue'; break;
            case '8': $return = 'blue'; break;
            case '10': $return = 'cyan'; break;
            case '11': $return = 'pink'; break;
        }

        return $return;
    }
}