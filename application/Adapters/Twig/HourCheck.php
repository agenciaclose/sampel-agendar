<?php

namespace Agencia\Close\Adapters\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class HourCheck extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('HourCheck', [$this, 'HourCheck']),
        ];
    }

    public function hourCheck($hora, $id_user): string
    {
        return '';
    }
}