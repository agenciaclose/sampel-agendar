<?php

namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class SaquesPainel extends Model
{
    public function getTotalAgendamentos($sampel_user_id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT id, id_vendedor, SUM(total) AS total FROM agendamentos WHERE id_vendedor = :sampel_user_id AND `status` = 'approved' AND situacao = 'Concluido'", "sampel_user_id={$sampel_user_id}");
        return $read;
    }

    public function getTotalProdutos($sampel_user_id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT id, id_vendedor, SUM(total) AS total FROM pagamentos WHERE id_vendedor = :sampel_user_id AND `status` = 'approved'", "sampel_user_id={$sampel_user_id}");
        return $read;
    }

    public function getTotalSaques($sampel_user_id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT id_consultor, SUM(valor) AS total FROM saques WHERE id_consultor = :sampel_user_id AND `status` <> 'Rejeitado'", "sampel_user_id={$sampel_user_id}");
        return $read;
    }

    public function getContas_Bancarias($sampel_user_id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM contas_bancarias WHERE id_consultor = :sampel_user_id ORDER BY id DESC", "sampel_user_id={$sampel_user_id}");
        return $read;
    }

    public function getContaID($sampel_user_id, $id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM contas_bancarias WHERE id_consultor = :sampel_user_id AND id = :id", "sampel_user_id={$sampel_user_id}&id={$id}");
        return $read;
    }

    public function createConta(array $data, $sampel_user_id): Create
    {
        $data['id_consultor'] = $sampel_user_id;
        unset($data['id']);
        $create = new Create();
        $create->ExeCreate('contas_bancarias', $data);
        return $create;
    }

    public function updateConta(array $data, $sampel_user_id, $id): Update
    {
        $update = new Update();
        $update->ExeUpdate('contas_bancarias', $data, 'WHERE id = :id AND id_consultor = :id_consultor', "id={$id}&id_consultor={$sampel_user_id}");
        return $update;
    }

    public function getSaques($sampel_user_id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT s.*, c.`conta_banco`,c.`banco_pix`,c.`conta_ag`,c.`conta_numero`,c.`conta_tipo`,c.`conta_responsavel`,c.`conta_cpf_cnpj`
                        FROM saques AS s 
                        INNER JOIN contas_bancarias AS c ON c.id = s.id_conta_bancaria
                        WHERE s.id_consultor = :sampel_user_id GROUP BY s.id ORDER BY s.id DESC", "sampel_user_id={$sampel_user_id}");
        return $read;
    }

    public function createSaque(array $data, $sampel_user_id): Create
    {
        
        $data['id_consultor'] = $sampel_user_id;
        $data['valor'] = str_replace(',', '.', str_replace('.', '', $data['valor']));
        unset($data['id']);

        $create = new Create();
        $create->ExeCreate('saques', $data);
        return $create;
    }

}