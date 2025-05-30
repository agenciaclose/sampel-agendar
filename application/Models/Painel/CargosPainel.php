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

    public function getCargosListGerenciavel(): Read
    {
        if($_SESSION['sampel_user_tipo'] == 1){
            $gerenciavel = "";
        }else{
            $gerenciavel = "WHERE gerenciavel = 'S'";
        }

        $read = new Read();
        $read->FullRead("SELECT * FROM roles $gerenciavel ORDER BY `id` DESC");
        return $read;
    }

    public function getCargoID($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM roles WHERE id = :id", "id={$id}");
        return $read;
    }

    public function addRoleSave($role, $permission, $gerenciavel)
    {
        $params['role'] = $role;
        $params['permissions'] = $permission;
        $params['gerenciavel'] = $gerenciavel;
    
        $create = new Create();
        $create->ExeCreate('roles', $params);
        return $create->getResult();
    }

    public function editRoleSave($id, $role, $permission, $gerenciavel)
    {
        $params['role'] = $role;
        $params['permissions'] = $permission;
        $params['gerenciavel'] = $gerenciavel;

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