<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Models\Model;

class Relatorios extends Model
{

    public function getFeedbacksPerguntas(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `feedback_perguntas`");
        return $read;
    }
    
    public function getFeedbacksList($pergunta): Read
    {
        $read = new Read();
        $read->FullRead(" SELECT pergunta, resposta, COUNT(resposta) AS qtd FROM feedback WHERE `pergunta` = :pergunta GROUP BY resposta ORDER BY qtd DESC ", "pergunta={$pergunta}");
        return $read;
    }
    
}