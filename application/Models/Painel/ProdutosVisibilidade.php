<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Conn\Delete;
use Agencia\Close\Models\Model;

class ProdutosVisibilidade extends Model
{
    public function getAll()
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM visibilidades ORDER BY nome ASC");
        return $read;
    }

    public function getById($id)
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM visibilidades WHERE id = :id", "id={$id}");
        return $read;
    }

    public function create($params)
    {
        $create = new Create();
        $create->ExeCreate('visibilidades', $params);
        return $create;
    }

    public function update($id, $params)
    {
        $update = new Update();
        $update->ExeUpdate('visibilidades', $params, 'WHERE id = :id', "id={$id}");
        return $update;
    }

    public function delete($id)
    {
        $delete = new Delete();
        $delete->ExeDelete('visibilidades', 'WHERE id = :id', "id={$id}");
        return $delete;
    }
} 