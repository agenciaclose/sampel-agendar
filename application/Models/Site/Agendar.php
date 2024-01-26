<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class Agendar extends Model
{
    public function getEstados(): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM visitas_limites ORDER BY estado ASC");
        return $read;
    }

    public function getConfiguracoes(): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM configuracoes WHERE id = '1' LIMIT 1");
        return $read;
    }

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

    public function getLast(){
        $read = new Read();
        $read->FullRead("SELECT * FROM visitas ORDER BY id DESC LIMIT 1");
        return $read;
    }

    public function getMotivos(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM motivos ORDER BY `id` DESC");
        return $read;
    }

    public function listCheckEventsClose(): Read
    {
        $read = new Read();
        $read->FullRead("UPDATE `visitas` SET `status_inscricoes` = 'Fechadas' WHERE CURDATE() > DATE(data_close)");
        return $read;
    }

    public function listcheckEventsConcluido(): Read
    {
        $read = new Read();
        $read->FullRead("UPDATE `visitas` SET `status_visita` = 'Concluido' WHERE CURRENT_DATE > STR_TO_DATE(`data_visita`, '%Y-%m-%d')");
        return $read;
    }

}