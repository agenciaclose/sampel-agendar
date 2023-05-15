<?php

namespace Agencia\Close\Models\Admin;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class ServicosTipos extends Model
{
	private string $table = 'tipo_servicos';

    public function getTipos(): Read
    {
        $read = new Read();
        $read->ExeRead($this->table);
        return $read;
    }

        public function getTiposID($id): Read
    {
        $read = new Read();
        $read->ExeRead($this->table, 'WHERE id = :id', "id={$id}");
        return $read;
    }

    public function updateTipo(array $data): Update
    {
        $dataToSave = $this->tratamento($data);
        $update = new Update();
        $update->ExeUpdate($this->table, $dataToSave, 'WHERE id = :id', "id={$data['id']}");
        return $update;
    }

    public function createTipo(array $data): Create
    {
        $dataToSave = $this->tratamento($data);
        $create = new Create();
        $create->ExeCreate($this->table, $dataToSave);
        return $create;
    }

    public function tratamento(array $data): array
    {
        unset($data['id']);
        return $data;
    }


}