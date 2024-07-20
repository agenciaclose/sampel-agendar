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
        $read->FullRead("SELECT * FROM feedback_perguntas ORDER BY ordem,id ASC");
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
        $read->FullRead("SELECT v.*, u.*, v.id AS visita_id, (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id) AS total_inscricao,
                        (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id AND presenca = 'Sim') AS presencas,
                        (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id AND certificado = 'Sim') AS certificados
                        FROM visitas AS v
                        INNER JOIN usuarios AS u ON u.id = v.id_empresa
                        WHERE v.`status_visita` = 'Concluido' ORDER BY v.`data` DESC");
        return $read;
    }

    public function getFeedbacksPerguntas(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `feedback_perguntas`");
        return $read;
    }
    
    public function getFeedbacksList($id_visita, $pergunta): Read
    {
        $read = new Read();
        $read->FullRead(" SELECT pergunta, resposta, COUNT(resposta) AS qtd FROM feedback WHERE `id_visita` = :id_visita AND `pergunta` = :pergunta GROUP BY resposta ORDER BY qtd DESC ", "id_visita={$id_visita}&pergunta={$pergunta}");
        return $read;
    }

    public function getFeedbacksPerguntasOrdenar($params)
    {   
        foreach ($params['order'] as $index => $id) {
            $read = new Read();
            $read->FullRead("UPDATE `feedback_perguntas` SET `ordem` = :order WHERE id = :id", "order={$index}&id={$id}");
        }
    }
}