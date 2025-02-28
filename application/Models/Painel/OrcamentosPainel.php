<?php

namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Conn\Read;
use Agencia\Close\Models\Model;

class OrcamentosPainel extends Model
{

    public function getVisitasList(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, u.*, v.id AS visita_id
                        FROM visitas AS v
                        INNER JOIN usuarios AS u ON u.id = v.id_empresa WHERE v.`status_visita` = 'Concluido' ORDER BY v.`data` DESC");
        return $read;
    }

    public function getTerms(): Read
    {
        if(!empty($_GET["q"])){
            $term = "WHERE orcamento like '%".$_GET["q"]."%'";
        }else{
            $term = "";
        }
        $read = new Read();
        $read->FullRead("SELECT * FROM orcamentos $term GROUP BY orcamento ORDER BY orcamento ASC");
        return $read;
    }

    public function getTermsTags(): Read
    {
        if(!empty($_GET["q"])){
            $term = "WHERE tag_orcamento like '%".$_GET["q"]."%'";
        }else{
            $term = "";
        }
        $read = new Read();
        $read->FullRead("SELECT tag_orcamento FROM orcamentos $term GROUP BY tag_orcamento ORDER BY tag_orcamento ASC");
        return $read;
    }

    public function getOrcamentosByTipo($tipo_evento, $ano): Read
    {
        $read = new Read();
        $read->FullRead("SELECT 
                SUM(op.valor_parcela) AS valor_total_orcamento
            FROM 
                orcamentos AS o
            INNER JOIN orcamentos_parcelas AS op ON op.id_orcamento = o.id
            LEFT JOIN 
                visitas AS v ON v.id = o.id_evento AND o.tipo_evento = 'visitas'
            LEFT JOIN 
                palestras AS p ON p.id = o.id_evento AND o.tipo_evento = 'palestras'
            LEFT JOIN 
                patrocinios AS pt ON pt.id = o.id_evento AND o.tipo_evento = 'patrocinios'
            LEFT JOIN 
                eventos AS e ON e.id = o.id_evento AND o.tipo_evento = 'eventos'
            WHERE 
                YEAR(op.data_parcela) = :ano
                AND o.tipo_evento = :tipo_evento
            GROUP BY 
                o.tipo_evento", "tipo_evento={$tipo_evento}&ano={$ano}");
        return $read;
    }

    public function getOrcamentosByEvento($id_evento, $tipo_evento): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM orcamentos WHERE id_evento = :id_evento AND tipo_evento = :tipo_evento", "id_evento={$id_evento}&tipo_evento={$tipo_evento}");
        return $read;
    }

    public function getOrcamentosID($id_evento, $tipo_evento, $id_edit): Read
    {
        $read = new Read();
        $read->FullRead("SELECT *,
                        CASE 
                            WHEN o.tipo_evento = 'visitas' THEN (SELECT v.title FROM visitas v WHERE v.id = o.id_evento)
                            WHEN o.tipo_evento = 'palestras' THEN (SELECT p.title FROM palestras p WHERE p.id = o.id_evento)
                            WHEN o.tipo_evento = 'patrocinios' THEN (SELECT pt.nome_patrocinio FROM patrocinios pt WHERE pt.id = o.id_evento)
                            WHEN o.tipo_evento = 'eventos' THEN (SELECT e.nome_evento FROM eventos e WHERE e.id = o.id_evento)
                            ELSE NULL
                        END AS nome_evento FROM orcamentos as o WHERE id_evento = :id_evento AND tipo_evento = :tipo_evento AND id = :id_edit", "id_evento={$id_evento}&tipo_evento={$tipo_evento}&id_edit={$id_edit}");
        return $read;
    }

    public function getOrcamentoParcelas($id_orcamento): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM orcamentos_parcelas WHERE id_orcamento = :id_orcamento ORDER BY data_parcela ASC", "id_orcamento={$id_orcamento}");
        return $read;
    }

