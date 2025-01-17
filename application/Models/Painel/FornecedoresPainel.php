<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class FornecedoresPainel extends Model
{
    public function lista(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM fornecedores ORDER BY id DESC");
        return $read;
    }

    public function getFornecedorID($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM fornecedores WHERE id= :id ORDER BY id DESC", "id={$id}");
        return $read;
    }

    public function addFornecedorSave($params): Read
    {   
        $create = new Create();
        $create->ExeCreate('fornecedores', $params);

        $read = new Read();
        $read->FullRead("SELECT * FROM fornecedores ORDER BY id DESC LIMIT 1");
        return $read;
    }

    public function editFornecedorSave($params): Update
    {
        $id = $params['id'];
        unset($params['id']);
    
        $update = new Update();
        $update->ExeUpdate('fornecedores', $params, 'WHERE id = :id', "id={$id}");
        return $update;
    }

}