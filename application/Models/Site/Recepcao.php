<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Models\Model;

class Recepcao extends Model
{

    public function confirmarPresenca($params): Read
    {
        $update = new Read();
        $update->FullRead("UPDATE `visitas_inscricoes` SET `presenca` = 'Sim' WHERE 
        id_visita = '".$params['id_visita']."' AND cpf = '".$params['codigo']."' OR
        id_visita = '".$params['id_visita']."' AND codigo = '".$params['codigo']."' OR
        id_visita = '".$params['id_visita']."' AND email = '".$params['codigo']."'");

        $read = new Read();
        $read->FullRead("SELECT * FROM `visitas_inscricoes` WHERE 
        id_visita = '".$params['id_visita']."' AND cpf = '".$params['codigo']."' OR
        id_visita = '".$params['id_visita']."' AND codigo = '".$params['codigo']."' OR
        id_visita = '".$params['id_visita']."' AND email = '".$params['codigo']."'");
        return $read;
    }

    public function InscricoesConfirmados($id_visita): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `visitas_inscricoes` WHERE `id_visita` = :id_visita AND `presenca` = 'Sim' ORDER BY nome ASC", "id_visita={$id_visita}");
        return $read;
    }

    public function InscricoesConfirmadosPalestra($id_palestra): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `palestras_participantes` WHERE `id_palestra` = :id_palestra AND `presenca` = 'Sim' ORDER BY nome ASC", "id_palestra={$id_palestra}");
        return $read;
    }

    public function confirmarPresencaPalestra($params): Read
    {
        if (strlen($params['codigo']) > 8) {
            $codigo = " AND `cpf` = '".$params['codigo']."'";
        } else {
            $codigo = " AND `codigo` = '".$params['codigo']."'";
        }

        $read = new Read();
        $read->FullRead("UPDATE `palestras_participantes` SET `presenca` = 'Sim' WHERE `id_palestra` = '".$params['id_palestra']."' $codigo");
        return $read;
    }

}