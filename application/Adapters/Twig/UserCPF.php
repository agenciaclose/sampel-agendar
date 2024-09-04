<?php

namespace Agencia\Close\Adapters\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UserCPF extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('UserCPF', [$this, 'UserCPF']),
        ];
    }

    public function UserCPF($cpf): string
    {
        // Remove todos os caracteres que não sejam números
        $cpf = preg_replace("/[^0-9]/", "", $cpf);

        // Oculta os cinco números centrais do CPF
        $cpf = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.***.***-\$4", $cpf);

        return $cpf;
    }
}