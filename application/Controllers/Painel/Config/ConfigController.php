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

        $opcionais = new ConfigPainel();
        $opcionais = $opcionais->getOpcionais()->getResult();

        $this->render('painel/pages/visitas/configuracoes.twig', ['menu' => 'visitas', 'estados' => $estados, 'configuracoes' => $configuracoes, 'opcionais' => $opcionais]);
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

    public function saveOpcional($params)
    {
        $this->setParams($params);

        $save = new ConfigPainel();
        $save = $save->saveOpcional($this->params)->getResult();
        if(!$save){
            echo '1';
        }else{
            echo '0';
        }
    }

    public function editOpcional($params)
    {
        $this->setParams($params);

        $save = new ConfigPainel();
        $save = $save->editOpcional($this->params)->getResult();
        if(!$save){
            echo '1';
        }else{
            echo '0';
        }
    }

    public function deleteOpcional($params)
    {
        $this->setParams($params);

        $save = new ConfigPainel();
        $save = $save->deleteOpcional($this->params)->getResult();
        if(!$save){
            echo '1';
        }else{
            echo '0';
        }
    }

}
