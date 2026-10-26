<?php

namespace Agencia\Close\Adapters\Twig;

use Agencia\Close\Helpers\Document\DocumentoIdentidade;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UserCPF extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('UserCPF', [$this, 'UserCPF']),
            new TwigFilter('DocumentoIdentidade', [$this, 'DocumentoIdentidade']),
        ];
    }

    public function UserCPF($cpf): string
    {
        return DocumentoIdentidade::formatMasked($cpf);
    }

    public function DocumentoIdentidade($documento): string
    {
        return DocumentoIdentidade::format($documento);
    }
}
