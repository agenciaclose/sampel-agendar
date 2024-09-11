<?php

namespace Agencia\Close\Controllers\Painel\Orcamentos;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\PedidosPainel;
use Agencia\Close\Models\Painel\OrcamentosPainel;

class OrcamentosPainelController extends Controller
{

    public function lista($params)
    {
        $this->setParams($params);
       
        $model = new PedidosPainel();
        $brindes = $model->getPedidoOrcamentoID($params['id'], $params['tipo'])->getResult();

        $model = new OrcamentosPainel();
        $orcamentos = $model->getOrcamentosByEvento($params['id'], $params['tipo'])->getResult();
            
        $this->render('painel/pages/orcamentos/lista.twig', ['menu' => $params['tipo'], 'dados' => $params, 'brindes' => $brindes, 'orcamentos' => $orcamentos]);
    }

    public function getTerms($params)
    {
        $this->setParams($params);
        $model = new OrcamentosPainel();
        $terms = $model->getTerms();
  
        $json = [];
        if(count($terms->getResult()) > 0){
            foreach ($terms->getResult() as $key => $value) {
                $json[] = ['id'=>$value['orcamento'], 'text'=>$value['orcamento']];
            }
        } else {
            $json[] = array('id' => '0', 'text' => 'Nenhum item encontrado');
        }
        echo json_encode($json);
        
    }

    public function addOrcamento($params)
    {
        $this->setParams($params);
        $this->render('painel/pages/orcamentos/form.twig', ['dados' => $params]);
    }

    public function editOrcamento($params)
    {
        $this->setParams($params);

        $model = new OrcamentosPainel();
        $orcamentos = $model->getOrcamentosID($params['id'], $params['tipo'], $params['id_edit']);

        if($orcamentos->getResult()){
            $orcamento = $orcamentos->getResult()[0];
            $parcelas = $model->getOrcamentoParcelas($orcamento['id'])->getResult();
        }else{
            $parcelas = [];
            $orcamento = [];
        }

        $model = new OrcamentosPainel();
        $arquivos = $model->getOrcamentosArquivos($params['id'], $params['id_edit'])->getResult();

        $this->render('painel/pages/orcamentos/form.twig', ['dados' => $params, 'orcamento' => $orcamento, 'parcelas' => $parcelas, 'arquivos' => $arquivos]);
    }

    public function addOrcamentoSave($params)
    {
        $this->setParams($params);
        $save = new OrcamentosPainel();
        $save = $save->addOrcamentoSave($params);
        if($save){ echo '1'; }
    }

    public function editOrcamentoSave($params)
    {
        $this->setParams($params);
        $save = new OrcamentosPainel();
        $save = $save->editOrcamentoSave($params);
        if($save){ echo '1'; }
    }

    public function removeOrcamento($params)
    {
        $this->setParams($params);
        $remove = new OrcamentosPainel();
        $remove = $remove->removeOrcamento($params['id']);
        if($remove){ echo '1'; }
    }

}