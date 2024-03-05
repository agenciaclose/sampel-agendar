<?php

namespace Agencia\Close\Adapters\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DayTranslate extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('dayTranslate', [$this, 'dayTranslate']),
        ];
    }

    public function dayTranslate($day, $invert = false): string
    {
        if($invert == false){
            switch ($day) {
                case 'Monday': $day = 'Segunda'; break;
                case 'Tuesday': $day = 'Terça'; break;
                case 'Wednesday': $day = 'Quarta'; break;
                case 'Thursday': $day = 'Quinta'; break;
                case 'Friday': $day = 'Sexta'; break;
                case 'Saturday': $day = 'Sábado'; break;
                case 'Sunday': $day = 'Domingo'; break;
                default;
            }
        }else{
            switch ($day) {
                case 'Segunda': $day = 'Mon'; break;
                case 'Terça': $day = 'Tue'; break;
                case 'Quarta': $day = 'Wed'; break;
                case 'Quinta': $day = 'Thu'; break;
                case 'Sexta': $day = 'Fri'; break;
                case 'Sábado': $day = 'Sat'; break;
                case 'Domingo': $day = 'Sun'; break;
            }
        }

        return $day;
    }
}