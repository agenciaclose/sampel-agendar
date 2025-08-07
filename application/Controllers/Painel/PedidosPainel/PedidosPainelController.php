<?php

namespace Agencia\Close\Controllers\Painel\PedidosPainel;

use Agencia\Close\Helpers\Result;
use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\PedidosPainel;
use Agencia\Close\Models\Painel\ProdutosPainel;
use Agencia\Close\Models\Painel\EquipesPainel;
use Agencia\Close\Services\Email\ProdutosEmailService;

class PedidosPainelController extends Controller
{
    public function listPedidos($params)
    {
        $this->setParams($params);
        $this->permissions('pedidos', '"view"');

        $model = new PedidosPainel();
        $pedidos = $model->getPedidos()->getResult();

        $valor_total = $model->getPedidosTotalValor();
        if($valor_total->getResult()){
            $valor_total = $valor_total->getResult()[0]['valor_total'];
        }else{
            $valor_total = 0;
        }

        $model = new EquipesPainel();
        $equipes = $model->getEquipesList()->getResult();

        $this->render('painel/pages/pedidos/list.twig', ['menu' => 'pedidos',  'pedidos' => $pedidos,  'equipes' => $equipes, 'valor_total' => $valor_total]);
    }

    public function viewPedido($params)
    {
        $this->setParams($params);
        $this->permissions('pedidos', '"view"');

        $model = new PedidosPainel();
        $pedido = $model->getPedidoID($params['id']);

        if($pedido->getResult()){
            $pedido = $pedido->getResult()[0];
            
            $evento = [];
            if($pedido['tipo_evento'] != 'extra'){
                $evento = $model->getPedidoEvento($pedido['tipo_evento'], $pedido['id_evento'])->getResult()[0];
            }

            $itens = $model->getPedidoIDItens($pedido['id'])->getResult();
        }

        $this->render('painel/pages/pedidos/view.twig', ['menu' => 'pedidos',  'pedido' => $pedido, 'itens' => $itens, 'evento' => $evento]);
    }

    public function printPedido($params)
    {
        $this->setParams($params);

        $model = new PedidosPainel();
        $pedido = $model->getPedidoID($params['id']);

        if($pedido->getResult()){
            $pedido = $pedido->getResult()[0];
            
            $evento = [];
            if($pedido['tipo_evento'] != 'extra'){
                $evento = $model->getPedidoEvento($pedido['tipo_evento'], $pedido['id_evento'])->getResult()[0];
            }

            $itens = $model->getPedidoIDItens($pedido['id'])->getResult();
        }

        $this->render('painel/pages/pedidos/print.twig', ['menu' => 'pedidos',  'pedido' => $pedido, 'itens' => $itens, 'evento' => $evento]);
    }

    public function printFaturamentoPedido($params)
    {
        $this->setParams($params);

        $model = new PedidosPainel();
        $pedido = $model->getPedidoID($params['id']);

        if($pedido->getResult()){
            $pedido = $pedido->getResult()[0];
            
            $evento = [];
            if($pedido['tipo_evento'] != 'extra'){
                $evento = $model->getPedidoEvento($pedido['tipo_evento'], $pedido['id_evento'])->getResult()[0];
            }

            $itens = $model->getPedidoIDItens($pedido['id'])->getResult();
        }

        $this->render('painel/pages/pedidos/print-faturamento.twig', ['menu' => 'pedidos',  'pedido' => $pedido, 'itens' => $itens, 'evento' => $evento]);
    }

    public function addPedido($params)
    {
        $this->setParams($params);
        $this->permissions('pedidos', '"add"');

        $model = new ProdutosPainel();
        $produtos = $model->getProdutos()->getResult();

        $model = new EquipesPainel();
        $equipes = $model->getEquipesList()->getResult();

        $this->render('painel/pages/pedidos/add.twig', ['menu' => 'pedidos',  'produtos' => $produtos,  'equipes' => $equipes, 'estados' => $this->estados()]);
    }

    public function editPedido($params)
    {
        $this->setParams($params);
        $this->permissions('pedidos', '"add"');

        $model = new EquipesPainel();
        $equipes = $model->getEquipesList()->getResult();

        $model = new PedidosPainel();
        $pedido = $model->getPedidoID($params['id']);

        if($pedido->getResult()){
            $pedido = $pedido->getResult()[0];
            
            $evento = [];
            if($pedido['tipo_evento'] != 'extra'){
                $evento = $model->getPedidoEvento($pedido['tipo_evento'], $pedido['id_evento'])->getResult()[0];
            }

            $produtos = $model->getPedidoIDItensEdit($pedido['id'])->getResult();
        }

        $this->render('painel/pages/pedidos/edit.twig', ['menu' => 'pedidos', 'equipes' => $equipes, 'pedido' => $pedido, 'produtos' => $produtos, 'evento' => $evento, 'estados' => $this->estados()]);
    }