    public function addOrcamentoSave($params)
    {   

        $params['tags'] = '';
        if (isset($params['tag_orcamento']) && is_array($params['tag_orcamento'])) {
            $tags = array_filter($params['tag_orcamento'], function($value) {
                return !empty($value) && $value != '0';
            });
            $params['tags'] = implode(',', $tags);
        }
        $params['tag_orcamento'] = $params['tags'];

        $params['valor_orcamento'] = str_replace(',', '.', str_replace('.', '', $params['valor_orcamento']));

        $params['id_user'] = $_SESSION['sampel_user_id'];

        $valor_parcela = $params['valor_parcela'];
        $data_parcela = $params['data_parcela'];
        $numero_parcela = $params['numero_parcela'];
        unset($params['tags']);
        unset($params['valor_parcela']);
        unset($params['data_parcela']);
        unset($params['numero_parcela']);
        
        $create = new Create();
        $create->ExeCreate('orcamentos', $params);
        
        if($valor_parcela){
            $this->atualizarParcelas($create->getResult(), $valor_parcela, $data_parcela, $numero_parcela);
        }
        
        return $create->getResult();
    }
    
    public function editOrcamentoSave($params)
    {
        $id = $params['id'];
        unset($params['id']);
        
        $params['id_user'] = $_SESSION['sampel_user_id'];
        $params['valor_orcamento'] = str_replace(',', '.', str_replace('.', '', $params['valor_orcamento']));

        $params['tags'] = '';
        if (isset($params['tag_orcamento']) && is_array($params['tag_orcamento'])) {
            $tags = array_filter($params['tag_orcamento'], function($value) {
                return !empty($value) && $value != '0';
            });
            $params['tags'] = implode(',', $tags);
        }
        $params['tag_orcamento'] = $params['tags'];
        
        $valor_parcela = $params['valor_parcela'];
        $data_parcela = $params['data_parcela'];
        $numero_parcela = $params['numero_parcela'];
        unset($params['tags']);
        unset($params['valor_parcela']);
        unset($params['data_parcela']);
        unset($params['numero_parcela']);
        unset($params["fileuploader-list-files"]);
        
        if($valor_parcela){
            $this->atualizarParcelas($id, $valor_parcela, $data_parcela, $numero_parcela);
        }
        
        $update = new Update();
        $update->ExeUpdate("orcamentos", $params, "WHERE id = :id", "id={$id}");
        return $update;
    }
    
    public function atualizarParcelas($id_orcamento, $valor_parcela, $data_parcela, $numero_parcela)
    {
        $clear = new Read();
        $clear->FullRead("DELETE FROM `orcamentos_parcelas` WHERE `id_orcamento`= :id_orcamento", "id_orcamento={$id_orcamento}");

        for ($i=0; $i < count($valor_parcela); $i++) {

            $dados = [];
            $valor = str_replace(',', '.', str_replace('.', '', $valor_parcela[$i]));
            $dados['id_orcamento'] = $id_orcamento;
            $dados['valor_parcela'] = $valor;
            $dados['data_parcela'] = $data_parcela[$i];
            $dados['numero_parcela'] = $numero_parcela[$i];
            
            $create = new Create();
            $create->ExeCreate('orcamentos_parcelas', $dados);
        }
        return $create;
    }


    public function removeOrcamento($id): Read
    {
        $read = new Read();
        $read->FullRead("DELETE FROM `orcamentos` WHERE `id`= :id", "id={$id}");
        return $read;
    }

    public function getOrcamentosArquivos($id_orcamento, $id_item): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM orcamentos_arquivos WHERE id_orcamento = :id_orcamento AND id_item = :id_item ORDER BY `order` ASC", "id_orcamento={$id_orcamento}&id_item={$id_item}");
        return $read;
    }

    public function tipoContrato($params)
    {
        $id = $params['id'];
        unset($params['id']);
        
        $update = new Update();
        $update->ExeUpdate('orcamentos', $params, 'WHERE id = :id', "id={$id}");
        return $update;
    }

    public function getOrcamentosRecorrentes() {
        $read = new Read();
        $read->FullRead("SELECT * FROM orcamentos WHERE tipo_pagamento = 'Recorrente'");
        return $read->getResult();
    }

    public function getUltimaParcela($id_orcamento) {
        $read = new Read();
        $read->FullRead("SELECT * FROM orcamentos_parcelas WHERE id_orcamento = :id_orcamento ORDER BY numero_parcela DESC LIMIT 1", "id_orcamento={$id_orcamento}");
        $result = $read->getResult();
        return !empty($result) ? $result[0] : null;
    }

    public function inserirNovaParcela($id_orcamento, $numero_parcela, $valor_parcela, $data_parcela) {
        $create = new Create();
        $dados = [
            'id_orcamento'   => $id_orcamento,
            'numero_parcela' => $numero_parcela,
            'valor_parcela'  => $valor_parcela,
            'data_parcela'   => $data_parcela
        ];
        $create->ExeCreate("orcamentos_parcelas", $dados);
    }

}