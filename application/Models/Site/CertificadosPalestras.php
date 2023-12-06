<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Models\Model;

class CertificadosPalestras extends Model
{

    public function emitirCheckPalestra($cpf): read
    {
        $read = new Read();
        $read->FullRead("SELECT codigo, cpf FROM palestras_participantes WHERE (cpf = :cpf OR codigo = :cpf ) AND presenca = 'Sim'", "cpf={$cpf}");
        return $read;
    }

    public function certificadoUpdate($codigo): read
    {
        $read = new Read();
        $read->FullRead("UPDATE `palestras_participantes` SET `certificado` = 'Sim' WHERE codigo = :codigo", "codigo={$codigo}");
        return $read;
    }

}