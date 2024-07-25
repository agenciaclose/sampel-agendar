<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class PedidosPainel extends Model
{
    public function getTipoVisitas(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT id, title, data_visita AS data FROM visitas WHERE status_visita NOT IN ('Cancelado','Recusado') ORDER BY id DESC");
        return $read;
    }

    public function getTipoPalestras(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT id, title, DATE_FORMAT(data_palestra, '%Y-%m-%d') AS data FROM palestras WHERE status_palestra NOT IN ('Cancelado','Recusado') AND id > 11 ORDER BY id DESC");
        return $read;
    }

    public function getTipoEventos(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT id, nome_evento AS title, data_evento_inicio AS data FROM eventos WHERE status_evento = 'Ativo' ORDER BY id DESC");
        return $read;
    }

}