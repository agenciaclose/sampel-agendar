<?php

    namespace Agencia\Close\Models\Site;

    use Agencia\Close\Conn\Read;
    use Agencia\Close\Models\Model;

    class MinhaConta extends Model
    {

        public function getSchedules($user): read
        {
            $read = new Read();
            $read->FullRead("SELECT a.*, s.titulo, u.nome, ai.dia_agendamento, ai.horario_agendamento
                            FROM agendamentos AS a
                            INNER JOIN servicos AS s ON s.id = a.itemID
                            INNER JOIN usuarios AS u ON u.id = a.id_vendedor
                            LEFT JOIN (
                                SELECT id_pedido,dia_agendamento,horario_agendamento
                                FROM agendamentos_itens ORDER BY `horario_agendamento` DESC
                            ) ai ON a.id = ai.id_pedido
                            WHERE a.id_comprador = :user ORDER BY a.id DESC", "user={$user}");
            return $read;
        }

        public function getOrders($user): read
        {
            $read = new Read();
            $read->FullRead("SELECT pa.*, u.nome AS vendedor FROM pagamentos AS pa 
                            INNER JOIN usuarios AS u ON u.id = pa.id_vendedor
                            WHERE pa.`id_comprador` = :user ORDER BY pa.id DESC", "user={$user}");
            return $read;
        }

        public function getOrdersView($sampel_user_id, $id): Read
        {
            $read = new Read();
            $read->FullRead("SELECT pa.*, u.nome AS comprador, p.id, p.sku, img.imagem
                            FROM pagamentos AS pa 
                            INNER JOIN usuarios AS u ON u.id = pa.id_vendedor
                            INNER JOIN produtos AS p ON p.id = pa.itemID
                            LEFT JOIN (
                                SELECT id_produto,imagem,`order`
                                FROM produtos_imagens WHERE `order` = 0
                                GROUP BY id_produto
                                ORDER BY `order` ASC
                            ) img ON pa.itemID = img.id_produto
                            WHERE pa.`id_comprador` = :id_comprador AND pa.`id` = :id ORDER BY pa.id DESC", "id_comprador={$sampel_user_id}&id={$id}");
            return $read;
        }

        public function getNotasVenda($id): Read
        {
            $read = new Read();
            $read->FullRead("SELECT * FROM `pedidos_notas` WHERE id_pagamento = :id_pagamento", "id_pagamento={$id}");
            return $read;
        }

        public function getArquivos($id): Read
        {
            $read = new Read();
            $read->FullRead("SELECT * FROM `produtos_arquivos` WHERE id_produto = :id_produto AND `status` = 'S'", "id_produto={$id}");
            return $read;
        }

        public function getAgendamentoView($sampel_user_id, $id): Read
        {
            $read = new Read();
            $read->FullRead("SELECT pa.*, u.nome AS comprador, p.titulo, p.imagem, ai.dia_agendamento, ai.horario_agendamento
                            FROM agendamentos AS pa 
                            INNER JOIN usuarios AS u ON u.id = pa.id_vendedor
                            INNER JOIN servicos AS p ON p.id = pa.itemID
                            LEFT JOIN (
                                SELECT id_pedido,dia_agendamento,horario_agendamento
                                FROM agendamentos_itens ORDER BY `horario_agendamento` DESC
                            ) ai ON pa.id = ai.id_pedido
                            WHERE pa.`id_comprador` = :id_comprador AND pa.`id` = :id ORDER BY pa.id DESC", "id_comprador={$sampel_user_id}&id={$id}");
            return $read;
        }

        public function getHorariosAgendamento($id): Read
        {
            $read = new Read();
            $read->FullRead("SELECT * FROM `agendamentos_itens` WHERE id_pedido = :id_pedido", "id_pedido={$id}");
            return $read;
        }

        public function getNotasAgendamento($id): Read
        {
            $read = new Read();
            $read->FullRead("SELECT * FROM `agendamentos_notas` WHERE id_agendamento = :id_agendamento", "id_agendamento={$id}");
            return $read;
        }

    }