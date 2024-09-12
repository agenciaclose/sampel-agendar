<?php

namespace Agencia\Close\Controllers\Painel\Empenho;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\EmpenhoModel;

class EmpenhoController extends Controller
{

    public function index($params)
    {
        $this->setParams($params);

        $empenhos = new EmpenhoModel();
        $empenhos = $empenhos->getEmpenhos()->getResult();

        $this->render('painel/pages/empenho/index.twig', ['menu' => 'empenho', 'empenhos' => $empenhos]);
    }

    public function empenhoAdd($params)
    {
        $this->setParams($params);
        $this->render('painel/pages/empenho/form.twig', []);
    }

    public function empenhoEdit($params)
    {
        $this->setParams($params);

        $empenho = new EmpenhoModel();
        $empenho = $empenho->getEmpenhoID($params['id'])->getResult();
        
        $this->render('painel/pages/empenho/form.twig', ['empenho' => $empenho[0]]);
    }

    public function addEmpenhoSave($params)
    {
        $this->setParams($params);
        $save = new EmpenhoModel();
        $save = $save->addEmpenhoSave($params);
        if($save){ echo '1'; }
    }

    public function editEmpenhoSave($params)
    {
        $this->setParams($params);
        $save = new EmpenhoModel();
        $save = $save->editEmpenhoSave($params);
        if($save){ echo '1'; }
    }

    public function removeEmpenho($params)
    {
        $this->setParams($params);
        $remove = new EmpenhoModel();
        $remove = $remove->removeEmpenho($params['id']);
        if($remove){ echo 'success'; }
    }

}