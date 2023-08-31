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
						WHERE v.id_empresa = :user_id ORDER BY v.`data` DESC", "user_id={$_SESSION['sampel_user_id']}");
        return $read;
    }

    public function listarVisitasUser(): read
    {
        $read = new Read();
        $read->FullRead("SELECT *, v.id AS visita_id FROM visitas_inscricoes AS vi
                        INNER JOIN visitas AS v ON v.id = vi.id_visita
                        INNER JOIN usuarios AS u ON u.id = v.id_empresa
                        WHERE vi.id_user = :user_id ORDER BY vi.`data` DESC", "user_id={$_SESSION['sampel_user_id']}");
        return $read;
    }

    public function listarVisitaID($id_visita): read
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, u.*, v.id AS visita_id
        				FROM visitas AS v
        				INNER JOIN usuarios AS u ON u.id = v.id_empresa
        				WHERE v.id = :id_visita ORDER BY v.`data` DESC", "id_visita={$id_visita}");
        return $read;
    }

    public function getInscricao($id_visita): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM visitas_inscricoes AS vi
                        INNER JOIN visitas AS v ON v.id = vi.id_visita
                        INNER JOIN usuarios AS u ON u.id = vi.id_user
                        WHERE vi.id_visita = :id_visita AND vi.id_user = :user_id ORDER BY vi.`data` DESC", "id_visita={$id_visita}&user_id={$_SESSION['sampel_user_id']}");
        return $read;
    }
    
    public function getInscricaoByCode($codigo): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM visitas_inscricoes AS vi
                        INNER JOIN visitas AS v ON v.id = vi.id_visita
                        INNER JOIN usuarios AS u ON u.id = vi.id_user
                        WHERE vi.codigo = :codigo ORDER BY vi.`data` DESC", "codigo={$codigo}");
        return $read;
    }

    public function inscricaoCadastro($params)
    {
        $read = new Read();
        $codigo = $this->genCode($params['id_visita']);
        $read->FullRead("INSERT INTO `visitas_inscricoes` (`id_visita`, `id_user`, `codigo`) VALUES (:id_visita, :id_user, :codigo)", "id_visita={$params['id_visita']}&id_user={$params['sampel_user_id']}&codigo={$codigo}");
        return $read;

    }

    public function inscricaoCadastroQRcode($params)
    {
        $read = new Read();

        $read->FullRead("UPDATE `visitas` SET `inscricoes` = (`inscricoes` + 1) WHERE `id` = :id_visita", "id_visita={$params['id_visita']}");

        $read->FullRead("UPDATE `visitas_inscricoes` SET `qrcode` = :qrcode WHERE `id_visita` = :id_visita AND `id_user` = :id_user", "qrcode={$params['qrcode']}&id_visita={$params['id_visita']}&id_user={$params['id_user']}");


        return $read;
    }

    public function checkCadastro($cpf, $email, $id_visita)
    {
        $read = new Read();
        $read->FullRead("SELECT v.*
                        FROM visitas_inscricoes AS v
                        INNER JOIN usuarios AS u ON u.id = v.id_user
                        WHERE v.id_visita = :visita_id AND u.email = :email AND u.cpf = :cpf", 
                        "visita_id={$id_visita}&email={$email}&cpf={$cpf}");
        return $read;
    }

    function genCode($id_visita) { 

        $chars = "ABCDEFGHIJKMNOPQRSTUVWXYZ023456789"; 
        srand((double)microtime() * 1000000); 
        $i = 0; 
        $pass = ''; 

        while ($i <= 5) {
            $num = rand() % 33; 
            $tmp = substr($chars, $num, 1); 
            $pass = $pass . $tmp; 
            $i++; 
        } 

        return $id_visita.$pass; 

    } 

}