<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class FornecedoresPainel extends Model
{
    public function lista(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM fornecedores ORDER BY id DESC");
        return $read;
    }

    public function getTerms(): Read
    {
        if(!empty($_GET["q"])){
            $term = "WHERE empresa_fantasia like '%".$_GET["q"]."%'";
        }else{
            $term = "";
        }

        $read = new Read();
        $read->FullRead("SELECT * FROM fornecedores $term ORDER BY empresa_fantasia ASC");
        return $read;
    }

    public function getFornecedorID($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM fornecedores WHERE id = :id ORDER BY id DESC", "id={$id}");
        return $read;
    }

    public function addFornecedorSave($params): Read
    {   
        $create = new Create();
        $create->ExeCreate('fornecedores', $params);

        $read = new Read();
        $read->FullRead("SELECT * FROM fornecedores ORDER BY id DESC LIMIT 1");
        return $read;
    }

    public function editFornecedorSave($params): Update
    {
        $id = $params['id'];
        unset($params['id']);
    
        $update = new Update();
        $update->ExeUpdate('fornecedores', $params, 'WHERE id = :id', "id={$id}");
        return $update;
    }

    public function getOrcamentos($id): Read
    {
        $read = new Read();

        $read->FullRead("SELECT 
                o.*, 
                op.primeira_data_parcela, 
                op.ultima_data_parcela, 
                f.empresa_fantasia, 
                f.empresa_cnpj,
                (SELECT COUNT(*) 
                FROM orcamentos_parcelas AS op2 
                WHERE op2.id_orcamento = o.id 
                AND op2.data_parcela <= CURDATE()) AS parcelas_pagas
            FROM 
                orcamentos AS o 
            LEFT JOIN 
                fornecedores AS f ON f.id = o.id_fornecedor 
            INNER JOIN 
                (SELECT 
                    id_orcamento, 
                    MIN(data_parcela) AS primeira_data_parcela, 
                    MAX(data_parcela) AS ultima_data_parcela 
                FROM 
                    orcamentos_parcelas 
                GROUP BY 
                    id_orcamento) AS op 
            ON 
                o.id = op.id_orcamento 
            WHERE 
                o.id_fornecedor = :id 
            ORDER BY 
                o.date_create DESC", "id={$id}");
                    return $read;
    }

    public function getParcelasPagas($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT op.*, o.*,
        (SELECT COUNT(*) 
        FROM orcamentos_parcelas AS op2 
        INNER JOIN orcamentos AS o2 ON o2.id = op2.id_orcamento
        WHERE op2.id_orcamento = op.id_orcamento
        AND o2.id_fornecedor = 57) AS qtd_parcelas
        FROM orcamentos_parcelas AS op
        INNER JOIN orcamentos AS o ON o.id = op.id_orcamento
        WHERE o.id_fornecedor = :id
        AND op.data_parcela <= CURDATE()
        ORDER BY op.data_parcela DESC", "id={$id}");
        return $read;
    }

    public function getParcelasNaoPagas($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT op.*, o.*,
        (SELECT COUNT(*) 
        FROM orcamentos_parcelas AS op2 
        INNER JOIN orcamentos AS o2 ON o2.id = op2.id_orcamento
        WHERE op2.id_orcamento = op.id_orcamento
        AND o2.id_fornecedor = 57) AS qtd_parcelas
        FROM orcamentos_parcelas AS op
        INNER JOIN orcamentos AS o ON o.id = op.id_orcamento
        WHERE o.id_fornecedor = :id
        AND op.data_parcela > CURDATE()
        ORDER BY op.data_parcela ASC", "id={$id}");
        return $read;
    }

    public function getValorContratos($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT SUM(valor_orcamento) AS valor FROM orcamentos WHERE id_fornecedor = :id", "id={$id}");
        return $read;
    }

}