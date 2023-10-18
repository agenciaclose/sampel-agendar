<?php

namespace Agencia\Close\Controllers\Painel\Config;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\Upload;
use Agencia\Close\Models\Painel\ConfigPainel;

class ConfigController extends Controller
{

    public function index($params)
    {
        $this->setParams($params);

        $estados = new ConfigPainel();
        $estados = $estados->getEstados()->getResult();

        $configuracoes = new ConfigPainel();
        $configuracoes = $configuracoes->getConfiguracoes()->getResult()[0];

        $motivos = new ConfigPainel();
        $motivos = $motivos->getMotivos()->getResult();

        $this->render('painel/pages/visitas/configuracoes.twig', ['menu' => 'visitas', 'estados' => $estados, 'configuracoes' => $configuracoes, 'motivos' => $motivos]);
    }

    public function save($params)
    {
        $this->setParams($params);

        $limites = new ConfigPainel();
        $limites = $limites->SaveLimites($this->params)->getResult();
        echo "1";
    }

    public function saveRegras($params)
    {
        $this->setParams($params);

        $regras = new ConfigPainel();
        $regras = $regras->saveRegras($this->params)->getResult();
        if(!$regras){
            echo '1';
        }else{
            echo '0';
        }
    }

    public function saveMotivo($params)
    {
        $this->setParams($params);

        $motivo = new ConfigPainel();
        $motivo = $motivo->saveMotivo($this->params)->getResult();
        if(!$motivo){
            echo '1';
        }else{
            echo '0';
        }
    }

    public function editMotivo($params)
    {
        $this->setParams($params);

        $motivo = new ConfigPainel();
        $motivo = $motivo->editMotivo($this->params)->getResult();
        if(!$motivo){
            echo '1';
        }else{
            echo '0';
        }
    }

    public function deleteMotivo($params)
    {
        $this->setParams($params);

        $motivo = new ConfigPainel();
        $motivo = $motivo->deleteMotivo($this->params)->getResult();
        if(!$motivo){
            echo '1';
        }else{
            echo '0';
        }
    }

}
