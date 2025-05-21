<?php

namespace Agencia\Close\Controllers\Painel\ProdutosPainel;

use Agencia\Close\Helpers\Upload;
use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\ProdutosPainel;
use Agencia\Close\Models\Painel\ProdutosVisibilidade;

class ProdutosPainelController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);
        $this->permissions('produtos', '"view"');

        $model = new ProdutosPainel();
        $produtos = $model->getProdutosComVisibilidades();

        $visibilidade = $_GET['visibilidade'] ?? '';
        if ($visibilidade) {
            $produtos = array_filter($produtos, function($produto) use ($visibilidade) {
                foreach ($produto['visibilidades'] as $v) {
                    if ($v['nome'] === $visibilidade) {
                        return true;
                    }
                }
                return false;
            });
        }

        $valorTotalEstoque = $model->valorTotalEstoqueSemPDV()->getResult()[0];
        $valorTotalEstoquePDV = $model->valorTotalEstoquePDV()->getResult()[0];

        $this->render('painel/pages/produtos/index.twig', ['menu' => 'produtos', 'produtos' => $produtos, 'valorTotalEstoque' => $valorTotalEstoque, 'valorTotalEstoquePDV' => $valorTotalEstoquePDV]);
    }

    public function productAdd($params)
    {
        $this->setParams($params);
        $visibilidadeModel = new ProdutosVisibilidade();
        $visibilidades = $visibilidadeModel->getAll()->getResult();
        $this->render('painel/pages/produtos/form.twig', ['visibilidades' => $visibilidades, 'product' => ['visibilidades' => []]]);
    }

    public function productEdit($params)
    {
        $this->setParams($params);
        $produto = new ProdutosPainel();
        $product = $produto->getProdutoID($params['id'])->getResult();
        $visibilidadeModel = new ProdutosVisibilidade();
        $visibilidades = $visibilidadeModel->getAll()->getResult();
        // Buscar visibilidades vinculadas
        $read = new \Agencia\Close\Conn\Read();
        $read->FullRead("SELECT id_visibilidade FROM produtos_visibilidades WHERE id_produto = :id_produto", "id_produto={$params['id']}");
        $product[0]['visibilidades'] = array_map(function($v){ return $v['id_visibilidade']; }, $read->getResult() ?: []);
        $this->render('painel/pages/produtos/form.twig', ['product' => $product[0], 'visibilidades' => $visibilidades]);
    }

    public function productAddSave($params)
    {
        $this->setParams($params);
        $save = new ProdutosPainel();

        if(isset($_FILES['imagem'])) {
            $upload = new Upload;
            $upload->Image($_FILES['imagem'], microtime(), null, 'produtos');
        }
        if(isset($upload) && $upload->getResult()) {
            $params['product_imagem'] = DOMAIN.'/uploads/'.$upload->getResult();
        }

        $save = $save->addProductSave($params);
        if($save){ echo '1'; }
    }

    public function productEditSave($params)
    {
        $this->setParams($params);
        $save = new ProdutosPainel();

        if(isset($_FILES['imagem'])) {
            $upload = new Upload;
            $upload->Image($_FILES['imagem'], microtime(), null, 'produtos');
        }
        if(isset($upload) && $upload->getResult()) {
            $params['product_imagem'] = DOMAIN.'/uploads/'.$upload->getResult();
        }

        $save = $save->editProductSave($params);
        if($save){ echo '1'; }
    }

    public function productStatus(array $params)
    {
        $this->setParams($params);
        $editar = new ProdutosPainel();
        $editar = $editar->productStatus($params)->getResult();
        echo '1';
    }

    public function productsByUser($params)
    {
        $this->setParams($params);

        $model = new ProdutosPainel();
        $usuarios = $model->getProdutosByUser($params['id']);
        if($usuarios->getResult()){
            $usuarios = $usuarios->getResult();
        }else{
            $usuarios = [];
        }

        $this->render('painel/pages/produtos/productsUser.twig', ['menu' => 'dashboard-pedidos', 'usuarios' => $usuarios]);
    }

}