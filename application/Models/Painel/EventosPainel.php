<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class EventosPainel extends Model
{
    public function getEventos(): Read
    {
        $where = '';

        if(!empty($_GET['ano_evento'])){
            $where .= " AND data_evento_inicio like '%".$_GET['ano_evento']."%'";
        }

        $read = new Read();
        $read->FullRead("SELECT *,
        (SELECT SUM(valor_orcamento) FROM orcamentos WHERE id_evento = eventos.id) AS total_orcamento,
        (SELECT SUM(valor_total_pedido) FROM pedidos WHERE id_evento = eventos.id) AS total_pedido,
        (
            IFNULL((SELECT SUM(valor_orcamento) FROM orcamentos WHERE id_evento = eventos.id), 0) +
            IFNULL((SELECT SUM(valor_total_pedido) FROM pedidos WHERE id_evento = eventos.id), 0)
        ) AS total_gastos
        FROM eventos
        WHERE status_evento = 'Ativo' $where
        ORDER BY data_evento_inicio ASC");
        return $read;
    }

    public function getEventoID($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM eventos WHERE id = :id", "id={$id}");
        return $read;
    }

    public function addProductSave($params)
    {   
        $create = new Create();
        $create->ExeCreate('eventos', $params);
        return $create;
    }

    public function editProductSave($params)
    {
        $id = $params['id'];
        unset($params['id']);
    
        $update = new Update();
        $update->ExeUpdate('eventos', $params, 'WHERE id = :id', "id={$id}");
        return $update;
    }

    public function getEventoStatus($params)
    {
        $read = new Read();
        $read->FullRead("UPDATE `eventos` SET `status_evento` = :status_evento WHERE id = :id", "status_evento={$params['status_evento']}&id={$params['id']}");
        return $read;
    }

}