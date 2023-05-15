<?php

namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class CategoriasProdutosPainel extends Model
{
    public function getCategory($id_user): Read
    {
        $this->read = new Read();
        $this->read->FullRead("SELECT * FROM categorias WHERE id_user = :id_user ORDER BY nome ASC", "id_user={$id_user}");
        return $this->read;
    }

    public function catSave($params): Read
    {
        $this->read = new Read();
        $this->read->FullRead("SELECT * FROM categorias WHERE id_user = :id_user ORDER BY nome ASC", "id_user={$id_user}");
        return $this->read;

    }


}