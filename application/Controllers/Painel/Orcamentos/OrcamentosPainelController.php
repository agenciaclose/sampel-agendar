<?php

namespace Agencia\Close\Controllers\Painel\Orcamentos;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\PedidosPainel;
use Agencia\Close\Models\Painel\OrcamentosPainel;
use Agencia\Close\Models\Painel\FornecedoresPainel;

class OrcamentosPainelController extends Controller
{

    public function lista($params)
    {
        $this->setParams($params);

        $model = new PedidosPainel();

        $tipo = '';
        if($params['tipo'] == 'visitas'){
            $tipo = 'Visita: ';
        }

        if($params['tipo'] == 'palestras'){
            $tipo = 'Palestra: ';
        }

        if($params['tipo'] == 'eventos'){
            $tipo = 'Evento: ';
        }

        if($params['tipo'] == 'patrocinios'){
            $tipo = 'Patrocínio: ';
        }

        $evento = $model->getPedidoEvento($params['tipo'], $params['id'])->getResult()[0];

        $brindes = $model->getPedidoOrcamentoID($params['id'], $params['tipo'])->getResult();

        $model = new OrcamentosPainel();
        $orcamentos = $model->getOrcamentosByEvento($params['id'], $params['tipo'])->getResult();
            
        $this->render('painel/pages/orcamentos/lista.twig', ['menu' => $params['tipo'], 'dados' => $params, 'tipo' => $tipo, 'evento' => $evento, 'brindes' => $brindes, 'orcamentos' => $orcamentos]);
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
        $this->render('painel/pages/orcamentos/form.twig', ['dados' => $params, 'tipo' => $params['tipo']]);
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

        $fornecedor = new FornecedoresPainel();
        $fornecedor = $fornecedor->getFornecedorID($orcamento['id_fornecedor'])->getResultSingle();

        $model = new OrcamentosPainel();
        $arquivos = $model->getOrcamentosArquivos($params['id'], $params['id_edit'])->getResult();

        $this->render('painel/pages/orcamentos/form.twig', ['dados' => $params, 'orcamento' => $orcamento, 'fornecedor' => $fornecedor, 'parcelas' => $parcelas, 'arquivos' => $arquivos, 'tipo' => $params['tipo']]);
    }

    public function addOrcamentoSave($params)
    {
        $this->setParams($params);
        $save = new OrcamentosPainel();
        $save = $save->addOrcamentoSave($params);
        if($save){ echo $save; }
    }

    public function editOrcamentoSave($params)
    {
        $this->setParams($params);
        $save = new OrcamentosPainel();
        $save = $save->editOrcamentoSave($params);
        if($save){ echo '1'; }
    }

    public function tipoContrato($params)
    {
        $this->setParams($params);
        $update = new OrcamentosPainel();
        $update = $update->tipoContrato($params);
        if($update){ echo '1'; }
    }

    public function removeOrcamento($params)
    {
        $this->setParams($params);
        $remove = new OrcamentosPainel();
        $remove = $remove->removeOrcamento($params['id']);
        if($remove){ echo '1'; }
    }

}