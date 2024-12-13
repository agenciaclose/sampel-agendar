<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class ContratosPainel extends Model
{
    public function getContratos(): Read
    {
        $where = '';

        if(!empty($_GET['ano'])){
            $where .= " AND (SELECT MIN(op.data_parcela)
                            FROM orcamentos_parcelas op 
                            WHERE op.id_orcamento = o.id) LIKE '%".$_GET['ano']."%'";
        }

        $read = new Read();
        $read->FullRead("SELECT 
                    o.*,
                    CASE 
                        WHEN o.tipo_evento = 'visitas' THEN (SELECT v.title FROM visitas v WHERE v.id = o.id_evento)
                        WHEN o.tipo_evento = 'palestras' THEN (SELECT p.title FROM palestras p WHERE p.id = o.id_evento)
                        WHEN o.tipo_evento = 'patrocinios' THEN (SELECT pt.nome_patrocinio FROM patrocinios pt WHERE pt.id = o.id_evento)
                        WHEN o.tipo_evento = 'eventos' THEN (SELECT e.nome_evento FROM eventos e WHERE e.id = o.id_evento)
                        ELSE NULL
                    END AS nome_evento,
                    op.primeira_data_parcela,
                    op.ultima_data_parcela
                    FROM orcamentos AS o
                    INNER JOIN (
                    SELECT 
                        id_orcamento, 
                        MIN(data_parcela) AS primeira_data_parcela, 
                        MAX(data_parcela) AS ultima_data_parcela
                    FROM orcamentos_parcelas
                    GROUP BY id_orcamento
                    ) AS op ON o.id = op.id_orcamento
                    WHERE o.tipo_contrato = 'Contrato' $where
                    ORDER BY o.date_create DESC");
        return $read;
    }

    public function getContratosTotal(): Read
    {
        $where = ""; 

        $ano = !empty($_GET['ano']) ? $_GET['ano'] : date('Y');
        
        if (isset($_GET['ano'])) {
            $where .= " AND YEAR(date_create) = $ano ";
        }

        $read = new Read();
        $read->FullRead("SELECT COUNT(id) AS TotalContratos, SUM(valor_orcamento) AS total_valor_contratos
            FROM orcamentos
            WHERE tipo_contrato = 'Contrato' $where ORDER BY date_create DESC");
        return $read;
    }

    // ISSO LISTA TODOS OS ORCAMENTOS E SEUS VALORES PAGOS
    //SELECT  o.id AS orcamento_id, o.orcamento AS nome_orcamento, SUM(op.valor_parcela) AS total_pago
    //FROM orcamentos o
    //INNER JOIN orcamentos_parcelas op ON o.id = op.id_orcamento
    //WHERE op.data_parcela < CURDATE()
    //GROUP BY o.id, o.orcamento

    public function getContratosValorPago(): Read
    {
        $where = ""; 
        $ano = !empty($_GET['ano']) ? $_GET['ano'] : date('Y');
        
        if (isset($_GET['ano'])) {
            $where .= " AND YEAR(o.date_create) = $ano ";
        }

        $read = new Read();
        $read->FullRead("SELECT SUM(op.valor_parcela) AS total_pago FROM orcamentos o
        INNER JOIN orcamentos_parcelas op ON o.id = op.id_orcamento
        WHERE o.tipo_contrato = 'Contrato' AND op.data_parcela < CURDATE() $where");
        return $read;
    }

    public function getContratosValorNaoPago(): Read
    {
        $where = ""; 

        $ano = !empty($_GET['ano']) ? $_GET['ano'] : date('Y');
        
        if (isset($_GET['ano'])) {
            $where .= " AND YEAR(o.date_create) = $ano ";
        }

        $read = new Read();
        $read->FullRead("SELECT SUM(op.valor_parcela) AS total_nao_pago FROM orcamentos o
            INNER JOIN orcamentos_parcelas op ON o.id = op.id_orcamento
            WHERE o.tipo_contrato = 'Contrato' AND op.data_parcela >= CURDATE() 
            $where
        ");
        return $read;
    }

    public function getPagamentosPorMes(): Read
    {
        $ano = ""; 
        if (isset($_GET['ano_inicial'])) {
            $ano = $_GET['ano_inicial'];
        }else{
            $ano = date('Y');
        }

        $read = new Read();
        $read->FullRead("SELECT 
                    MONTH(op.data_parcela) AS mes,
                    COUNT(*) AS qtd_pagamentos,
                    SUM(op.valor_parcela) AS total_valor,
                    (
                    SUM(op.valor_parcela) / (
                        SELECT SUM(valor_parcela) 
                        FROM orcamentos_parcelas 
                        INNER JOIN  orcamentos AS o ON o.id = op.id_orcamento
                            WHERE o.tipo_contrato = 'Contrato' AND YEAR(data_parcela) = $ano
                    )
                    ) * 100 AS porcentagem_valor,
                    (
                    COUNT(*) * 100.0 / (
                        SELECT COUNT(*)
                        FROM orcamentos_parcelas 
                        INNER JOIN  orcamentos AS o ON o.id = op.id_orcamento
                            WHERE o.tipo_contrato = 'Contrato' AND YEAR(data_parcela) = $ano
                    )
                    ) AS porcentagem
                    FROM orcamentos_parcelas op
                    INNER JOIN  orcamentos AS o ON o.id = op.id_orcamento
                    WHERE o.tipo_contrato = 'Contrato' AND YEAR(op.data_parcela) = $ano
                    GROUP BY MONTH(op.data_parcela)
                ");
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