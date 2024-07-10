<?php
namespace Agencia\Close\Models;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;

class Permissions extends Model
{
    public function getPermissions($permission, $action, $id_user): Read
    {
        $read = new Read();
        $read->FullRead("SELECT r.id, r.role, r.permissions, ur.id_user
                            FROM roles r
                            INNER JOIN usuario_roles ur ON r.id = ur.id_role
                            WHERE JSON_CONTAINS(r.permissions, :action, CONCAT('$.', :permission)) 
                            AND id_user = :id_user", 
                            "action={$action}&permission={$permission}&id_user={$id_user}");
        return $read;
    }

    public function getPermissionsUser( $id_user): Read
    {
        $read = new Read();
        $read->FullRead("SELECT r.id, r.role, r.permissions, ur.id_user
                            FROM roles r
                            INNER JOIN usuario_roles ur ON r.id = ur.id_role
                            WHERE id_user = :id_user", 
                            "id_user={$id_user}");
        return $read;
    }
}