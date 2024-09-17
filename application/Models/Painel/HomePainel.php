<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Read;
use Agencia\Close\Models\Model;

class HomePainel extends Model
{
    public function getForEventosYear(): Read
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

    public function getPedidosMensais(): Read
    {
       
        $read = new Read();
        $read->FullRead("SELECT meses.mes_nome, COALESCE(pedidos_por_mes.quantidade_pedidos, 0) AS quantidade_pedidos, COALESCE(pedidos_por_mes.total_valor_pedidos, 0) AS total_valor_pedidos
        FROM 
            (
                SELECT 1 AS mes_num, 'Jan' AS mes_nome UNION ALL
                SELECT 2 AS mes_num, 'Fev' AS mes_nome UNION ALL
                SELECT 3 AS mes_num, 'Mar' AS mes_nome UNION ALL
                SELECT 4 AS mes_num, 'Abr' AS mes_nome UNION ALL
                SELECT 5 AS mes_num, 'Mai' AS mes_nome UNION ALL
                SELECT 6 AS mes_num, 'Jun' AS mes_nome UNION ALL
                SELECT 7 AS mes_num, 'Jul' AS mes_nome UNION ALL
                SELECT 8 AS mes_num, 'Ago' AS mes_nome UNION ALL
                SELECT 9 AS mes_num, 'Set' AS mes_nome UNION ALL
                SELECT 10 AS mes_num, 'Out' AS mes_nome UNION ALL
                SELECT 11 AS mes_num, 'Nov' AS mes_nome UNION ALL
                SELECT 12 AS mes_num, 'Dez' AS mes_nome
            ) AS meses
        LEFT JOIN 
            (
                SELECT MONTH(date_create) AS mes_num, COUNT(id) AS quantidade_pedidos, SUM(valor_total_pedido) AS total_valor_pedidos
                FROM pedidos
                WHERE YEAR(date_create) = YEAR(CURDATE()) AND status_pedido not in ('0', '1')
                GROUP BY  mes_num
            ) AS pedidos_por_mes
        ON meses.mes_num = pedidos_por_mes.mes_num
        ORDER BY meses.mes_num");
        return $read;
    }

    public function getPedidosSemanais(): Read
    {
       
        $read = new Read();
        $read->FullRead("SELECT semanas.dia_semana AS semana_nome, COALESCE(pedidos_por_semana.quantidade_pedidos, 0) AS quantidade_pedidos, COALESCE(pedidos_por_semana.total_valor_pedidos, 0) AS total_valor_pedidos
        FROM 
            (
                SELECT 1 AS dia_num, 'Seg' AS dia_semana UNION ALL
                SELECT 2 AS dia_num, 'Ter' AS dia_semana UNION ALL
                SELECT 3 AS dia_num, 'Qua' AS dia_semana UNION ALL
                SELECT 4 AS dia_num, 'Qui' AS dia_semana UNION ALL
                SELECT 5 AS dia_num, 'Sex' AS dia_semana UNION ALL
                SELECT 6 AS dia_num, 'Sab' AS dia_semana UNION ALL
                SELECT 7 AS dia_num, 'Dom' AS dia_semana
            ) AS semanas
        LEFT JOIN 
            (
                SELECT DAYOFWEEK(date_create) AS dia_num, COUNT(id) AS quantidade_pedidos, SUM(valor_total_pedido) AS total_valor_pedidos
                FROM  pedidos
                WHERE YEARWEEK(date_create, 1) = YEARWEEK(CURDATE(), 1) AND status_pedido not in ('0', '1')
                GROUP BY dia_num
            ) AS pedidos_por_semana
        ON semanas.dia_num = pedidos_por_semana.dia_num
        ORDER BY semanas.dia_num");
        return $read;
    }

}