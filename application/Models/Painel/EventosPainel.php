<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;
use Agencia\Close\Models\Painel\OrcamentosPainel;

class EventosPainel extends Model
{
    public function getEventos(): Read
    {
        $where = '';

        if(!empty($_GET['ano_evento'])){
            $where .= " AND data_evento_inicio like '%".$_GET['ano_evento']."%'";
        }

        $read = new Read();
        $read->FullRead("SELECT 
                eventos.*,
                IFNULL(orcamentos.total_orcamento, 0) AS total_orcamento,
                IFNULL(pedidos.total_pedido, 0) AS total_pedido,
                IFNULL(orcamentos.total_orcamento, 0) + IFNULL(pedidos.total_pedido, 0) AS total_gastos
            FROM 
                eventos
            LEFT JOIN (
                SELECT 
                    id_evento, 
                    SUM(valor_orcamento) AS total_orcamento
                FROM 
                    orcamentos
                WHERE 
                    tipo_evento = 'eventos'
                GROUP BY 
                    id_evento
            ) orcamentos ON eventos.id = orcamentos.id_evento
            LEFT JOIN (
                SELECT 
                    id_evento, 
                    SUM(valor_total_pedido) AS total_pedido
                FROM 
                    pedidos
                WHERE 
                    tipo_evento = 'eventos'
                GROUP BY 
                    id_evento
            ) pedidos ON eventos.id = pedidos.id_evento
            WHERE 
                eventos.status_evento = 'Ativo' $where
            ORDER BY
                (eventos.data_evento_inicio >= CURDATE()) DESC,
                CASE 
                    WHEN eventos.data_evento_inicio >= CURDATE() THEN eventos.data_evento_inicio 
                    ELSE NULL 
                END ASC,
                CASE 
                    WHEN eventos.data_evento_inicio < CURDATE() THEN eventos.data_evento_inicio 
                    ELSE NULL 
                END DESC");
        return $read;
    }

    public function getEventoID($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM eventos WHERE id = :id", "id={$id}");
        return $read;
    }

    public function addProductSave($params)
    {   
        $create = new Create();
        $create->ExeCreate('eventos', $params);
        return $create;
    }

    public function editProductSave($params)
    {
        $id = $params['id'];
        unset($params['id']);
    
        $update = new Update();
        $update->ExeUpdate('eventos', $params, 'WHERE id = :id', "id={$id}");
        return $update;
    }

    public function getEventoStatus($params)
    {
        $read = new Read();
        $read->FullRead("UPDATE `eventos` SET `status_evento` = :status_evento WHERE id = :id", "status_evento={$params['status_evento']}&id={$params['id']}");
        return $read;
    }

    public function getEventoDuplicar($id_evento)
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `eventos` WHERE id = :id", "id={$id_evento}");
        $evento = $read->getResult()[0];

        unset($evento['id']);
        unset($evento['date_update']);
        unset($evento['date_create']);
        $evento['id_user'] = $_SESSION['sampel_user_id'];

        $evento['nome_evento'] = $evento['nome_evento'].' - Copiado';
        $id_novo_evento = $this->addProductSave($evento)->getResult();

        //SALVA OS ORÇAMENTOS
        $read = new Read();
        $read->FullRead("SELECT * FROM `orcamentos` WHERE tipo_evento = 'eventos' AND id_evento = :id_evento", "id_evento={$id_evento}");
        if ($read->getResult()){
            foreach ($read->getResult() as $orcamento){
                
                unset($orcamento['id']);
                unset($orcamento['date_update']);
                unset($orcamento['date_create']);
                $orcamento['id_evento'] = $id_novo_evento;
                $orcamento['valor_orcamento'] = '0,00';
                $orcamento['qtd_parcelas'] = 1;

                $this->addOrcamentoSave($orcamento);
            }
        }

    }

    public function addOrcamentoSave($params)
    {   
        $params['valor_orcamento'] = str_replace(',', '.', str_replace('.', '', $params['valor_orcamento']));
        $params['id_user'] = $_SESSION['sampel_user_id'];

        $create = new Create();
        $create->ExeCreate('orcamentos', $params);
        return $create->getResult();
    }

}