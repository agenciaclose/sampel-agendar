<?php

namespace Agencia\Close\Controllers\Admin\Servicos;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\Upload;
use Agencia\Close\Models\Admin\ServicosTipos;

class TiposController extends Controller
{

    public function index($params)
    {
        $this->setParams($params);

        $tipos = new ServicosTipos();
        $tipo_lista = $tipos->getTipos()->getResult();

        $this->render('painel/admin/servicosTipos/lista.twig', ['menu' => 'tipos_servicos_admin', 'tipos' => $tipo_lista]);
    }

    public function edit($params)
    {
        $this->setParams($params);

        $tipos = new ServicosTipos();
        $tipos_lista = $tipos->getTipos()->getResult();

        $tipos = new ServicosTipos();
        $tipos_editar = $tipos->getTiposID($this->params['id'])->getResult()[0];

        $this->render('painel/admin/servicosTipos/lista.twig', ['menu' => 'tipos_servicos_admin', 'tipos' => $tipos_lista, 'tipos_editar' => $tipos_editar]);
    }

    public function save($params)
    {
        $this->setParams($params);
        $tipos = new ServicosTipos();

        if($_FILES['imagem']) {
            $upload = new Upload;
            $upload->Image($_FILES['imagem'], microtime(), null, 'servicos/icones');
        }
        if(isset($upload) && $upload->getResult()) {
            $this->params['icone'] = $upload->getResult();
        }
        if($this->params['id']) {
            $result = $tipos->updateTipo($this->params);
            if($result->getResult()) {
                $id = $this->params['id'];
            }
        } else {
            $result = $tipos->createTipo($this->params);
            $id = $result->getResult();
        }
        echo $id;

    }

}