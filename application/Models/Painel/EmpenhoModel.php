<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class EmpenhoModel extends Model
{

    public function getEmpenho($tipo, $ano): Read
    {
        if ($tipo == 'Eventos'){
            $tipo = 'Feiras e Eventos';
        }
        $read = new Read();
        $read->FullRead("SELECT * FROM empenhos WHERE tipo = :tipo AND data_empenho = :ano ORDER BY `data_empenho` DESC", "tipo={$tipo}&ano={$ano}");
        return $read;
    }

    public function getEmpenhos(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM empenhos ORDER BY `data_empenho` DESC");
        return $read;
    }

    public function getEmpenhoID($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM empenhos WHERE id = :id ORDER BY `data_empenho` DESC", "id={$id}");
        return $read;
    }

    public function addEmpenhoSave($params)
    {   
        $params['valor_empenho'] = str_replace(',', '.', str_replace('.', '', $params['valor_empenho']));
        $params['id_user'] = $_SESSION['sampel_user_id'];

        $create = new Create();
        $create->ExeCreate('empenhos', $params);
        
        return $create;
    }

    public function editEmpenhoSave($params)
    {
        $id = $params['id'];
        unset($params['id']);

        $params['id_user'] = $_SESSION['sampel_user_id'];
        $params['valor_empenho'] = str_replace(',', '.', str_replace('.', '', $params['valor_empenho']));

        $update = new Update();
        $update->ExeUpdate('empenhos', $params, 'WHERE id = :id', "id={$id}");
        return $update;
    }

    public function removeEmpenho($id): Read
    {
        $read = new Read();
        $read->FullRead("DELETE FROM `empenhos` WHERE `id`= :id", "id={$id}");
        return $read;
    }

    public function getPedidos($ano): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM pedidos WHERE tipo_evento = 'extra' AND YEAR(date_create) = :ano", "ano={$ano}");
        return $read;
    }

    public function getVisitasListAprovadas($ano): Read
    {
        $read = new Read();
        $read->FullRead("SELECT id FROM visitas WHERE status_visita not in ('Cancelado', 'Recusado') AND YEAR(data_visita) = :ano ORDER BY id DESC", "ano={$ano}");
        return $read;
    }

    public function getPalestrasList($ano): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM palestras WHERE YEAR(data_palestra) = :ano ORDER BY id DESC", "ano={$ano}");
        return $read;
    }

    public function getEventos($ano): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM eventos WHERE status_evento = 'Ativo' AND YEAR(data_evento_inicio) = :ano", "ano={$ano}");
        return $read;
    }

    public function getPatrocinios($ano): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM patrocinios WHERE status_patrocinio = 'Ativo' AND YEAR(data_patrocinio_inicio) = :ano", "ano={$ano}");
        return $read;
    }

}