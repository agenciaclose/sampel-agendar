<?php

namespace Agencia\Close\Models\Admin;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;
use mysql_xdevapi\Result;

class ProdutosPainel extends Model
{

    public function getVendasAdmin(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT pa.*, u.nome AS vendedor FROM pagamentos AS pa INNER JOIN usuarios AS u ON u.id = pa.id_vendedor ORDER BY pa.id DESC");
        return $read;
    }

    public function getVendaView($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT pa.*, u.nome AS comprador, p.id, p.sku, img.imagem
                        FROM pagamentos AS pa 
                        INNER JOIN usuarios AS u ON u.id = pa.id_comprador
                        INNER JOIN produtos AS p ON p.id = pa.itemID
                        LEFT JOIN (
                            SELECT id_produto,imagem,`order`
                            FROM produtos_imagens WHERE `order` = 0
                            GROUP BY id_produto
                            ORDER BY `order` ASC
                        ) img ON pa.itemID = img.id_produto
                        WHERE pa.`id` = :id ORDER BY pa.id DESC", "id={$id}");
        return $read;
    }

    public function getNotasVenda($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `pedidos_notas` WHERE id_pagamento = :id_pagamento", "id_pagamento={$id}");
        return $read;
    }

}