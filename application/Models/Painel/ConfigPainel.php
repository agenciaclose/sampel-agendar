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

    public function getConfiguracoes(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM configuracoes WHERE id = '1'");
        return $read;
    }

    public function SaveLimites($params)
    {
        $read = new Read();
		foreach($params as $key => $valor) {
            $estado = explode('_', $key);
            if (str_contains($key, 'max')) { 
                $read->FullRead("UPDATE `visitas_limites` SET `limit` = '".$valor."' WHERE `sigla` = '".$estado[0]."' LIMIT 1");
            }else{
                $read->FullRead("UPDATE `visitas_limites` SET `limit_min` = '".$valor."' WHERE `sigla` = '".$estado[0]."' LIMIT 1");
            }
		}
	    return $read;
    }

    public function saveRegras($params): Read
    {
        $read = new Read();
		$read->FullRead("UPDATE `configuracoes` SET `sobre_horarios` = '".$params['sobre_horarios']."', `regras_visita` = '".$params['regras_visita']."' WHERE `id` = '1'");
	    return $read;
    }

    public function getOpcionais(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM opcionais ORDER BY `id` DESC");
        return $read;
    }

    public function saveOpcional($params): Read
    {
        $read = new Read();
        $read->FullRead("INSERT INTO `opcionais` (`opcional`) VALUES (:opcional)", "opcional={$params['opcional']}");
	    return $read;
    }

    public function editOpcional($params): Read
    {
        $read = new Read();
        $read->FullRead("UPDATE `opcionais` SET `opcional` = :opcional WHERE id = :id", "opcional={$params['opcional']}&id={$params['id']}");
	    return $read;
    }

    public function deleteOpcional($params): Read
    {
        $read = new Read();
        $read->FullRead("DELETE FROM `opcionais` WHERE  `id` = :id", "id={$params['id']}");
	    return $read;
    }

}