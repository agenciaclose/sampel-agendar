<?php

namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class ServicosPainel extends Model
{

	private string $table = 'servicos';

	public function getServicos(): Read
    {
        $read = new Read();
        $read->ExeRead($this->table, 'WHERE id_user = :id_user', "id_user={$_SESSION['sampel_user_id']}");
        return $read;
    }

    public function getServicoID($id): Read
    {
        $read = new Read();
        $read->ExeRead($this->table, 'WHERE id = :id AND id_user = :id_user', "id={$id}&id_user={$_SESSION['sampel_user_id']}");
        return $read;
    }

    public function createServicos(array $data): Create
    {
        $dataToSave = $this->tratamento($data);
        $create = new Create();
        $create->ExeCreate($this->table, $dataToSave);
        return $create;
    }

    public function updateServicos(array $data): Update
    {
        $dataToSave = $this->tratamento($data);
        $update = new Update();
        $update->ExeUpdate($this->table, $dataToSave, 'WHERE id = :id AND id_user = :id_user', "id={$data['id']}&id_user={$_SESSION['sampel_user_id']}");
        return $update;
    }

    public function tratamento(array $data): array
    {
        unset($data['id']);
        $data['id_user'] = $_SESSION['sampel_user_id'];
        $data['preco'] = number_format(str_replace(",",".",str_replace(".","", $data['preco'])), 2, '.', '');
        if(isset($data['tipo'])) { $data['tipo'] = implode(',', $data['tipo']); }
        if(isset($data['categoria'])) { $data['categoria'] = implode(',', $data['categoria']); }
        return $data;
    }

    public function getTipos(): Read
    {
        $read = new Read();
        $read->ExeRead('tipo_servicos');
        return $read;
    }

    public function getCategorias(): Read
    {
        $read = new Read();
        $read->ExeRead('categoria_servicos');
        return $read;
    }

    public function getHorarios(): Read
    {
        $read = new Read();
        $read->ExeRead('servicos_horarios', 'WHERE id_user = :id_user', "id_user={$_SESSION['sampel_user_id']}");
        return $read;
    }

    public function createHorarios(array $data)
    {
        for ($i = 0; $i < count($data['dia']); $i++){
            $dias = [];

            $dias['id_user'] = $_SESSION['sampel_user_id'];
            $dias['dia'] = $data['dia'][$i];
            $dias['horario_inicio'] = $data['inicio'][$i];
            $dias['horario_fim'] = $data['fim'][$i];

            $create = new Create();
            $create->ExeCreate('servicos_horarios', $dias);
        }

    }

    public function updateHorarios(array $data)
    {
        for ($i = 0; $i < count($data['dia']); $i++){
            $dias = [];

            $dias['id'] = $data['id'][$i];
            $dias['dia'] = $data['dia'][$i];
            $dias['horario_inicio'] = $data['inicio'][$i];
            $dias['horario_fim'] = $data['fim'][$i];

            $update = new Update();
            $update->ExeUpdate('servicos_horarios', $dias, 'WHERE id = :id AND id_user = :id_user', "id={$dias['id']}&id_user={$_SESSION['sampel_user_id']}");
        }

    }

    public function getAgendamentos(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT a.*, s.titulo, u.nome, ai.dia_agendamento, ai.horario_agendamento
                        FROM agendamentos AS a
                        INNER JOIN servicos AS s ON s.id = a.itemID
                        INNER JOIN usuarios AS u ON u.id = a.id_comprador
                        LEFT JOIN (
                            SELECT id_pedido,dia_agendamento,horario_agendamento
                            FROM agendamentos_itens ORDER BY `horario_agendamento` DESC
                        ) ai ON a.id = ai.id_pedido
                        WHERE a.id_vendedor = :id_vendedor ORDER BY a.id DESC", "id_vendedor={$_SESSION['sampel_user_id']}");
        return $read;
    }

    public function postLinkSave(array $data): Read
    {
        $read = new Read();
        $read->FullRead("UPDATE `agendamentos` SET `link_consultor` = :link WHERE  `id`= :id", "link={$data['link']}&id={$data['agendamento']}");
        return $read;
    }

    public function getAgendamentoView($sampel_user_id, $id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT pa.*, u.nome AS comprador, p.titulo, p.imagem, ai.dia_agendamento, ai.horario_agendamento
                        FROM agendamentos AS pa 
                        INNER JOIN usuarios AS u ON u.id = pa.id_comprador
                        INNER JOIN servicos AS p ON p.id = pa.itemID
                        LEFT JOIN (
                            SELECT id_pedido,dia_agendamento,horario_agendamento
                            FROM agendamentos_itens ORDER BY `horario_agendamento` DESC
                        ) ai ON pa.id = ai.id_pedido
                        WHERE pa.`id_vendedor` = :id_vendedor AND pa.`id` = :id ORDER BY pa.id DESC", "id_vendedor={$sampel_user_id}&id={$id}");
        return $read;
    }

    public function getAgendamentoViewLast($sampel_user_id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT pa.*, u.nome AS comprador, p.titulo, p.imagem, ai.dia_agendamento, ai.horario_agendamento
                        FROM agendamentos AS pa 
                        INNER JOIN usuarios AS u ON u.id = pa.id_comprador
                        INNER JOIN servicos AS p ON p.id = pa.itemID
                        LEFT JOIN (
                            SELECT id_pedido,dia_agendamento,horario_agendamento
                            FROM agendamentos_itens ORDER BY `horario_agendamento` DESC
                        ) ai ON pa.id = ai.id_pedido
                        WHERE pa.`id_vendedor` = :id_vendedor ORDER BY pa.id DESC LIMIT 1", "id_vendedor={$sampel_user_id}");
        return $read;
    }

    public function getNotasAgendamento($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `agendamentos_notas` WHERE id_agendamento = :id_agendamento", "id_agendamento={$id}");
        return $read;
    }

    public function getHorariosAgendamento($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `agendamentos_itens` WHERE id_pedido = :id_pedido", "id_pedido={$id}");
        return $read;
    }

    public function updateConcluido($item): Read
    {
        $read = new Read();
        $read->FullRead("UPDATE `agendamentos` SET `situacao` = 'Concluido' WHERE `id` = :item", "item={$item}");
        return $read;
    }

    public function getManuais(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM manuais_consultor ORDER BY ordem ASC");
        return $read;
    }


}