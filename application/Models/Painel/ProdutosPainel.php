<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Conn\Delete;
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

        // Filtro por visibilidade da role se não for admin
        $user_tipo = $_SESSION['sampel_user_tipo'] ?? null;
        $user_id = $_SESSION['sampel_user_tipo'] ?? null;
        if ($user_tipo != 1 && $user_id) {
            $where .= " AND produtos.id IN (
                SELECT pv.id_produto
                FROM produtos_visibilidades pv
                JOIN visibilidades v ON v.id = pv.id_visibilidade
                JOIN usuario_roles ur ON ur.id_role = v.cargo
                WHERE ur.id_user = {$user_id}
            )";
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

    public function getVisibilidades()
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM produtos_visibilidade ORDER BY nome ASC");
        return $read;
    }

    public function saveVisibilidadesProduto($id_produto, $visibilidades)
    {
        $delete = new Delete();
        $delete->ExeDelete('produtos_visibilidades', 'WHERE id_produto = :id_produto', "id_produto={$id_produto}");
        if (!empty($visibilidades)) {
            $create = new Create();
            foreach ($visibilidades as $id_visibilidade) {
                $create->ExeCreate('produtos_visibilidades', [
                    'id_produto' => $id_produto,
                    'id_visibilidade' => $id_visibilidade
                ]);
            }
        }
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
        if(empty($params['visibilidade_id'])) {
            $params['visibilidade_id'] = null;
        }
        $visibilidades = isset($params['visibilidades']) ? $params['visibilidades'] : [];
        unset($params['visibilidades']);
        $create = new Create();
        $create->ExeCreate('produtos', $params);
        $id_produto = $create->getResult();
        if (!empty($visibilidades)) {
            $this->saveVisibilidadesProduto($id_produto, $visibilidades);
        }
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
        if(empty($params['visibilidade_id'])) {
            $params['visibilidade_id'] = null;
        }
        $visibilidades = isset($params['visibilidades']) ? $params['visibilidades'] : [];
        unset($params['visibilidades']);
        $update = new Update();
        $update->ExeUpdate('produtos', $params, 'WHERE id = :id', "id={$id}");
        if (!empty($visibilidades)) {
            $this->saveVisibilidadesProduto($id, $visibilidades);
        }
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

    public function getProdutosComVisibilidades(): array
    {
        $where = $this->getFilter();
        $read = new Read();
        $read->FullRead("SELECT * FROM produtos WHERE `status` = 'Ativo' $where ORDER BY `nome` ASC");
        $produtos = $read->getResult();
        if ($produtos) {
            foreach ($produtos as &$produto) {
                $visRead = new Read();
                $visRead->FullRead("SELECT v.id, v.nome, v.cor FROM produtos_visibilidades pv JOIN visibilidades v ON v.id = pv.id_visibilidade WHERE pv.id_produto = :id_produto", "id_produto={$produto['id']}");
                $produto['visibilidades'] = $visRead->getResult() ?: [];
            }
        }
        return $produtos;
    }

}