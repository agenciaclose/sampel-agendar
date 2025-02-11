<?php

namespace Agencia\Close\Controllers\Painel\Orcamentos;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\EventosPainel;
use Agencia\Close\Models\Painel\PedidosPainel;
use Agencia\Close\Models\Painel\VisitasPainel;
use Agencia\Close\Models\Painel\PalestrasPainel;
use Agencia\Close\Models\Painel\OrcamentosPainel;
use Agencia\Close\Models\Painel\PatrociniosPainel;
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
        $fornecedor = [];

        if(isset($params['fornecedor'])){
            $fornecedor = new FornecedoresPainel();
            $fornecedor = $fornecedor->getFornecedorID($params['fornecedor'])->getResultSingle();      
        }

        $eventos = [];
        if($params['tipo'] == 'eventos'){
            $eventos = new EventosPainel();
            $eventos = $eventos->getEventos()->getResult();
        }        
        if($params['tipo'] == 'visitas'){
            $eventos = new VisitasPainel();
            $eventos = $eventos->getVisitasList()->getResult();
        }
        if($params['tipo'] == 'palestras'){
            $eventos = new PalestrasPainel();
            $eventos = $eventos->getPalestrasList()->getResult();
        }
        if($params['tipo'] == 'patrocinios'){
            $eventos = new PatrociniosPainel();
            $eventos = $eventos->getPatrocinios($fornecedor['id'])->getResult();
        }

        $this->setParams($params);
        $this->render('painel/pages/orcamentos/form.twig', ['dados' => $params, 'eventos' => $eventos, 'tipo' => $params['tipo'], 'fornecedor' => $fornecedor]);
    }

    public function editOrcamento($params)
    {
        $this->setParams($params);

        $model = new OrcamentosPainel();
        $orcamentos = $model->getOrcamentosID($params['id'], $params['tipo'], $params['id_edit']);
        
        $parcelas = [];
        $orcamento = [];
        if($orcamentos->getResult()){
            $orcamento = $orcamentos->getResult()[0];
            $parcelas = $model->getOrcamentoParcelas($orcamento['id'])->getResult();
        }
        
        $fornecedor = [];
        if((isset($params['fornecedor'])) || (isset($orcamento['id_fornecedor']))){
            if($params['tipo'] == 'fornecedor'){
                $fornecedor = new FornecedoresPainel();
                $fornecedor = $fornecedor->getFornecedorID($params['fornecedor'])->getResultSingle();
            }else{
                $fornecedor = new FornecedoresPainel();
                $fornecedor = $fornecedor->getFornecedorID($orcamento['id_fornecedor'])->getResultSingle();
            }
        }

        $eventos = [];
        if($params['tipo'] == 'eventos'){
            $eventos = new EventosPainel();
            $eventos = $eventos->getEventos()->getResult();
        }        
        if($params['tipo'] == 'visitas'){
            $eventos = new VisitasPainel();
            $eventos = $eventos->getVisitasList()->getResult();
        }
        if($params['tipo'] == 'palestras'){
            $eventos = new PalestrasPainel();
            $eventos = $eventos->getPalestrasList()->getResult();
        }
        if($params['tipo'] == 'patrocinios'){
            $eventos = new PatrociniosPainel();
            $eventos = $eventos->getPatrocinios($fornecedor['id'])->getResult();
        }

        $model = new OrcamentosPainel();
        $arquivos = $model->getOrcamentosArquivos($params['id'], $params['id_edit'])->getResult();

        $this->render('painel/pages/orcamentos/form.twig', ['dados' => $params, 'eventos' => $eventos, 'orcamento' => $orcamento, 'fornecedor' => $fornecedor, 'parcelas' => $parcelas, 'arquivos' => $arquivos, 'tipo' => $params['tipo']]);
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

    public function getTermsTags($params)
    {
        $this->setParams($params);
        $model = new OrcamentosPainel();
        $terms = $model->getTermsTags();

        $json = [];
        if (count($terms->getResult()) > 0) {
            foreach ($terms->getResult() as $key => $value) {
                // Verifica se $value['tag_orcamento'] não é null ou vazio
                if (!empty($value['tag_orcamento'])) {
                    // Split the tag_orcamento by comma
                    $tags = explode(',', $value['tag_orcamento']);
                    
                    // Loop through each tag and add it to the $json array
                    foreach ($tags as $tag) {
                        $tag = trim($tag); // Remove espaços em branco
                        if (!empty($tag)) { // Ignora valores vazios
                            $json[] = ['id' => $tag, 'text' => $tag];
                        }
                    }
                }
            }
        }
        
        echo json_encode($json);
    }

    public function getTermsEventos($params)
    {
        $this->setParams($params);
        $model = new EventosPainel();
        $terms = $model->getEventos();
  
        $json = [];
        if(count($terms->getResult()) > 0){
            foreach ($terms->getResult() as $key => $value) {
                $json[] = ['id'=>$value['id'], 'text'=>$value['nome_evento']];
            }
        } else {
            $json[] = array('id' => '0', 'text' => 'Nenhum item encontrado');
        }
        echo json_encode($json);
    }

}