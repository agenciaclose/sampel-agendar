<?php
namespace Agencia\Close\Controllers\Painel\ProdutosPainel;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\ProdutosVisibilidade;
use Agencia\Close\Models\Painel\CargosPainel;


class ProdutosVisibilidadeController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);
        $model = new ProdutosVisibilidade();
        $visibilidades = $model->getAll()->getResult();

        $cargos = new CargosPainel();
        $cargos = $cargos->getCargosList()->getResult();

        $this->render('painel/pages/produtos/visibilidade/index.twig', ['visibilidades' => $visibilidades, 'cargos' => $cargos]);
    }

    public function create($params)
    {
        $this->setParams($params);
        $cargos = new CargosPainel();
        $cargos = $cargos->getCargosList()->getResult();
        $this->render('painel/pages/produtos/visibilidade/form.twig', ['cargos' => $cargos]);
    }

    public function store($params)
    {
        $this->setParams($params);
        $model = new ProdutosVisibilidade();
        
        // Converter cargos para array se não for
        if (isset($params['cargos']) && !is_array($params['cargos'])) {
            $params['cargos'] = [$params['cargos']];
        }
        
        $model->create($params);
        header('Location: ' . DOMAIN . '/painel/produtos/visibilidade');
        exit;
    }

    public function edit($params)
    {
        $this->setParams($params);
        $model = new ProdutosVisibilidade();
        $editar = $model->getById($params['id'])->getResult()[0];
        
        // Converter string de cargos para array
        if (isset($editar['cargos_ids']) && !empty($editar['cargos_ids'])) {
            $editar['cargos_ids'] = explode(',', $editar['cargos_ids']);
        } else {
            $editar['cargos_ids'] = [];
        }

        $cargos = new CargosPainel();
        $cargos = $cargos->getCargosList()->getResult();

        // Buscar todas as visibilidades cadastradas
        $visibilidades = $model->getAll()->getResult();

        $this->render('painel/pages/produtos/visibilidade/form.twig', [
            'editar' => $editar,
            'cargos' => $cargos,
            'visibilidades' => $visibilidades
        ]);
    }

    public function update($params)
    {
        $this->setParams($params);
        $model = new ProdutosVisibilidade();
        $id = $params['id'];
        unset($params['id']);
        
        // Converter cargos para array se não for
        if (isset($params['cargos']) && !is_array($params['cargos'])) {
            $params['cargos'] = [$params['cargos']];
        }
        
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