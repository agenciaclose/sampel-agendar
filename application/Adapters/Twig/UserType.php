<?php

namespace Agencia\Close\Adapters\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UserType extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('userType', [$this, 'userType']),
        ];
    }

    public function userType($status): string
    {
        switch ($status) {
            case '1': $return = '<div class="badge badge-pill badge-light-danger">Administrador</div>'; break;
            case '2': $return = '<div class="badge badge-pill badge-light-warning">Consultor</div>'; break;
            case '3': $return = '<div class="badge badge-pill badge-light-info">Cliente</div>'; break;
        }

        return $return;
    }
}