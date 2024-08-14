<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class ProdutosPainel extends Model
{
    public function getProdutos(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM produtos WHERE `status` = 'Ativo' ORDER BY `nome` ASC");
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

    public function valorTotalSemEstoque(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT SUM(preco) AS valor_total_sem_estoque FROM produtos WHERE estoque = 0 AND preco <> '' AND PDV = 'N'");
        return $read;
    }

    public function getProdutosSemEstoque(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM produtos WHERE estoque = 0  AND preco <> ''");
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

}