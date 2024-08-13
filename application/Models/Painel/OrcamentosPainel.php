<?php

namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Conn\Read;
use Agencia\Close\Models\Model;

class OrcamentosPainel extends Model
{

    public function getVisitasList(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, u.*, v.id AS visita_id
                        FROM visitas AS v
                        INNER JOIN usuarios AS u ON u.id = v.id_empresa WHERE v.`status_visita` = 'Concluido' ORDER BY v.`data` DESC");
        return $read;
    }

    public function getTerms(): Read
    {
        if(!empty($_GET["q"])){
            $term = "WHERE orcamento like '%".$_GET["q"]."%'";
        }else{
            $term = "";
        }
        $read = new Read();
        $read->FullRead("SELECT * FROM orcamentos $term ORDER BY orcamento ASC");
        return $read;
    }

    public function getOrcamentosByEvento($id_evento, $tipo_evento): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM orcamentos WHERE id_evento = :id_evento AND tipo_evento = :tipo_evento", "id_evento={$id_evento}&tipo_evento={$tipo_evento}");
        return $read;
    }

    public function getOrcamentosID($id_evento, $tipo_evento, $id_edit): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM orcamentos WHERE id_evento = :id_evento AND tipo_evento = :tipo_evento AND id = :id_edit", "id_evento={$id_evento}&tipo_evento={$tipo_evento}&id_edit={$id_edit}");
        return $read;
    }

    public function addOrcamentoSave($params)
    {   
        $params['valor_orcamento'] = str_replace(',', '.', str_replace('.', '', $params['valor_orcamento']));

        $params['id_user'] = $_SESSION['sampel_user_id'];

        $create = new Create();
        $create->ExeCreate('orcamentos', $params);
        return $create;
    }

    public function editOrcamentoSave($params)
    {
        $id = $params['id'];
        unset($params['id']);

        $params['id_user'] = $_SESSION['sampel_user_id'];

        $params['valor_orcamento'] = str_replace(',', '.', str_replace('.', '', $params['valor_orcamento']));
    
        $update = new Update();
        $update->ExeUpdate('orcamentos', $params, 'WHERE id = :id', "id={$id}");
        return $update;
    }

    public function removeOrcamento($id): Read
    {
        $read = new Read();
        $read->FullRead("DELETE FROM `orcamentos` WHERE `id`= :id", "id={$id}");
        return $read;
    }

}