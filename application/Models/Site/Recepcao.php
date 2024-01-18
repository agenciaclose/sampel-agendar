<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Models\Model;

class Recepcao extends Model
{
    public function confirmarPresenca($params)
    {
        $read = new Read();
        $read->FullRead("UPDATE `visitas_inscricoes` SET `presenca` = 'Sim' WHERE `id_visita` = '".$params['id_visita']."' AND `codigo` = '".$params['codigo']."'");
        return $read;
    }

}