    public function getTipoEvento($params)
    {
        $this->setParams($params);
        $model = new PedidosPainel();

        if ($params['tipo'] == 'visitas') {
            $eventos = $model->getTipoVisitas()->getResult();
            echo json_encode($eventos, JSON_UNESCAPED_UNICODE);
        }

        if ($params['tipo'] == 'palestras') {
            $eventos = $model->getTipoPalestras()->getResult();
            echo json_encode($eventos, JSON_UNESCAPED_UNICODE);
        }

        if ($params['tipo'] == 'eventos') {
            $eventos = $model->getTipoEventos()->getResult();
            echo json_encode($eventos, JSON_UNESCAPED_UNICODE);
        }

        if ($params['tipo'] == 'patrocinios') {
            $eventos = $model->getTipopPatrocinios()->getResult();
            echo json_encode($eventos, JSON_UNESCAPED_UNICODE);
        }
    }

    public function addPedidoSave($params)
    {
        $this->setParams($params);
        $model = new PedidosPainel();
        $save = $model->addPedidoSave($params);

        //FAZ O ENVIO DO EMAIL
        if($save === 'success'){
            // Buscar o último pedido criado
            $ultimoPedido = $model->getUltimoPedidoID();
            if ($ultimoPedido->getResult()) {
                $id_pedido = $ultimoPedido->getResultSingle()['id'];
                $emailService = new ProdutosEmailService();
                $emailService->enviarNovoPedido(['id_pedido' => $id_pedido]);
            }
            echo '1';
        }
    }

    public function editPedidoSave($params)
    {
        $this->setParams($params);
        $model = new PedidosPainel();
        $save = $model->editPedidoSave($params);
        if($save === 'success'){
           echo '1'; 
        }
    }

    public function showModerate($params)
    {
        $this->permissions('pedidos', '"manager"');
        $model = new PedidosPainel();
        $pedido = $model->getPedidoID($params['id']);
        if($pedido->getResult()){
            $moderar = $pedido->getResult()[0];
        }else{
            $moderar = [];
        }

        $this->render('painel/pages/pedidos/moderate.twig', ['moderar' => $moderar]);
    }

    public function statusPedidoSave($params)
    {
        $this->setParams($params);
        $model = new PedidosPainel();
        
        // Buscar o status atual do pedido antes de alterar
        $pedido_atual = $model->getPedidoID($params['id'])->getResult();
        $status_anterior = null;
        if ($pedido_atual) {
            $status_anterior = $pedido_atual[0]['status_pedido'];
        }
        
        if($params['status_pedido'] == '0'){
            $model->statusRecusadoSave($params);
        }else{
            $model->statusPedidoSave($params);
        }
        
        // Enviar email de notificação de alteração de status
        if ($status_anterior != $params['status_pedido']) {
            $emailService = new ProdutosEmailService();
            $emailService->enviarStatusPedido([
                'id_pedido' => $params['id'],
                'novo_status' => $params['status_pedido'],
                'status_anterior' => $status_anterior
            ]);
        }
    }

    public function estados()
    {
        $estados = [
            'Acre', 
            'Alagoas', 
            'Amapá', 
            'Amazonas', 
            'Bahia', 
            'Ceará', 
            'Distrito Federal', 
            'Espírito Santo', 
            'Goiás', 
            'Maranhão', 
            'Mato Grosso', 
            'Mato Grosso do Sul', 
            'Minas Gerais', 
            'Pará', 
            'Paraíba', 
            'Paraná', 
            'Pernambuco', 
            'Piauí', 
            'Rio de Janeiro', 
            'Rio Grande do Norte', 
            'Rio Grande do Sul', 
            'Rondônia', 
            'Roraima', 
            'Santa Catarina', 
            'São Paulo', 
            'Sergipe', 
            'Tocantins'
        ];
        return $estados;
    }


    public function getEmitentData($params)
    {
        $emitenteNome = urlencode($params['emitenteNome']);
        $url = "http://187.92.201.2:8180/api/intranet/v1/emitente/$emitenteNome";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: insomnia/10.3.1',
            'Content-Type: application/json',
            // 'Authorization: Basic aW50ZWdyYWNhbzppbnRlZ3JhY2Fv' // Se for Basic Auth
            // 'Authorization: Bearer SEU_TOKEN' // Se for Bearer Token
        ]);

        // Se for Basic Auth, pode usar também:
        curl_setopt($ch, CURLOPT_USERPWD, 'integracao:integracao');

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Erro: ' . curl_error($ch);
        }
        curl_close($ch);
        echo $result;
    }
    
    public function userPedidosProdutos($params) {
        $this->setParams($params);

        $model = new PedidosPainel();
        $produtos = $model->getProdutosByUser($params['id']);
        if($produtos->getResult()){
            $produtos = $produtos->getResult();
        }else{
            $produtos = [];
        }

        $this->render('painel/pages/pedidos/userPedidos.twig', ['menu' => 'dashboard-pedidos', 'produtos' => $produtos]);

    }
}