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
        $read = new Read();
        $read->FullRead("SELECT * FROM eventos ORDER BY `data_evento_inicio` ASC");
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

    public function productStatus($params)
    {
        $read = new Read();
        $read->FullRead("UPDATE `eventos` SET `status` = :status WHERE id = :id", "status={$params['status']}&id={$params['id']}");
        return $read;
    }

}