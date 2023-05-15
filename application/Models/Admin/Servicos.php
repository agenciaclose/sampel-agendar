<?php

namespace Agencia\Close\Models\Admin;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class Servicos extends Model
{
    public function getAgendamentos(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT a.*, s.titulo, u.nome AS consultor FROM agendamentos AS a
                        INNER JOIN servicos AS s ON s.id = a.itemID
                        INNER JOIN usuarios AS u ON u.id = a.id_vendedor
                        ORDER BY a.id DESC");
        return $read;
    }

    public function getAgendamentoView($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT pa.*, u.nome AS comprador, p.titulo, p.imagem, ai.dia_agendamento, ai.horario_agendamento
                        FROM agendamentos AS pa 
                        INNER JOIN usuarios AS u ON u.id = pa.id_comprador
                        INNER JOIN servicos AS p ON p.id = pa.itemID
                        LEFT JOIN (
                            SELECT id_pedido,dia_agendamento,horario_agendamento
                            FROM agendamentos_itens ORDER BY `horario_agendamento` DESC
                        ) ai ON pa.id = ai.id_pedido
                        WHERE pa.`id` = :id ORDER BY pa.id DESC", "id={$id}");
        return $read;
    }

    public function getNotasAgendamento($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `agendamentos_notas` WHERE id_agendamento = :id_agendamento", "id_agendamento={$id}");
        return $read;
    }

    public function getHorariosAgendamento($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `agendamentos_itens` WHERE id_pedido = :id_pedido", "id_pedido={$id}");
        return $read;
    }

}