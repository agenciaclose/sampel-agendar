<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class CargosPainel extends Model
{
    public function getCargosList(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM roles ORDER BY `id` DESC");
        return $read;
    }

    public function getCargoID($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM roles WHERE id = :id", "id={$id}");
        return $read;
    }

    public function addRoleSave($role, $permission)
    {
        $params['role'] = $role;
        $params['permissions'] = $permission;
    
        $create = new Create();
        $create->ExeCreate('roles', $params);
        return $create->getResult();
    }

    public function editRoleSave($id, $role, $permission)
    {
        $params['role'] = $role;
        $params['permissions'] = $permission;
    
        $update = new Update();
        $update->ExeUpdate('roles', $params, 'WHERE id = :id', "id={$id}");
        return $update;
    }

    public function deleteRole($id)
    {   
        $read = new Read();
        $read->FullRead("DELETE FROM roles WHERE id = :id", "id={$id}");
        return $read;
    }

    public function getCargosUser($id_user): Read
    {
        $read = new Read();
        $read->FullRead("SELECT id_role FROM usuario_roles WHERE id_user = :id_user ORDER BY `id_role` DESC", "id_user={$id_user}");
        return $read;
    }
}