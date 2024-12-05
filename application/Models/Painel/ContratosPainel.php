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

        if(!empty($_GET['ano_contrato'])){
            $where .= " AND o.date_create like '%".$_GET['ano_contrato']."%'";
        }

        $read = new Read();
        $read->FullRead("SELECT o.*,
                        CASE 
                            WHEN o.tipo_evento = 'visitas' THEN (SELECT v.title FROM visitas v WHERE v.id = o.id_evento)
                            WHEN o.tipo_evento = 'palestras' THEN (SELECT p.title FROM palestras p WHERE p.id = o.id_evento)
                            WHEN o.tipo_evento = 'patrocinios' THEN (SELECT pt.nome_patrocinio FROM patrocinios pt WHERE pt.id = o.id_evento)
                            WHEN o.tipo_evento = 'eventos' THEN (SELECT e.nome_evento FROM eventos e WHERE e.id = o.id_evento)
                            ELSE NULL
                        END AS nome_evento,
                        -- Subconsulta para a primeira data da parcela
                        (SELECT MIN(op.data_parcela) 
                        FROM orcamentos_parcelas op 
                        WHERE op.id_orcamento = o.id) AS primeira_data_parcela,
                        
                        -- Subconsulta para a última data da parcela
                        (SELECT MAX(op.data_parcela) 
                        FROM orcamentos_parcelas op 
                        WHERE op.id_orcamento = o.id) AS ultima_data_parcela
                    FROM orcamentos AS o
                    WHERE o.tipo_contrato = 'Contrato' $where
                    ORDER BY o.date_create DESC");
        return $read;
    }

}