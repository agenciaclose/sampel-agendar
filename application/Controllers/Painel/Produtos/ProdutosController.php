<?php

namespace Agencia\Close\Controllers\Painel\Produtos;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\Upload;
use Agencia\Close\Models\Painel\CategoriasProdutosPainel;
use Agencia\Close\Models\Painel\ProdutosPainel;

class ProdutosController extends Controller
{

    private CategoriasProdutosPainel $categories;
    public int $id = 0;

    public function index($params)
    {
        $this->setParams($params);

        $produtos = new ProdutosPainel();
        $produtos = $produtos->getProdutosList($_SESSION['sampel_user_id'])->getResult();
        $this->render('painel/pages/produtos/lista.twig', ['menu' => 'produtos', 'produtos' => $produtos]);
    }


    public function add($params)
    {
        $this->setParams($params);
        $categorias_lista = $this->getCategoryList();

        $this->render('painel/pages/produtos/form.twig', ['menu' => 'produtos', 'categorias' => $categorias_lista]);
    }

    public function edit($params)
    {
        $this->setParams($params);

        $produtos = new ProdutosPainel();
        $produto = $produtos->getProdutoID($this->params['id'], $_SESSION['sampel_user_id'])->getResult()[0];
        $categorias_lista = $this->getCategoryList();

        $arquivos = new ProdutosPainel();
        $arquivo = $arquivos->getProdutoFiles($this->params['id'], $_SESSION['sampel_user_id'])->getResult();

        $imagens = new ProdutosPainel();
        $imagem = $imagens->getProdutoImages($this->params['id'])->getResult();

        $this->render('painel/pages/produtos/form.twig', ['menu' => 'produtos', 'produto' => $produto, 'categorias' => $categorias_lista, 'arquivos' => $arquivo, 'imagens' => $imagem]);
    }

    public function vendas($params)
    {
        $this->setParams($params);
        $vendas = new ProdutosPainel();
        $vendas = $vendas->getVendas($_SESSION['sampel_user_id'])->getResult();
        $this->render('painel/pages/produtos/vendas.twig', ['menu' => 'vendas', 'vendas' => $vendas]);
    }
    public function vendaView($params)
    {
        $this->setParams($params);
        $venda = new ProdutosPainel();
        $venda = $venda->getVendaView($_SESSION['sampel_user_id'], $this->params['id'])->getResult()[0];

        $notas = new ProdutosPainel();
        $notas = $notas->getNotasVenda($this->params['id'])->getResult();

        $this->render('painel/pages/produtos/view.twig', ['menu' => 'vendas', 'venda' => $venda, 'notas' => $notas]);
    }

    //CRIAR O PRODUTO EM RASCUNHO
    public function save_draft($params)
    {
        $this->setParams($params);
        $produtos = new ProdutosPainel();
        $result = $produtos->createDraft($this->params, $_SESSION['sampel_user_id']);
        $produto_draft = $result->getResult()[0];

        header("Content-Type: application/json");
        echo json_encode($produto_draft);
    }

    public function getCategoryList(): array
    {
        $this->categories = new CategoriasProdutosPainel();
        $result = $this->categories->getCategory($_SESSION['sampel_user_id']);
        if ($result->getResult()) {
            return $this->buildTree($result->getResult());
        } else {
            return [];
        }
    }

    public function buildTree($categories, $parentId = 0): array
    {
        $branch = array();
        foreach ($categories as $item) {
            if ($item['parent'] == $parentId) {
                $children = $this->buildTree($categories, $item['id']);
                if ($children) {
                    $item['children'] = $children;
                }
                $branch[] = $item;
            }
        }
        return $branch;
    }

    public function update($params)
    {
        $this->setParams($params);
        $produto = new ProdutosPainel();

        //RETORNA OS NOMES DAS CATEGORIAS
        if(!empty($this->params['categories_id'])){
             for ($i = 0; $i < count($this->params['categories_id']); $i++) {
                $this->params['categories'][] = $produto->getCategoriesID($this->params['categories_id'][$i])->getResult()[0]['nome'];
             }
        }

        //RETORNA OS DADOS DAS CATEGORIAS
        $categories_lista = array();
        if(!empty($this->params['categories_id'])){
             for ($i = 0; $i < count($this->params['categories_id']); $i++) {
                $categories_lista[] = $produto->getCategoriesID($this->params['categories_id'][$i])->getResult()[0];
             }
        }
        $produto->updateProductCategory($categories_lista, $this->params['id']);

        //SALVAR ARQUIVOS DO PRODUTO
        $arquivos = $this->tratarArquivos($_FILES['arquivo']);
        $i = 0;
        foreach ($arquivos as $arquivo){
            if($arquivo['name']) {
                $upload = new Upload;
                $upload->File($arquivo, microtime(), 'produtos/'.$_SESSION['sampel_user_slug']);
            }
            if($arquivo['name']) {
                if(isset($upload) && $upload->getResult()) {
                    $this->params['arquivo'][$i]['file'] = $upload->getResult();
                }
            }
            $i++;
        }
        if(!empty($this->params['arquivo'])){
            $produto->updateProductFiles($this->params['arquivo'], $this->params['id']);
        }
        //ARQUIVOS

        $result = $produto->updateProduct($this->params);

        if($result->getResult()) {
            $this->id = $this->params['id'];
        }

        echo $this->id;
    }

    private function tratarArquivos($arquivos): array
    {
        $files = array();
        for ($i = 0; $i < count($arquivos['name']); $i++) {
            $files[$i]['name'] = $arquivos["name"][$i]['file'];
            $files[$i]['tmp_name'] = $arquivos["tmp_name"][$i]['file'];
            $files[$i]['type'] = $arquivos["type"][$i]['file'];
            $files[$i]['size'] = $arquivos["size"][$i]['file'];
            $files[$i]['error'] = $arquivos["error"][$i]['file'];
        }
        return $files;
    }

    public function deleteFile($params)
    {
        $this->setParams($params);
        $deletarFile = new ProdutosPainel();
        $deletarFile->deleteFile($this->params);
    }

    public function excluir($params)
    {
        $this->setParams($params);
        $excluir = new ProdutosPainel();
        $excluir->excluirProduto($this->params['id_produto']);
    }


}