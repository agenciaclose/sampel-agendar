<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Conn\Read;
use Agencia\Close\Models\Model;

class Feedback extends Model
{

    public function getPerguntas(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM feedback_perguntas ORDER BY ordem ASC");
        return $read;
    }

    public function savePerguntas($params): Create
    {
        $create = new Create();
        $create->ExeCreate('feedback_perguntas', $params);
        return $create;
    }

    public function getUserVisita($id_visita, $cpf): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM visitas_inscricoes WHERE id_visita = :id_visita AND cpf = :cpf", "id_visita={$id_visita}&cpf={$cpf}");
        return $read;
    }

    public function saveFeedback($params)
    {
        if (is_countable($params['pergunta']) && count($params['pergunta']) > 0){
            for ($i=0; $i < count($params['pergunta']); $i++) {
                $read = new Read();
                $read->FullRead("INSERT INTO `feedback` (`id_visita`, `user_codigo`, `user_cpf`, `pergunta`, `resposta`) 
                        VALUES ('".$params['id_visita']."', '".$params['user_codigo']."', '".$params['user_cpf']."', '".$params['pergunta'][$i]."', '".$params['resposta'][$i]."')");
            }
        }

        $read = new Read();
        $read->FullRead("UPDATE `visitas_inscricoes` SET `presenca` = 'Sim' WHERE `codigo` = '".$params['user_codigo']."'");
    }

    public function checkFeedback($id_visita, $cpf): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM feedback WHERE id_visita = :id_visita AND user_cpf = :user_cpf LIMIT 1", "id_visita={$id_visita}&user_cpf={$cpf}");
        return $read;
    }

}