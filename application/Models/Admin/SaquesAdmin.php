<?php

namespace Agencia\Close\Models\ADmin;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class SaquesAdmin extends Model
{

    public function getSaques(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT s.*, c.`conta_banco`, c.`banco_pix`,c.`conta_ag`,c.`conta_numero`,c.`conta_tipo`,c.`conta_responsavel`,c.`conta_cpf_cnpj`,u.`nome`, u.`email`, u.`telefone` FROM saques AS s 
                        INNER JOIN contas_bancarias AS c ON c.id = s.id_conta_bancaria
                        INNER JOIN usuarios AS u ON c.id_consultor = u.id 
                        GROUP BY s.id ORDER BY s.id DESC");
        return $read;
    }

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
        $read->FullRead("SELECT id_consultor, SUM(valor) AS total FROM saques WHERE id_consultor = :sampel_user_id AND `status` = 'Concluido'", "sampel_user_id={$sampel_user_id}");
        return $read;
    }

    public function getSaque($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM saques WHERE id = :id LIMIT 1", "id={$id}");
        return $read;
    }

    public function statusSave($params): Read
    {
        $read = new Read();
        $read->FullRead("UPDATE `saques` SET `status` = :status, `nota` = :nota WHERE `id` = :id", "id={$params['id']}&status={$params['status']}&nota={$params['nota']}");
        return $read;
    }


}