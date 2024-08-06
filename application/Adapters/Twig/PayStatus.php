<?php

namespace Agencia\Close\Adapters\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PayStatus extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('payStatus', [$this, 'payStatus']),
        ];
    }

    public function payStatus($status): string
    {
        switch ($status) {
            case 'Pendente': $return = '<i class="fa-solid fa-shield-exclamation"></i> Pendente'; break;
            case 'Aprovado': $return = '<i class="fa-solid fa-shield-check"></i> Aprovado'; break;
            case 'Recusado': $return = '<i class="fa-solid fa-shield-xmark"></i> Recusado'; break;
            case 'Revisar': $return = '<i class="fa-solid fa-shield-keyhole"></i> Revisar'; break;
        }

        return $return;
    }
}