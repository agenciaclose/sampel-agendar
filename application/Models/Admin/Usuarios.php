<?php

namespace Agencia\Close\Models\Admin;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class Usuarios extends Model
{
	private string $table = 'usuarios';

    public function getUsuarios(): Read
    {
        $read = new Read();
        $read->ExeRead($this->table);
        return $read;
    }

    public function getStatusUpdate($status, $user)
    {
        $read = new Read();
        $read->FullRead("UPDATE `usuarios` SET `status` = :status WHERE id = :user", "status={$status}&user={$user}");
    }

    public function getUsuarioID($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM ".$this->table." WHERE id = :id", "id={$id}");
        return $read;
    }

    public function getManuais(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM manuais_admin ORDER BY ordem ASC");
        return $read;
    }


}