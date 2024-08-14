<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Read;
use Agencia\Close\Models\Model;

class HomePainel extends Model
{
    public function getFourEventosYear(): Read
    {
        $where = " WHERE data_evento_inicio like '%".date('Y')."%'";
        
        $read = new Read();
        $read->FullRead("SELECT *,
        (SELECT SUM(valor_orcamento) FROM orcamentos WHERE id_evento = eventos.id) AS total_orcamento,
        (SELECT SUM(valor_total_pedido) FROM pedidos WHERE id_evento = eventos.id) AS total_pedido,
        (
            IFNULL((SELECT SUM(valor_orcamento) FROM orcamentos WHERE id_evento = eventos.id), 0) +
            IFNULL((SELECT SUM(valor_total_pedido) FROM pedidos WHERE id_evento = eventos.id), 0)
        ) AS total_gastos
        FROM eventos
        $where
        ORDER BY data_evento_inicio ASC");
        return $read;
    }

}