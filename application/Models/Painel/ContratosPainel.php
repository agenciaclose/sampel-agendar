<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class ContratosPainel extends Model
{

    public function getAnosContratos(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT YEAR(data_parcela) AS ano FROM orcamentos_parcelas GROUP BY YEAR(data_parcela) ORDER BY ano ASC");
        return $read;
    }

    public function getContratosTotalGeral(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT COUNT(id) AS total_contratos, SUM(valor_orcamento) AS valor_total FROM orcamentos WHERE tipo_contrato = 'Contrato'");
        return $read;
    }

    public function getContratosTotalGeralAtivos(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT o.id, o.valor_orcamento, MAX(op.data_parcela) AS ultima_parcela
        FROM orcamentos AS o
        JOIN orcamentos_parcelas AS op ON op.id_orcamento = o.id
        WHERE o.tipo_contrato = 'Contrato'
        GROUP BY o.id
        HAVING MAX(op.data_parcela) >= CURDATE()");
        return $read;
    }

    public function getContratosTotalGeralAVencer(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT o.id, o.valor_orcamento
        FROM orcamentos AS o
        JOIN orcamentos_parcelas AS op ON op.id_orcamento = o.id
        WHERE o.tipo_contrato = 'Contrato'
        GROUP BY o.id
        HAVING MAX(op.data_parcela) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 2 MONTH)");
        return $read;
    }

    public function getContratosTotalGeralVencidos(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT o.id, o.valor_orcamento
        FROM orcamentos AS o
        JOIN orcamentos_parcelas AS op ON op.id_orcamento = o.id
        WHERE o.tipo_contrato = 'Contrato'
        GROUP BY o.id
        HAVING MAX(op.data_parcela) < CURDATE()");
        return $read;
    }


    //VENCIDOS

    public function getContratos($params): Read
    {
        $ids = '';
        if(isset($params['ids'])){
            $ids = "AND o.id in (".$params['ids'].")";
        }

        $where = '';

        if(!empty($_GET['ano'])){
            $where .= " AND (SELECT MIN(op.data_parcela)
                            FROM orcamentos_parcelas op 
                            WHERE op.id_orcamento = o.id) LIKE '%".$_GET['ano']."%'";
        }

        $read = new Read();
        $read->FullRead("SELECT o.*,
                    CASE 
                        WHEN o.tipo_evento = 'visitas' THEN (SELECT v.title FROM visitas v WHERE v.id = o.id_evento)
                        WHEN o.tipo_evento = 'palestras' THEN (SELECT p.title FROM palestras p WHERE p.id = o.id_evento)
                        WHEN o.tipo_evento = 'patrocinios' THEN (SELECT pt.nome_patrocinio FROM patrocinios pt WHERE pt.id = o.id_evento)
                        WHEN o.tipo_evento = 'eventos' THEN (SELECT e.nome_evento FROM eventos e WHERE e.id = o.id_evento)
                        ELSE NULL
                    END AS nome_evento, op.primeira_data_parcela, op.ultima_data_parcela, f.empresa_fantasia, f.empresa_cnpj
                    FROM orcamentos AS o
                    LEFT JOIN fornecedores AS f ON f.id = o.id_fornecedor
                    INNER JOIN (
                        SELECT 
                            id_orcamento, 
                            MIN(data_parcela) AS primeira_data_parcela, 
                            MAX(data_parcela) AS ultima_data_parcela
                        FROM orcamentos_parcelas
                        GROUP BY id_orcamento
                    ) AS op ON o.id = op.id_orcamento
                    WHERE o.tipo_contrato = 'Contrato' $where $ids
                    ORDER BY o.date_create DESC");
        return $read;
    }

    public function getContratosTotal(): Read
    {
        $ano = !empty($_GET['ano']) ? $_GET['ano'] : date('Y');
        
        $read = new Read();
        $read->FullRead("SELECT COUNT(DISTINCT o.id) AS TotalContratos, SUM(op.valor_parcela) AS total_valor_contratos
        FROM orcamentos_parcelas AS op
        INNER JOIN orcamentos o ON o.id = op.id_orcamento
        WHERE o.tipo_contrato = 'Contrato' AND YEAR(op.data_parcela) = '".$ano."'");
        return $read;
    }

    public function getContratosValorPago(): Read
    {
        $ano = !empty($_GET['ano']) ? $_GET['ano'] : date('Y');

        $read = new Read();
        $read->FullRead("SELECT SUM(op.valor_parcela) AS total_pago FROM 
        orcamentos_parcelas AS op 
        INNER JOIN orcamentos o ON o.id = op.id_orcamento 
        WHERE o.tipo_contrato = 'Contrato' AND YEAR(op.data_parcela) = '".$ano."' AND op.data_parcela < CURDATE()");
        return $read;
    }

    public function getContratosValorNaoPago(): Read
    {
        $ano = !empty($_GET['ano']) ? $_GET['ano'] : date('Y');

        $read = new Read();
        $read->FullRead("SELECT SUM(op.valor_parcela) AS total_nao_pago FROM 
        orcamentos_parcelas AS op 
        INNER JOIN orcamentos o ON o.id = op.id_orcamento 
        WHERE o.tipo_contrato = 'Contrato' AND YEAR(op.data_parcela) = '".$ano."' AND op.data_parcela >= CURDATE()");
        return $read;
    }

    public function getPagamentosPorMes(): Read
    {
        $ano = !empty($_GET['ano']) ? $_GET['ano'] : date('Y');

        $read = new Read();
        $read->FullRead("SELECT 
            m.mes, 
            COALESCE(q.qtd_pagamentos, 0) AS qtd_pagamentos,
            COALESCE(q.total_valor, 0) AS total_valor,
            COALESCE(q.porcentagem_valor, 0) AS porcentagem_valor,
            COALESCE(q.porcentagem, 0) AS porcentagem
        FROM (
            SELECT 1 AS mes UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
            UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 
            UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12
        ) AS m
        LEFT JOIN (
            SELECT 
                MONTH(op.data_parcela) AS mes, 
                COUNT(*) AS qtd_pagamentos, 
                SUM(op.valor_parcela) AS total_valor, 
                ( SUM(op.valor_parcela) / (
                    SELECT SUM(valor_parcela) 
                    FROM orcamentos_parcelas 
                    INNER JOIN orcamentos AS oo ON oo.id = orcamentos_parcelas.id_orcamento 
                    WHERE oo.tipo_contrato = 'Contrato' 
                    AND YEAR(data_parcela) = $ano
                )) * 100 AS porcentagem_valor, 
                ( COUNT(*) * 100.0 / (
                    SELECT COUNT(*) 
                    FROM orcamentos_parcelas 
                    INNER JOIN orcamentos AS oo ON oo.id = orcamentos_parcelas.id_orcamento 
                    WHERE oo.tipo_contrato = 'Contrato' 
                    AND YEAR(data_parcela) = $ano
                )) AS porcentagem 
            FROM orcamentos_parcelas op 
            INNER JOIN orcamentos AS o ON o.id = op.id_orcamento 
            WHERE o.tipo_contrato = 'Contrato' 
            AND YEAR(op.data_parcela) = $ano 
            GROUP BY MONTH(op.data_parcela)
        ) q ON q.mes = m.mes");
        return $read;
    }


    public function getListaOrcamentoPorMes($primeiro_dia, $ultimo_dia): Read
    {
        $read = new Read();
        $read->FullRead("SELECT o.*, 
                CASE 
                    WHEN o.tipo_evento = 'visitas' THEN (SELECT v.title FROM visitas v WHERE v.id = o.id_evento)
                    WHEN o.tipo_evento = 'palestras' THEN (SELECT p.title FROM palestras p WHERE p.id = o.id_evento)
                    WHEN o.tipo_evento = 'patrocinios' THEN (SELECT pt.nome_patrocinio FROM patrocinios pt WHERE pt.id = o.id_evento)
                    WHEN o.tipo_evento = 'eventos' THEN (SELECT e.nome_evento FROM eventos e WHERE e.id = o.id_evento)
                    ELSE NULL
                END AS nome_evento,
                op.numero_parcela,
                op.data_parcela,
                op.valor_parcela
            FROM  orcamentos AS o
            INNER JOIN  orcamentos_parcelas AS op ON o.id = op.id_orcamento
            WHERE o.tipo_contrato = 'Contrato' AND op.data_parcela BETWEEN '".$primeiro_dia."' AND '".$ultimo_dia."'
            ORDER BY op.data_parcela ASC
        ");
        return $read;
    }

    public function getValoresPagosMes($primeiro_dia, $ultimo_dia): Read
    {
        
        $read = new Read();
        $read->FullRead("SELECT SUM(op.valor_parcela) AS valor_total_pago
                FROM  orcamentos AS o
                INNER JOIN  orcamentos_parcelas AS op ON o.id = op.id_orcamento
                WHERE o.tipo_contrato = 'Contrato' AND op.data_parcela BETWEEN '".$primeiro_dia."' AND '".$ultimo_dia."' 
                AND op.data_parcela <= CURDATE()
                ORDER BY op.data_parcela ASC");
        return $read;
    }

    public function getValoresNaoPagosMes($primeiro_dia, $ultimo_dia): Read
    {
        
        $read = new Read();
        $read->FullRead("SELECT SUM(op.valor_parcela) AS valor_nao_total_pago
                FROM  orcamentos AS o
                INNER JOIN  orcamentos_parcelas AS op ON o.id = op.id_orcamento
                WHERE o.tipo_contrato = 'Contrato' AND op.data_parcela BETWEEN '".$primeiro_dia."' AND '".$ultimo_dia."' 
                AND op.data_parcela > CURDATE()
                ORDER BY op.data_parcela ASC");
        return $read;
    }

}