<?php

namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Conn\Read;
use Agencia\Close\Models\Model;

class FeedbackPainel extends Model
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

    public function excluirPergunta($id): read
    {
        $read = new Read();
        $read->FullRead("DELETE FROM `feedback_perguntas` WHERE `id` = :id", "id={$id}");
        return $read;
    }

    public function getVisitasList(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, u.*, v.id AS visita_id, (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id) AS total_inscricao
                        FROM visitas AS v
                        INNER JOIN usuarios AS u ON u.id = v.id_empresa WHERE status_visita = 'Aprovado' ORDER BY v.id DESC");
        return $read;
    }

}