<?php
namespace Agencia\Close\Controllers\Painel\ProdutosPainel;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\ProdutosVisibilidade;

class ProdutosVisibilidadeController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);
        $model = new ProdutosVisibilidade();
        $visibilidades = $model->getAll()->getResult();
        $this->render('painel/pages/produtos/visibilidade/index.twig', ['visibilidades' => $visibilidades]);
    }

    public function create($params)
    {
        $this->setParams($params);
        $this->render('painel/pages/produtos/visibilidade/form.twig', []);
    }

    public function store($params)
    {
        $this->setParams($params);
        $model = new ProdutosVisibilidade();
        $model->create($params);
        header('Location: ' . DOMAIN . '/painel/produtos/visibilidade');
        exit;
    }

    public function edit($params)
    {
        $this->setParams($params);
        $model = new ProdutosVisibilidade();
        $editar = $model->getById($params['id'])->getResult()[0];
        $visibilidades = $model->getAll()->getResult();

        $this->render('painel/pages/produtos/visibilidade/form.twig', ['editar' => $editar, 'visibilidades' => $visibilidades]);
    }

    public function update($params)
    {
        $this->setParams($params);
        $model = new ProdutosVisibilidade();
        $id = $params['id'];
        unset($params['id']);
        $model->update($id, $params);
        header('Location: ' . DOMAIN . '/painel/produtos/visibilidade');
        exit;
    }

    public function delete($params)
    {
        $this->setParams($params);
        $model = new ProdutosVisibilidade();
        $model->delete($params['id']);
        header('Location: ' . DOMAIN . '/painel/produtos/visibilidade');
        exit;
    }
} 