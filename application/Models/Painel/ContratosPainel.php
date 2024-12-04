<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class ContratosPainel extends Model
{
    public function getContratos(): Read
    {
        $where = '';

        if(!empty($_GET['ano_contrato'])){
            $where .= " AND data_contrato_inicio like '%".$_GET['ano_contrato']."%'";
        }

        $read = new Read();
        $read->FullRead("SELECT *,
        (SELECT SUM(valor_orcamento) FROM orcamentos WHERE id_evento = contratos.id AND tipo_evento = 'contratos') AS total_orcamento,
        (SELECT SUM(valor_total_pedido) FROM pedidos WHERE id_evento = contratos.id AND tipo_evento = 'contratos') AS total_pedido,
        (
        IFNULL((SELECT SUM(valor_orcamento) FROM orcamentos WHERE id_evento = contratos.id AND tipo_evento = 'contratos'), 0) +
        IFNULL((SELECT SUM(valor_total_pedido) FROM pedidos WHERE id_evento = contratos.id AND tipo_evento = 'contratos'), 0)
        ) AS total_gastos
        FROM contratos
        WHERE status_contrato = 'Ativo' $where
        ORDER BY nome_contrato ASC");
        return $read;
    }

    public function getContratoID($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM contratos WHERE id = :id", "id={$id}");
        return $read;
    }

    public function addProductSave($params)
    {   
        $create = new Create();
        $create->ExeCreate('contratos', $params);
        return $create;
    }

    public function editProductSave($params)
    {
        $id = $params['id'];
        unset($params['id']);
    
        $update = new Update();
        $update->ExeUpdate('contratos', $params, 'WHERE id = :id', "id={$id}");
        return $update;
    }

    public function getContratoStatus($params)
    {
        $read = new Read();
        $read->FullRead("UPDATE `contratos` SET `status_contrato` = :status_contrato WHERE id = :id", "status_contrato={$params['status_contrato']}&id={$params['id']}");
        return $read;
    }

    public function addContratoSave($params)
    {
        $create = new Create();
        $params['id_user'] = $_SESSION['sampel_user_id'];
        $create->ExeCreate('contratos', $params);
        return $create->getResult();
    }

    public function editContratoSave($params)
    {
        $id = $params['id'];
        unset($params['id']);
        $update = new Update();
        $update->ExeUpdate('contratos', $params, 'WHERE id = :id', "id={$id}");
        return $update;
    }

}