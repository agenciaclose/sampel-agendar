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
                case 'Monday': $day = 'na Segunda'; break;
                case 'Tuesday': $day = 'na Terça'; break;
                case 'Wednesday': $day = 'na Quarta'; break;
                case 'Thursday': $day = 'na Quinta'; break;
                case 'Friday': $day = 'na Sexta'; break;
                case 'Saturday': $day = 'no Sábado'; break;
                case 'Sunday': $day = 'no Domingo'; break;
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