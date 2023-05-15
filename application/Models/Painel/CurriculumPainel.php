<?php

namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class CurriculumPainel extends Model
{

	private string $table = 'usuarios';

	public function getUser($sampel_user_id): Read
    {
        $read = new Read();
        $read->ExeRead($this->table, 'WHERE id = :id', "id={$sampel_user_id}");
        return $read;
    }

    public function getServicos($sampel_user_id): Read
    {
        $read = new Read();
        $read->ExeRead('servicos', 'WHERE id_user = :id_user', "id_user={$sampel_user_id}");
        return $read;
    }

    public function getProdutos($sampel_user_id): Read
    {
        $read = new Read();
        $read->ExeRead('produtos', 'WHERE id_user = :id_user', "id_user={$sampel_user_id}");
        return $read;
    }

    public function accountUpdate(array $params)
    {
        unset($params['email']);
        $update = new Update();
        $update->ExeUpdate($this->table, $params, 'WHERE id = :id', "id={$_SESSION['sampel_user_id']}");
        return $update;
    }

    public function accountSecurity(array $params)
    {
        $params['senha'] = sha1($params['senha']);
        unset($params['confirmarsenha']);
        $update = new Update();
        $update->ExeUpdate($this->table, $params, 'WHERE id = :id', "id={$_SESSION['sampel_user_id']}");
        return $update;
    }

    public function accountTerms(array $params)
    {
        $update = new Update();
        $update->ExeUpdate($this->table, $params, 'WHERE id = :id', "id={$_SESSION['sampel_user_id']}");
        return $update;
    }

}