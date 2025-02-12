<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class PatrociniosPainel extends Model
{
    public function getPatrocinios($id_fornecedor = ''): Read
    {
        $where = '';

        if(!empty($_GET['ano_patrocinio'])){
            $where .= " AND data_patrocinio_inicio like '%".$_GET['ano_patrocinio']."%'";
        }

        if($id_fornecedor != ''){
            $where .= " AND fornecedores.id = '".$id_fornecedor."'";
        }

        $read = new Read();
        $read->FullRead("SELECT patrocinios.*,
        (SELECT SUM(valor_orcamento) FROM orcamentos WHERE id_evento = patrocinios.id AND tipo_evento = 'patrocinios') AS total_orcamento,
        (SELECT SUM(valor_total_pedido) FROM pedidos WHERE id_evento = patrocinios.id AND tipo_evento = 'patrocinios') AS total_pedido,
        (
        IFNULL((SELECT SUM(valor_orcamento) FROM orcamentos WHERE id_evento = patrocinios.id AND tipo_evento = 'patrocinios'), 0) +
        IFNULL((SELECT SUM(valor_total_pedido) FROM pedidos WHERE id_evento = patrocinios.id AND tipo_evento = 'patrocinios'), 0)
        ) AS total_gastos,
        fornecedores.empresa_fantasia,
        patrocinios.nome_patrocinio AS title
        FROM patrocinios
        LEFT JOIN fornecedores ON fornecedores.id = patrocinios.id_fornecedor
        WHERE status_patrocinio = 'Ativo' $where
        ORDER BY nome_patrocinio ASC");
        return $read;
    }

    public function getPatrocinioID($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM patrocinios WHERE id = :id", "id={$id}");
        return $read;
    }

    public function addProductSave($params)
    {   
        $create = new Create();
        $create->ExeCreate('patrocinios', $params);
        return $create;
    }

    public function editProductSave($params)
    {
        $id = $params['id'];
        unset($params['id']);
    
        $update = new Update();
        $update->ExeUpdate('patrocinios', $params, 'WHERE id = :id', "id={$id}");
        return $update;
    }

    public function getPatrocinioStatus($params)
    {
        $read = new Read();
        $read->FullRead("UPDATE `patrocinios` SET `status_patrocinio` = :status_patrocinio WHERE id = :id", "status_patrocinio={$params['status_patrocinio']}&id={$params['id']}");
        return $read;
    }

    public function addPatrocinioSave($params)
    {
        $create = new Create();
        $params['id_user'] = $_SESSION['sampel_user_id'];
        $create->ExeCreate('patrocinios', $params);
        return $create->getResult();
    }

    public function editPatrocinioSave($params)
    {
        $id = $params['id'];
        unset($params['id']);
        $update = new Update();
        $update->ExeUpdate('patrocinios', $params, 'WHERE id = :id', "id={$id}");
        return $update;
    }

}