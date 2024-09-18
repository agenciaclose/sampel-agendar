<?php

namespace Agencia\Close\Controllers\Painel\ProdutosPainel;

use Agencia\Close\Helpers\Upload;
use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\ProdutosPainel;

class ProdutosPainelController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);
        $this->permissions('produtos', '"view"');

        $model = new ProdutosPainel();
        $produtos = $model->getProdutos()->getResult();

        $valorTotalEstoque = $model->valorTotalEstoqueSemPDV()->getResult()[0];
        $valorTotalEstoquePDV = $model->valorTotalEstoquePDV()->getResult()[0];

        $this->render('painel/pages/produtos/index.twig', ['menu' => 'produtos', 'produtos' => $produtos, 'valorTotalEstoque' => $valorTotalEstoque, 'valorTotalEstoquePDV' => $valorTotalEstoquePDV]);
    }

    public function productAdd($params)
    {
        $this->setParams($params);
        $this->render('painel/pages/produtos/form.twig', []);
    }

    public function productEdit($params)
    {
        $this->setParams($params);

        $produto = new ProdutosPainel();
        $produto = $produto->getProdutoID($params['id'])->getResult();
        $this->render('painel/pages/produtos/form.twig', ['product' => $produto[0]]);
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