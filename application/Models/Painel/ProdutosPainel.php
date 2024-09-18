<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class ProdutosPainel extends Model
{

    public function getFilter()
    {
        $where = '';

        if(!empty($_GET['tipo'])){

            if($_GET['tipo'] == 'PDV'){
                $where .= " AND `PDV` = 'S' ";
            }
            if($_GET['tipo'] == 'estoque'){
                $where .= " AND estoque < estoque_minimo ";
            }

        }
        return $where;
    }



    public function getProdutos(): Read
    {
        $where = $this->getFilter();

        $read = new Read();
        $read->FullRead("SELECT * FROM produtos WHERE `status` = 'Ativo' $where ORDER BY `nome` ASC");
        return $read;
    }

    public function getProdutosSemPDV(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM produtos WHERE `status` = 'Ativo' AND PDV = 'N' ORDER BY `nome` ASC");
        return $read;
    }

    public function getProdutosPDV(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM produtos WHERE `status` = 'Ativo' AND PDV = 'S' ORDER BY `nome` ASC");
        return $read;
    }

    public function valorTotalEstoqueSemPDV(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT SUM(estoque * preco) AS valor_total_estoque FROM produtos WHERE estoque > 0 AND preco <> '' AND PDV = 'N'");
        return $read;
    }

    public function valorTotalEstoquePDV(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT SUM(estoque * preco) AS valor_total_estoque_PDV FROM produtos WHERE estoque > 0 AND preco <> '' AND PDV = 'S'");
        return $read;
    }

    public function valorTotalEstoqueBaixo(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT SUM((estoque_minimo - estoque) * CAST(preco AS DECIMAL(10, 2))) AS total_reabastecimento FROM produtos WHERE estoque < estoque_minimo  AND preco <> '' AND PDV = 'N'");
        return $read;
    }

    public function getProdutosEstoqueBaixo(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM produtos WHERE estoque < estoque_minimo  AND preco <> ''");
        return $read;
    }

    

    public function getProdutoID($id): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM produtos WHERE id = :id", "id={$id}");
        return $read;
    }

    public function addProductSave($params)
    {   
        if(isset($params['product_imagem'])) {
            $params['imagem'] = $params['product_imagem'];
            unset($params['product_imagem']);
        }else{
            unset($params['product_imagem']);
        }

        $params['preco'] = str_replace(',', '.', str_replace('.', '', $params['preco']));

        $create = new Create();
        $create->ExeCreate('produtos', $params);
        return $create;
    }

    public function editProductSave($params)
    {
        if(isset($params['product_imagem'])) {
            $params['imagem'] = $params['product_imagem'];
            unset($params['product_imagem']);
        }else{
            unset($params['product_imagem']);
        }

        $id = $params['id'];
        unset($params['id']);

        $params['preco'] = str_replace(',', '.', str_replace('.', '', $params['preco']));
    
        $update = new Update();
        $update->ExeUpdate('produtos', $params, 'WHERE id = :id', "id={$id}");
        return $update;
    }

    public function productStatus($params)
    {
        $read = new Read();
        $read->FullRead("UPDATE `produtos` SET `status` = :status WHERE id = :id", "status={$params['status']}&id={$params['id']}");
        return $read;
    }

    public function getProdutosByUser($id_produto)
    {
        $itens = new Read();
        $itens->FullRead("SELECT 
                pr.*, SUM(p.quantidade) AS total_quantidade, SUM(p.quantidade * pr.unidades) qtd_total, SUM(p.quantidade * pr.unidades) * p.valor_unid AS valor_total,
                SUM(p.quantidade) AS total_quantidade,
                u.nome AS user_nome,
                u.email AS user_email
            FROM pedidos_itens p
            JOIN pedidos pp ON pp.id_equipe = p.id_user
            JOIN produtos pr ON p.id_produto = pr.id
            JOIN usuarios u ON u.id = pp.id_equipe
            WHERE 
                p.id_produto = :id_produto
            GROUP BY 
                p.id_user, p.id_produto
            ORDER BY 
                total_quantidade DESC", "id_produto={$id_produto}");
        return $itens;
    }

}