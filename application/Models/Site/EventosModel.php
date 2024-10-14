<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class EventosModel extends Model
{

    public function listarEventos(): read
    {
        $read = new Read();
        $read->FullRead("SELECT e.*, u.nome as responsavel FROM eventos as e
        JOIN usuarios AS u ON u.id = e.id_user
        WHERE e.status_evento = 'Ativo'
        ORDER BY
        CASE WHEN e.data_evento_inicio >= CURRENT_DATE() THEN 0 ELSE 1 END,
        e.data_evento_inicio ASC");
        return $read;
    }

    public function listarEventosID($id): read
    {
        $read = new Read();
        $read->FullRead("SELECT e.*, u.nome AS responsavel FROM eventos AS e
        JOIN usuarios AS u ON u.id = e.id_user
        WHERE e.id = :id ORDER BY e.id DESC", "id={$id}");
        return $read;
    }

    public function listaEquipesEventos($id): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM eventos_equipes WHERE id_evento = :id", "id={$id}");
        return $read;
    }

    public function addEventoSave($params)
    {
        $create = new Create();
        $params['id_user'] = $_SESSION['sampel_user_id'];
        $create->ExeCreate('eventos', $params);
        return $create->getResult();
    }

    public function editEventoSave($params)
    {
        $id = $params['id'];
        unset($params['id']);
        $update = new Update();
        $update->ExeUpdate('eventos', $params, 'WHERE id = :id', "id={$id}");
        return $update;
    }

    public function listaEquipes(): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM usuarios WHERE tipo = '4' AND `situacao` <> 'Inativo' ORDER BY nome ASC");
        return $read;
    }

    public function listaEquipesSave($params): read
    {
        $read = new Read();
        $check = new Read();
        if(!empty($params['equipe'])){

            if(is_countable($params['equipe'])){

                for ($i=0; $i < count($params['equipe']); $i++) {
                    
                    $check->FullRead("SELECT * FROM `eventos_equipes` WHERE `id_evento` = :id_evento AND `id_user` = :id_user", "id_evento={$params['id_evento']}&id_user={$params['equipe'][$i]['editar_equipe']}");
                    
                    if(!$check->getResult()){
                        //SALVA SE NAO EXISTIR
                        $read->FullRead("INSERT INTO `eventos_equipes` (`id_evento`, `id_user`, `funcao`) VALUES (:id_evento, :id_user, :funcao)", "id_evento={$params['id_evento']}&id_user={$params['equipe'][$i]['editar_equipe']}&funcao={$params['equipe'][$i]['funcao']}");
                    }else{
                        //DELETA E SALVA SE NAO EXISTIR
                        $read->FullRead("DELETE FROM `eventos_equipes` WHERE  `id_evento` = :id_evento AND `id_user` = :id_user", "id_evento={$params['id_evento']}&id_user={$params['equipe'][$i]['editar_equipe']}");
                        $read->FullRead("INSERT INTO `eventos_equipes` (`id_evento`, `id_user`, `funcao`) VALUES (:id_evento, :id_user, :funcao)", "id_evento={$params['id_evento']}&id_user={$params['equipe'][$i]['editar_equipe']}&funcao={$params['equipe'][$i]['funcao']}");
                    }
                }
            }

        }
        return $read;
    }

    public function removeEquipe($params): read
    {
        $read = new Read();
        $read->FullRead("DELETE FROM `eventos_equipes` WHERE `id_evento` = :evento AND `id_user` = :membro", "evento={$params['evento']}&membro={$params['membro']}");
        return $read;
    }
    
    public function listaEquipesEvento($id_evento): read
    {
        $read = new Read();
        $read->FullRead("SELECT u.*, ve.`funcao`, ve.`data` AS data_equipe FROM eventos_equipes AS ve
        INNER JOIN usuarios AS u ON u.id = ve.id_user
        WHERE ve.id_evento = :id_evento ORDER BY u.nome ASC", "id_evento={$id_evento}");
        return $read;
    }

    public function sendUpdate($id): read
    {
        $read = new Read();
        $read->FullRead("UPDATE `eventos` SET `email_equipe` = 'S' WHERE `id` = :id", "id={$id}");
        return $read;
    }

}