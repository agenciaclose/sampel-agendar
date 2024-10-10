<?php

namespace Agencia\Close\Controllers\Painel\Home;

use Agencia\Close\Conn\Read;
use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\EmpenhoModel;
use Agencia\Close\Models\Painel\PedidosPainel;
use Agencia\Close\Models\Painel\VisitasPainel;
use Agencia\Close\Models\Painel\PalestrasPainel;
use Agencia\Close\Models\Painel\OrcamentosPainel;

class HomeController extends Controller
{   

    public $ano;
	
    public function index($params)
    {
        $this->ano = isset($_GET['ano']) ? $_GET['ano'] : date('Y');

        $this->setParams($params);

        $dadospedidos = $this->getDadosEmpenhoPedidos();
        $dadosvisitas = $this->getDadosEmpenhoVisitas();
        $dadospalestras = $this->getDadosEmpenhoPalestras();
        $dadoseventos = $this->getDadosEmpenhoEventos();
        $dadospatrocinios = $this->getDadosEmpenhoPadrocinios();

        $total_empenho = $dadospedidos['valorEmpenhoPedido'] + $dadosvisitas['valorEmpenhoPedido'] + $dadospalestras['valorEmpenhoPedido'] + $dadoseventos['valorEmpenhoPedido'] + $dadospatrocinios['valorEmpenhoPedido'];
        $total_consumo = $dadospedidos['pedidosValorTotalConsumo'] + $dadosvisitas['pedidosValorTotalConsumo'] + $dadospalestras['pedidosValorTotalConsumo'] + $dadoseventos['pedidosValorTotalConsumo'] + $dadospatrocinios['pedidosValorTotalConsumo'];

        $this->render('painel/pages/home/home.twig', [
            'menu' => 'home', 
            'dadospedidos' => $dadospedidos, 
            'dadosvisitas' => $dadosvisitas,
            'dadospalestras' => $dadospalestras,
            'dadoseventos' => $dadoseventos,
            'dadospatrocinios' => $dadospatrocinios,
            'total_empenho' => $total_empenho,
            'total_consumo' => $total_consumo
        ]);
    }

    // DADOS PEDIDOS
    public function getDadosEmpenhoPedidos()
    {
        $dados = [];

        $pedido = new PedidosPainel();
        $pedidosValorTotal = $pedido->getPedidosValorTotalByTipo('', $this->ano)->getResult();
        $pedidosValorTotalExtra = $pedido->getPedidosValorTotalByTipo('extra', $this->ano)->getResult();
        
        $empenho = new EmpenhoModel();
        $pedidos = $empenho->getPedidos($this->ano)->getResult();
        $empenhoPedidos = $empenho->getEmpenho('Pedidos', $this->ano)->getResult();

        if($empenhoPedidos){
            if($pedidosValorTotal){
                $pedidosValorTotalConsumo = $pedidosValorTotal[0]['valor_total_pedido'];
            }else{
                $pedidosValorTotalConsumo = 0;
            }

            if($empenhoPedidos){
                $valorEmpenhoPedido = $empenhoPedidos[0]['valor_empenho'];
            }else{
                $valorEmpenhoPedido = 0;
            }
        }else{
            $pedidosValorTotalConsumo = [];
            $valorEmpenhoPedido = [];
        }
        
        // Calcular a porcentagem restante
        $valorRestante = $valorEmpenhoPedido - $pedidosValorTotalConsumo;
        $porcentagemRestante = round(($valorRestante / $valorEmpenhoPedido) * 100, 0);

        // Adicionando os valores no array $dados
        $dados['pedidos'] =  count($pedidos);
        $dados['pedidosValorTotalConsumo'] = $pedidosValorTotalConsumo;
        $dados['pedidosValorTotal'] = $pedidosValorTotal[0]['valor_total_pedido'];
        $dados['pedidosValorTotalExtra'] = $pedidosValorTotalExtra[0]['valor_total_pedido'];
        $dados['valorEmpenhoPedido'] = $valorEmpenhoPedido;
        $dados['valorRestante'] = $valorRestante;
        $dados['porcentagemRestante'] = $porcentagemRestante;

        return $dados;
    }

