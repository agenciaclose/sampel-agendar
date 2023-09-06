<?php

namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Delete;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;
use mysql_xdevapi\Result;

class PalestrasPainel extends Model
{
    public function getPalestrasList(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT p.*, (SELECT COUNT(id) FROM palestras_participantes WHERE id_palestra = p.id) AS inscricoes FROM palestras AS p ORDER BY p.id DESC");
        return $read;
    }

    public function getPalestraID($id_palestra): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM palestras WHERE id = :id_palestra ORDER BY id DESC", "id_palestra={$id_palestra}");
        return $read;
    }

    public function saveCadastro($params)
    {
        $create = new Create();
        $create->ExeCreate('palestras', $params);
        return $create->getResult();
    }

    public function saveEditar($params)
    {
        $update = new Update();
        $update->ExeUpdate('palestras', $params, 'WHERE id = :id', "id={$params['id']}");
        return $update;
    
    }

    public function saveCadastroParticipante($params)
    {
        $create = new Create();
        $create->ExeCreate('palestras_participantes', $params);
        return $create->getResult();
    }

    public function saveEditarParticipante($params)
    {
        $update = new Update();
        $update->ExeUpdate('palestras_participantes', $params, 'WHERE id = :id', "id={$params['id']}");
        return $update;
    
    }

    public function saveExcluirParticipante($params)
    {
        $read = new Read();
        $read->FullRead("DELETE FROM `palestras_participantes` WHERE id = :id", "id={$params['id']}");
        return $read;
    
    }

    public function listarInscricoes($id_palestra): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM palestras_participantes WHERE id_palestra = :id_palestra ORDER BY id DESC", "id_palestra={$id_palestra}");
        return $read;
    }

    public function getParticipanteID($id_inscricao): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM palestras_participantes WHERE id = :id_inscricao ORDER BY id DESC", "id_inscricao={$id_inscricao}");
        return $read;
    }


}