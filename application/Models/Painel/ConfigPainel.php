<?php

namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;
use mysql_xdevapi\Result;

class ConfigPainel extends Model
{

    public function getEstados(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM visitas_limites ORDER BY sigla ASC");
        return $read;
    }

    public function SaveLimites($params)
    {
        $read = new Read();
		foreach($params as $key => $valor) {
	        $read->FullRead("UPDATE `visitas_limites` SET `limit` = '".$valor."' WHERE `sigla` = '".$key."' LIMIT 1");
		}
	    return $read;

    }

}