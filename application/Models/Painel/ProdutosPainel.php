<?php

namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;
use mysql_xdevapi\Result;

class ProdutosPainel extends Model
{
	private string $table = 'produtos';

    public function getProdutosList($id_user): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM ".$this->table." 
                        LEFT JOIN (
                            SELECT id_produto,imagem,`order`
                            FROM produtos_imagens WHERE `order` = 0
                            GROUP BY id_produto
                            ORDER BY `order` ASC
                        ) img ON produtos.id = img.id_produto
                        WHERE id_user = :id_user AND `status` <> 'Deletado' ORDER BY id DESC", "id_user={$id_user}");
        return $read;
    }
    public function createDraft(array $data, $id_user): Read
    {
        //SALVA O RASCUNHO
        $create = new Create();
        $data['id_user'] = $id_user;
        $data['status'] = 'Rascunho';
        $create->ExeCreate($this->table, $data);
        //RETORNA O ITEM SALVO
        $read = new Read();
        $read->FullRead("SELECT * FROM ".$this->table." WHERE id_user = :id_user ORDER BY id DESC LIMIT 1", "id_user={$id_user}");
        return $read;
    }

    public function updateProduct(array $data): Update
    {
        $dataToSave = $this->tratamento($data);
        $update = new Update();
        $update->ExeUpdate($this->table, $dataToSave, 'WHERE id = :id AND id_user = :id_user', "id={$data['id']}&id_user={$_SESSION['sampel_user_id']}");
        return $update;
    }

    public function getProdutoID($id, $id_user): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM ".$this->table." WHERE id = :id AND id_user = :id_user ORDER BY id DESC LIMIT 1", "id={$id}&id_user={$id_user}");
        return $read;
    }


    public function tratamento(array $data): array
    {
        unset($data['id']);
        unset($data['outer-list']);
        unset($data['data']);
        unset($data['produtoid']);
        unset($data['fileuploader-list-files']);
        unset($data['arquivo']);

        if($data['price'] != ''){$data['price'] = number_format(str_replace(",",".",str_replace(".","", $data['price'])), 2, '.', ''); }else{ $data['price'] = null; }
        if($data['price_promo'] != ''){ $data['price_promo'] = number_format(str_replace(",",".",str_replace(".","", $data['price_promo'])), 2, '.', ''); }else{ $data['price_promo'] = null; }

        if(isset($data['categories_id'])) { $data['categories_id'] = implode(',', $data['categories_id']); }
        if(isset($data['categories'])) { $data['categories'] = implode(',', $data['categories']); }

        return $data;

    }

    public function updateProductFiles(array $arquivos, $id): void
    {

        for ($i = 0; $i < count($arquivos); $i++) {

            if($arquivos[$i]['file_name'] != ''){

                $data['id_user'] = $_SESSION['sampel_user_id'];
                $data['id_produto'] = $id;
                $data['file_name'] = $arquivos[$i]['file_name'];
                $data['file'] = $arquivos[$i]['file'];

                $create = new Create();
                $create->ExeCreate('produtos_arquivos', $data);

            }

        }
    }

    public function getCategoriesID($categories_id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM categorias WHERE id = :id AND id_user = :id_user ORDER BY id DESC LIMIT 1", "id={$categories_id}&id_user={$_SESSION['sampel_user_id']}");
        return $read;
    }

    public function getProdutoFiles($id_produto, $id_user): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM produtos_arquivos WHERE id_produto = :id_produto AND id_user = :id_user AND `status` = 'S' ORDER BY `id` DESC", "id_produto={$id_produto}&id_user={$id_user}");
        return $read;
    }

    public function deleteFile(array $params): Read
    {
        $read = new Read();
        $read->FullRead("UPDATE `produtos_arquivos` SET `status` = 'N' WHERE `id_user` = :id_user AND `id_produto` = :id_produto  AND `file` = :file ", "id_produto={$params['id_produto']}&id_user={$_SESSION['sampel_user_id']}&file={$params['file']}");
        return $read;
    }

    public function getProdutoImages($id_produto): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM produtos_imagens WHERE id_produto = :id_produto ORDER BY `order`,`id` DESC", "id_produto={$id_produto}");
        return $read;
    }

    public function excluirProduto($id_produto): Read
    {
        $read = new Read();
        $read->FullRead("UPDATE `produtos` SET `status` = 'Deletado' WHERE  `id` = :id_produto", "id_produto={$id_produto}");
        return $read;
    }

    public function updateProductCategory(array $categories_lista, $id_produto)
    {
        $read = new Read();
        $read->FullRead("DELETE FROM produtos_categorias WHERE `id_user` = :id_user AND `id_produto` = :id_produto", "id_user={$_SESSION['sampel_user_id']}&id_produto={$id_produto}");

        for ($i = 0; $i < count($categories_lista); $i++) {

            if($categories_lista[$i]['nome'] != ''){

                $data['id_user'] = $_SESSION['sampel_user_id'];
                $data['id_produto'] = $id_produto;
                $data['id_categoria'] = $categories_lista[$i]['id'];
                $data['categoria'] = $categories_lista[$i]['nome'];
                $data['slug'] = $categories_lista[$i]['slug'];
                $data['nivel'] = $categories_lista[$i]['nivel'];
                $data['parent'] = $categories_lista[$i]['parent'];

                $create = new Create();
                $create->ExeCreate('produtos_categorias', $data);

            }

        }
    }

    public function getVendas($sampel_user_id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT pa.*, u.nome AS comprador FROM pagamentos AS pa 
                        INNER JOIN usuarios AS u ON u.id = pa.id_comprador
                        WHERE pa.`id_vendedor` = :id_vendedor ORDER BY pa.id DESC", "id_vendedor={$sampel_user_id}");
        return $read;
    }

    public function getVendasAdmin(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT pa.*, u.nome AS vendedor FROM pagamentos AS pa INNER JOIN usuarios AS u ON u.id = pa.id_vendedor ORDER BY pa.id DESC");
        return $read;
    }

    public function getVendaView($sampel_user_id, $id): Read
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
                        WHERE pa.`id_vendedor` = :id_vendedor AND pa.`id` = :id ORDER BY pa.id DESC", "id_vendedor={$sampel_user_id}&id={$id}");
        return $read;
    }

    public function getNotasVenda($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `pedidos_notas` WHERE id_pagamento = :id_pagamento", "id_pagamento={$id}");
        return $read;
    }

    public function getClientesAgendamentos($sampel_user_id): Read
    {
        $read = new Read();
        if($_SESSION['sampel_user_tipo'] == 2){
            $read->FullRead("SELECT * FROM agendamentos WHERE `id_vendedor` = :id_vendedor GROUP BY id_comprador ORDER BY id DESC", "id_vendedor={$sampel_user_id}");
        }else{
            $read->FullRead("SELECT * FROM agendamentos GROUP BY id_comprador ORDER BY id DESC");
        }
        return $read;
    }

    public function getClientesVendas($sampel_user_id): Read
    {
        $read = new Read();
        if($_SESSION['sampel_user_tipo'] == 2){
            $read->FullRead("SELECT * FROM pagamentos WHERE `id_vendedor` = :id_vendedor GROUP BY id_comprador ORDER BY id DESC", "id_vendedor={$sampel_user_id}");
        }else{
            $read->FullRead("SELECT * FROM pagamentos GROUP BY id_comprador ORDER BY id DESC");
        }
        return $read;
        
    }

    public function getTotalAgendamentos($sampel_user_id): Read
    {
        $read = new Read();

        if($_SESSION['sampel_user_tipo'] == 2){
            $read->FullRead("SELECT SUM(a.total) AS total
                            FROM agendamentos AS a
                            INNER JOIN servicos AS s ON s.id = a.itemID
                            INNER JOIN usuarios AS u ON u.id = a.id_comprador
                            LEFT JOIN (
                                SELECT id_pedido,dia_agendamento,horario_agendamento
                                FROM agendamentos_itens ORDER BY `horario_agendamento` DESC
                            ) ai ON a.id = ai.id_pedido
                            WHERE a.`id_vendedor` = :id_vendedor ORDER BY a.id DESC", "id_vendedor={$sampel_user_id}");
        }else{
            $read->FullRead("SELECT SUM(a.total) AS total
                            FROM agendamentos AS a
                            INNER JOIN servicos AS s ON s.id = a.itemID
                            INNER JOIN usuarios AS u ON u.id = a.id_comprador
                            LEFT JOIN (
                                SELECT id_pedido,dia_agendamento,horario_agendamento
                                FROM agendamentos_itens ORDER BY `horario_agendamento` DESC
                            ) ai ON a.id = ai.id_pedido ORDER BY a.id DESC");
        }
        return $read;
    }

    public function getTotalVendas($sampel_user_id): Read
    {
        $read = new Read();
        if($_SESSION['sampel_user_tipo'] == 2){
            $read->FullRead("SELECT SUM(pa.total) AS total FROM pagamentos AS pa 
                        INNER JOIN usuarios AS u ON u.id = pa.id_comprador
                        WHERE pa.`id_vendedor` = :id_vendedor ORDER BY pa.id DESC", "id_vendedor={$sampel_user_id}");
        }else{
            $read->FullRead("SELECT SUM(pa.total) AS total FROM pagamentos AS pa INNER JOIN usuarios AS u ON u.id = pa.id_comprador ORDER BY pa.id DESC");
        }
        return $read;
    }

}