<?php

namespace Agencia\Close\Adapters\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PedidoStatus extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('pedidoStatus', [$this, 'pedidoStatus']),
        ];
    }

    public function pedidoStatus($status): string
    {
        switch ($status) {
            case '0': $return = '<i class="fa-solid fa-shield-xmark"></i> Recusado'; break;
            case '1': $return = '<i class="fa-solid fa-shield-exclamation"></i> Pendente'; break;
            case '2': $return = '<i class="fa-solid fa-shield-check"></i> Aprovado'; break;
            case '3': $return = '<i class="fa-solid fa-shield-keyhole"></i> Revisar'; break;
            case '4': $return = '<i class="fa-solid fa-dolly"></i> Em Separação'; break;
            case '5': $return = '<i class="fa-solid fa-truck-container"></i> Transportadora'; break;
            case '6': $return = '<i class="fa-solid fa-truck-fast"></i> Correios'; break;
            case '7': $return = '<i class="fa-solid fa-people-carry-box"></i> Nosso Carro'; break;
            case '8': $return = '<i class="fa-solid fa-person-carry-box"></i> Retirado'; break;
            case '10': $return = '<i class="fa-solid fa-shield-quartered"></i> Concluído'; break;
        }

        return $return;
    }
}