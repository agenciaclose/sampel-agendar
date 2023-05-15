<?php

namespace Agencia\Close\Controllers\Admin\Servicos;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\Upload;
use Agencia\Close\Models\Admin\ServicosCategorias;

class CategoriasController extends Controller
{

    public function index($params)
    {
        $this->setParams($params);

        $categorias = new ServicosCategorias();
        $categorias_lista = $categorias->getCategorias()->getResult();

        $this->render('painel/admin/servicosCategorias/lista.twig', ['menu' => 'categorias_servicos_admin', 'categorias' => $categorias_lista]);
    }

    public function edit($params)
    {
        $this->setParams($params);

        $categorias = new ServicosCategorias();
        $categorias_lista = $categorias->getCategorias()->getResult();

        $categorias = new ServicosCategorias();
        $categorias_editar = $categorias->getCategoriasID($this->params['id'])->getResult()[0];

        $this->render('painel/admin/servicosCategorias/lista.twig', ['menu' => 'categorias_servicos_admin', 'categorias' => $categorias_lista, 'categorias_editar' => $categorias_editar]);
    }

    public function save($params)
    {
        $this->setParams($params);
        $categorias = new ServicosCategorias();

        if($_FILES['imagem']) {
            $upload = new Upload;
            $upload->Image($_FILES['imagem'], microtime(), null, 'servicos/icones');
        }
        if(isset($upload) && $upload->getResult()) {
            $this->params['icone'] = $upload->getResult();
        }
        if($this->params['id']) {
            $result = $categorias->updateCategoria($this->params);
            if($result->getResult()) {
                $id = $this->params['id'];
            }
        } else {
            $result = $categorias->createCategoria($this->params);
            $id = $result->getResult();
        }
        echo $id;

    }

}