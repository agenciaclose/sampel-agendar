<?php

namespace Agencia\Close\Controllers\Site\Pedidos;

use Agencia\Close\Helpers\Result;
use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\PedidosPainel;
use Agencia\Close\Models\Painel\ProdutosPainel;
use Agencia\Close\Models\Painel\EquipesPainel;
use Agencia\Close\Services\Email\ProdutosEmailService;

class PedidosController extends Controller
{
    public function listPedidos($params)
    {
        $this->setParams($params);
        $this->permissions('pedidos', '"view"');

        $model = new PedidosPainel();
        $by_user = '';
        if($_SESSION['sampel_user_tipo'] != 1){
            if ($this->checkPermissionsUser('pedidos', '"manager"') == null){ 
                $by_user = ' AND p.id_equipe = '.$_SESSION['sampel_user_id'].'';
            }
        };

        $pedidos = $model->getPedidos($by_user)->getResult();
        $valor_total = $model->getPedidosTotalValor($by_user);
        if($valor_total->getResult()){
            $valor_total = $valor_total->getResult()[0]['valor_total'];
        }else{
            $valor_total = 0;
        }

        $model = new EquipesPainel();
        $equipes = $model->getEquipesList()->getResult();

        $this->render('pages/pedidos/list.twig', ['menu' => 'pedidos',  'pedidos' => $pedidos,  'equipes' => $equipes, 'valor_total' => $valor_total]);
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

        $this->render('pages/pedidos/view.twig', ['menu' => 'pedidos',  'pedido' => $pedido, 'itens' => $itens, 'evento' => $evento]);
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

        $this->render('pages/pedidos/print.twig', ['menu' => 'pedidos',  'pedido' => $pedido, 'itens' => $itens, 'evento' => $evento]);
    }

    public function addPedido($params)
    {
        $this->setParams($params);
        $this->permissions('pedidos', '"add"');

        $model = new ProdutosPainel();
        $produtos = $model->getProdutos()->getResult();

        $model = new EquipesPainel();
        $equipes = $model->getEquipesList()->getResult();

        $this->render('pages/pedidos/add.twig', ['menu' => 'pedidos',  'produtos' => $produtos,  'equipes' => $equipes, 'estados' => $this->estados()]);
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

        $this->render('pages/pedidos/edit.twig', ['menu' => 'pedidos', 'equipes' => $equipes, 'pedido' => $pedido, 'produtos' => $produtos, 'evento' => $evento, 'estados' => $this->estados()]);
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
    }

    public function addPedidoSave($params)
    {
        $this->setParams($params);
        $model = new PedidosPainel();
        $save = $model->addPedidoSave($params);
        if($save === 'success'){
            //FAZ O ENVIO DO EMAIL
            if ($save === 'success') {
                // Buscar o último pedido criado
                $ultimoPedido = $model->getUltimoPedidoID();
                if ($ultimoPedido->getResult()) {
                    $id_pedido = $ultimoPedido->getResultSingle()['id'];
                    $emailService = new ProdutosEmailService();
                    $emailService->enviarNovoPedido(['id_pedido' => $id_pedido]);
                }
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

    public function showModerate()
    {
        $this->permissions('pedidos', '"manager"');
        $model = new PedidosPainel();
        $pedido = $model->getPedidoID($_GET['id']);
        if($pedido->getResult()){
            $moderar = $pedido->getResult()[0];
        }else{
            $moderar = [];
        }

        $this->render('pages/pedidos/moderate.twig', ['moderar' => $moderar]);
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
}