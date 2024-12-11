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

    public function getOrcamentosByTipo($tipo_evento, $ano): Read
    {
        $read = new Read();
        $read->FullRead("SELECT SUM(valor_orcamento) as valor_total_orcamento FROM orcamentos WHERE tipo_evento = :tipo_evento  AND YEAR(`date_create`) = :ano", "tipo_evento={$tipo_evento}&ano={$ano}");
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
        $params['valor_orcamento'] = str_replace(',', '.', str_replace('.', '', $params['valor_orcamento']));

        $params['id_user'] = $_SESSION['sampel_user_id'];

        $valor_parcela = $params['valor_parcela'];
        $data_parcela = $params['data_parcela'];
        unset($params['valor_parcela']);
        unset($params['data_parcela']);
        
        $create = new Create();
        $create->ExeCreate('orcamentos', $params);

        if($valor_parcela){
            $this->atualizarParcelas($create->getResult(), $valor_parcela, $data_parcela);
        }
        
        return $create->getResult();
    }

    public function editOrcamentoSave($params)
    {
        $id = $params['id'];
        unset($params['id']);

        $params['id_user'] = $_SESSION['sampel_user_id'];
        $params['valor_orcamento'] = str_replace(',', '.', str_replace('.', '', $params['valor_orcamento']));

        $valor_parcela = $params['valor_parcela'];
        $data_parcela = $params['data_parcela'];
        unset($params['valor_parcela']);
        unset($params['data_parcela']);

        if($valor_parcela){
            $this->atualizarParcelas($id, $valor_parcela, $data_parcela);
        }
        
        $update = new Update();
        $update->ExeUpdate('orcamentos', $params, 'WHERE id = :id', "id={$id}");
        return $update;
    }

    public function atualizarParcelas($id_orcamento, $valor_parcela, $data_parcela)
    {
        $clear = new Read();
        $clear->FullRead("DELETE FROM `orcamentos_parcelas` WHERE `id_orcamento`= :id_orcamento", "id_orcamento={$id_orcamento}");

        for ($i=0; $i < count($valor_parcela); $i++) {

            $dados = [];
            $valor = str_replace(',', '.', str_replace('.', '', $valor_parcela[$i]));
            $dados['id_orcamento'] = $id_orcamento;
            $dados['valor_parcela'] = $valor;
            $dados['data_parcela'] = $data_parcela[$i];
            
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

}