<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class Agendar extends Model
{

    public function saveCadastro($params)
    {
        $params['id_empresa'] = $_SESSION['sampel_user_id'];
        $create = new Create();
        $create->ExeCreate('visitas', $params);
        return $create->getResult();
    }

    public function saveEditar($params)
    {
        $update = new Update();
        $update->ExeUpdate('visitas', $params, 'WHERE id = :id', "id={$params['id']}");
        return $update;
    
    }

}