    // DADOS VISITAS
    public function getDadosEmpenhoVisitas()
    {
        $dados = [];

        $visita = new EmpenhoModel();
        $visitas = $visita->getVisitasListAprovadas($this->ano)->getResult();
        
        $pedido = new PedidosPainel();
        $pedidosValorTotal = $pedido->getPedidosValorTotalByTipo('visitas', $this->ano)->getResult();

        $orcamento = new OrcamentosPainel();
        $orcamentosValorTotal = $orcamento->getOrcamentosByTipo('visitas', $this->ano)->getResult();

        $empenho = new EmpenhoModel();
        $empenhoPedidos = $empenho->getEmpenho('Visitas', $this->ano)->getResult();

        if($empenhoPedidos){
            if($orcamentosValorTotal){
                $valorTotalOrcamento = $orcamentosValorTotal[0]['valor_total_orcamento'];
            }else{
                $valorTotalOrcamento = 0;
            }

            if($pedidosValorTotal){
                $pedidosValorTotalConsumo = $pedidosValorTotal[0]['valor_total_pedido'];
            }else{
                $pedidosValorTotalConsumo = 0;
            }

            if($empenhoPedidos){
                $valorEmpenhoPedido = $empenhoPedidos[0]['valor_empenho'];
            }else{
                $valorEmpenhoPedido = 0;
            }
        }else{
            $pedidosValorTotalConsumo = [];
            $valorEmpenhoPedido = [];
        }
        
        // Calcular a porcentagem restante
        $totalConsumo = $valorTotalOrcamento;
        $valorRestante = $valorEmpenhoPedido - $totalConsumo;
        $porcentagemRestante = round(($valorRestante / $valorEmpenhoPedido) * 100, 0);

        // Adicionando os valores no array $dados
        $dados['visitas'] =  count($visitas);
        $dados['pedidosValorTotalConsumo'] = $totalConsumo;
        $dados['pedidosValorTotal'] = $pedidosValorTotalConsumo;
        $dados['valorEmpenhoPedido'] = $valorEmpenhoPedido;
        $dados['valorRestante'] = $valorRestante;
        $dados['porcentagemRestante'] = $porcentagemRestante;

        return $dados;
    }

    //DADOS PALESTRAS
    public function getDadosEmpenhoPalestras()
    {
        $dados = [];

        $palestra = new EmpenhoModel();
        $palestras = $palestra->getPalestrasList($this->ano)->getResult();
        
        $pedido = new PedidosPainel();
        $pedidosValorTotal = $pedido->getPedidosValorTotalByTipo('palestras', $this->ano)->getResult();

        $orcamento = new OrcamentosPainel();
        $orcamentosValorTotal = $orcamento->getOrcamentosByTipo('palestras', $this->ano)->getResult();

        $empenho = new EmpenhoModel();
        $empenhoPedidos = $empenho->getEmpenho('Palestras', $this->ano)->getResult();

        if($empenhoPedidos){
            if($orcamentosValorTotal){
                $valorTotalOrcamento = $orcamentosValorTotal[0]['valor_total_orcamento'];
            }else{
                $valorTotalOrcamento = 0;
            }

            if($pedidosValorTotal){
                $pedidosValorTotalConsumo = $pedidosValorTotal[0]['valor_total_pedido'];
            }else{
                $pedidosValorTotalConsumo = 0;
            }

            if($empenhoPedidos){
                $valorEmpenhoPedido = $empenhoPedidos[0]['valor_empenho'];
            }else{
                $valorEmpenhoPedido = 0;
            }
        }else{
            $pedidosValorTotalConsumo = [];
            $valorEmpenhoPedido = [];
        }
        
        // Calcular a porcentagem restante
        $totalConsumo = $valorTotalOrcamento;
        $valorRestante = $valorEmpenhoPedido - $totalConsumo;
        $porcentagemRestante = round(($valorRestante / $valorEmpenhoPedido) * 100, 0);

        // Adicionando os valores no array $dados
        $dados['palestras'] =  count($palestras);
        $dados['pedidosValorTotalConsumo'] = $totalConsumo;
        $dados['pedidosValorTotal'] = $pedidosValorTotalConsumo;
        $dados['valorEmpenhoPedido'] = $valorEmpenhoPedido;
        $dados['valorRestante'] = $valorRestante;
        $dados['porcentagemRestante'] = $porcentagemRestante;

        return $dados;
    }

