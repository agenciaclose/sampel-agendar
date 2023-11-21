<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Models\Model;

class Certificados extends Model
{

    public function emitirCheckVisita($cpf): read
    {
        $read = new Read();
        $read->FullRead("SELECT codigo, cpf FROM visitas_inscricoes WHERE cpf = :cpf", "cpf={$cpf}");
        return $read;
    }

    public function certificadoUpdate($codigo): read
    {
        $read = new Read();
        $read->FullRead("UPDATE `visitas_inscricoes` SET `certificado` = 'Sim' WHERE codigo = :codigo", "codigo={$codigo}");
        return $read;
    }

}