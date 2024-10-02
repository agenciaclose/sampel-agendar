<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class EventosModel extends Model
{

    public function listarEventos(): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM eventos WHERE status_evento = 'Ativo' ORDER BY data_evento_inicio ASC", "");
        return $read;
    }

    public function listarEventosID($id): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM eventos WHERE id = :id ORDER BY id DESC", "id={$id}");
        return $read;
    }

    public function addEventoSave($params)
    {
        $create = new Create();
        $params['id_user'] = $_SESSION['sampel_user_id'];
        $create->ExeCreate('eventos', $params);
        return $create->getResult();
    }

    public function editEventoSave($params)
    {
        $id = $params['id'];
        unset($params['id']);
        $update = new Update();
        $update->ExeUpdate('eventos', $params, 'WHERE id = :id', "id={$id}");
        return $update;
    }

}