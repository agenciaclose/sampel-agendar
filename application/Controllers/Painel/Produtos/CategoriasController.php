<?php

namespace Agencia\Close\Controllers\Painel\Produtos;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\CategoriasProdutosPainel;

class CategoriasController extends Controller
{

    private CategoriasProdutosPainel $categories;

    public function index($params)
    {
        $this->setParams($params);
    	$categorias_lista = $this->getCategoryList();
        $this->render('painel/pages/produtos/categorias.twig', ['menu' => 'produtos', 'categorias' => $categorias_lista]);
    }

    public function getSave($params): array
    {
        $this->setParams($params);
        $categories = new CategoriasProdutosPainel();
        $result = $categories->catSave($this->params)->getResult();
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

}