    //DADOS EVENTOS
    public function getDadosEmpenhoEventos()
    {
        $dados = [];

        $evento = new EmpenhoModel();
        $eventos = $evento->getEventos($this->ano)->getResult();
        
        $pedido = new PedidosPainel();
        $pedidosValorTotal = $pedido->getPedidosValorTotalByTipo('eventos', $this->ano)->getResult();

        $orcamento = new OrcamentosPainel();
        $orcamentosValorTotal = $orcamento->getOrcamentosByTipo('eventos', $this->ano)->getResult();

        $empenho = new EmpenhoModel();
        $empenhoPedidos = $empenho->getEmpenho('Feiras e Eventos', $this->ano)->getResult();

        if($empenhoPedidos){
            if($orcamentosValorTotal){
                $valorTotalOrcamento = $orcamentosValorTotal[0]['valor_total_orcamento'];
            }else{
                $valorTotalOrcamento = 0;
            }

            if($pedidosValorTotal){
                $pedidosValorTotalConsumo = $pedidosValorTotal[0]['valor_total_pedido'];
            }else{
                $pedidosValorTotalConsumo = 0;
            }

            if($empenhoPedidos){
                $valorEmpenhoPedido = $empenhoPedidos[0]['valor_empenho'];
            }else{
                $valorEmpenhoPedido = 0;
            }
        }else{
            $pedidosValorTotalConsumo = [];
            $valorEmpenhoPedido = [];
        }
        
        // Calcular a porcentagem restante
        $totalConsumo = $valorTotalOrcamento;
        $valorRestante = $valorEmpenhoPedido - $totalConsumo;
        $porcentagemRestante = round(($valorRestante / $valorEmpenhoPedido) * 100, 0);

        // Adicionando os valores no array $dados
        $dados['eventos'] =  count($eventos);
        $dados['pedidosValorTotalConsumo'] = $totalConsumo;
        $dados['pedidosValorTotal'] = $pedidosValorTotalConsumo;
        $dados['valorEmpenhoPedido'] = $valorEmpenhoPedido;
        $dados['valorRestante'] = $valorRestante;
        $dados['porcentagemRestante'] = $porcentagemRestante;

        return $dados;
    }

    //DADOS EVENTOS
    public function getDadosEmpenhoPadrocinios()
    {
        $dados = [];

        $patrocinio = new EmpenhoModel();
        $patrocinios = $patrocinio->getPatrocinios($this->ano)->getResult();
        
        $pedido = new PedidosPainel();
        $pedidosValorTotal = $pedido->getPedidosValorTotalByTipo('patrocinios', $this->ano)->getResult();

        $orcamento = new OrcamentosPainel();
        $orcamentosValorTotal = $orcamento->getOrcamentosByTipo('patrocinios', $this->ano)->getResult();

        $empenho = new EmpenhoModel();
        $empenhoPedidos = $empenho->getEmpenho('Patrocinios', $this->ano)->getResult();

        if($empenhoPedidos){
            if($orcamentosValorTotal){
                $valorTotalOrcamento = $orcamentosValorTotal[0]['valor_total_orcamento'];
            }else{
                $valorTotalOrcamento = 0;
            }

            if($pedidosValorTotal){
                $pedidosValorTotalConsumo = $pedidosValorTotal[0]['valor_total_pedido'];
            }else{
                $pedidosValorTotalConsumo = 0;
            }

            if($empenhoPedidos){
                $valorEmpenhoPedido = $empenhoPedidos[0]['valor_empenho'];
            }else{
                $valorEmpenhoPedido = 0;
            }
        }else{
            $pedidosValorTotalConsumo = [];
            $valorEmpenhoPedido = [];
        }
        
        // Calcular a porcentagem restante
        $totalConsumo = $valorTotalOrcamento;
        $valorRestante = $valorEmpenhoPedido - $totalConsumo;
        $porcentagemRestante = round(($valorRestante / $valorEmpenhoPedido) * 100, 0);

        // Adicionando os valores no array $dados
        $dados['patrocinios'] =  count($patrocinios);
        $dados['pedidosValorTotalConsumo'] = $totalConsumo;
        $dados['pedidosValorTotal'] = $pedidosValorTotalConsumo;
        $dados['valorEmpenhoPedido'] = $valorEmpenhoPedido;
        $dados['valorRestante'] = $valorRestante;
        $dados['porcentagemRestante'] = $porcentagemRestante;

        return $dados;
    }

}