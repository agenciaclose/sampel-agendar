<?php

namespace Agencia\Close\Adapters\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UserStatus extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('userStatus', [$this, 'userStatus']),
        ];
    }

    public function userStatus($status): string
    {
        switch ($status) {
            case '1': $return = '<div class="badge badge-pill badge-light-success">Ativo</div>'; break;
            case '2': $return = '<div class="badge badge-pill badge-light-warning">Pendente</div>'; break;
            case '3': $return = '<div class="badge badge-pill badge-light-danger">Inativo</div>'; break;
        }

        return $return;
    }
}