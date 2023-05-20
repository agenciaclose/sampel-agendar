<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Models\Model;

class Visitas extends Model
{

    public function listarVisitas(): read
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, u.*, v.id AS visita_id
						FROM visitas AS v
						INNER JOIN usuarios AS u ON u.id = v.id_empresa
						WHERE v.id_empresa = :user_id ORDER BY v.id DESC", "user_id={$_SESSION['sampel_user_id']}");
        return $read;
    }

    public function listarVisitaID($visita_id): read
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, u.*, v.id AS visita_id
        				FROM visitas AS v
        				INNER JOIN usuarios AS u ON u.id = v.id_empresa
        				WHERE v.id = :visita_id ORDER BY v.id DESC", "visita_id={$visita_id}");
        return $read;
    }

    public function inscricaoCadastro($params)
    {
        $read = new Read();
        $read->FullRead("INSERT INTO `visitas_inscricoes` (`id_visita`, `id_user`) VALUES (:id_visita, :id_user)", "id_visita={$params['visita_id']}&id_user={$params['sampel_user_id']}");
        return $read;

    }

    public function checkCadastro($cpf, $email, $visita_id)
    {
        $read = new Read();
        $read->FullRead("SELECT v.*
                        FROM visitas_inscricoes AS v
                        INNER JOIN usuarios AS u ON u.id = v.id_user
                        WHERE v.id_visita = :visita_id AND u.email = :email AND u.cpf = :cpf", 
                        "visita_id={$visita_id}&email={$email}&cpf={$cpf}");
        return $read;
    }

}