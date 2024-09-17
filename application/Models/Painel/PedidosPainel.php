<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class PedidosPainel extends Model
{

    public function getFilter()
    {
        $where = '';

        if((!empty($_GET['de'])) OR (!empty($_GET['ate']))){

            if(empty($_GET['de'])){
                $_GET['de'] = '2024-07-01';
            }
            if(empty($_GET['ate'])){
                $_GET['ate'] = date('Y:m:d');
            }

            $where .= " AND DATE(p.`date_create`) BETWEEN '".$_GET['de']."' AND '".$_GET['ate']."' ";
        }

        if(!empty($_GET['id_equipe'])){
            $where .= " AND p.`id_equipe` = '".$_GET['id_equipe']."' ";
        }

        if(!empty($_GET['status_pedido'])){
            $where .= " AND p.`status_pedido` = '".$_GET['status_pedido']."' ";
        }

        if(!empty($_GET['estado'])){
            $where .= " AND p.`estado_pedido` = '".$_GET['estado']."' ";
        }

        return $where;
    }

    public function getPedidos($by_user = null): Read
    {
        $where = $this->getFilter();

        $read = new Read();
        $read->FullRead("SELECT p.*, u.nome AS equipe, v.title AS nome_visita, e.nome_evento, pa.title AS nome_palestra FROM pedidos AS p 
        INNER JOIN usuarios AS u ON u.id = p.id_equipe 
        LEFT JOIN visitas AS v ON v.id = p.id_evento
        LEFT JOIN eventos AS e ON e.id = p.id_evento
        LEFT JOIN palestras AS pa ON pa.id = p.id_evento
        WHERE p.ativo = 'S' $where $by_user ORDER BY `id` DESC");
        return $read;
    }

    public function getPedidosTotalValor($by_user = null): Read
    {
        $where = $this->getFilter();

        $read = new Read();
        $read->FullRead("SELECT SUM(p.valor_total_pedido) AS valor_total FROM pedidos AS p 
            INNER JOIN usuarios AS u ON u.id = p.id_equipe 
            WHERE p.ativo = 'S' AND p.status_pedido not in ('0', '1') $where $by_user ORDER BY p.`id` DESC");
        return $read;
    }

    public function getPedidosValorTotal(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT SUM(valor_total_pedido) as valor_total_pedido FROM pedidos WHERE ativo = 'S' AND status_pedido not in ('0', '1') ORDER BY `id` DESC");
        return $read;
    }

    public function getPedidosValorTotalByTipo($tipo_evento, $ano): Read
    {
        $read = new Read();
        $read->FullRead("SELECT SUM(valor_total_pedido) as valor_total_pedido FROM pedidos WHERE ativo = 'S' AND status_pedido not in ('0', '1') AND tipo_evento = :tipo_evento AND YEAR(`date_create`) = :ano ORDER BY `id` DESC", "tipo_evento={$tipo_evento}&ano={$ano}");
        return $read;
    }

    public function getPedidoID($id_pedido): Read
    {
        $read = new Read();
        $read->FullRead("SELECT p.*, u.nome AS equipe FROM pedidos AS p INNER JOIN usuarios AS u ON u.id = p.id_equipe WHERE p.id = :id ORDER BY p.`id` DESC", "id={$id_pedido}");
        return $read;
    }

    public function getPedidoOrcamentoID($id_evento, $tipo_evento): Read
    {
        $read = new Read();
        $read->FullRead("SELECT p.*, u.nome AS equipe FROM pedidos AS p INNER JOIN usuarios AS u ON u.id = p.id_equipe WHERE p.id_evento = :id_evento AND p.tipo_evento = :tipo_evento ORDER BY p.`id` DESC", "id_evento={$id_evento}&tipo_evento={$tipo_evento}");
        return $read;
    }

    public function getPedidoIDItens($id_pedido): Read
    {
        $read = new Read();
        $read->FullRead("SELECT *, ppi.quantidade as qtd_escolhida FROM pedidos_itens AS ppi
                        INNER JOIN produtos AS p ON p.id = ppi.id_produto
                        WHERE ppi.id_pedido = :id_pedido AND ppi.status_itens = 'S' ORDER BY ppi.`date_create` ASC", "id_pedido={$id_pedido}");
        return $read;
    }

    public function getPedidoIDItensEdit($id_pedido): Read
    {
        $read = new Read();
        $read->FullRead("SELECT *, p.quantidade as qtd_produto, pi.quantidade as qtd_escolhida FROM produtos p
                        LEFT JOIN (SELECT * FROM pedidos_itens WHERE id_pedido = :id_pedido AND status_itens = 'S') pi
                        ON p.id = pi.id_produto ORDER BY pi.id_produto DESC", "id_pedido={$id_pedido}");
        return $read;
    }

    public function getPedidoEvento($tipo_evento, $id_evento): Read
    {
        $read = new Read();

        if($tipo_evento == 'visitas'){
            $read->FullRead("SELECT *, title as nome_evento, data_visita as data_evento FROM visitas WHERE id = :id_evento", "id_evento={$id_evento}");
        }

        if($tipo_evento == 'palestras'){
            $read->FullRead("SELECT *, title as nome_evento, data_palestra as data_evento FROM palestras WHERE id = :id_evento", "id_evento={$id_evento}");
        }

        if($tipo_evento == 'eventos'){
            $read->FullRead("SELECT *, data_evento_inicio as data_evento FROM eventos WHERE id = :id_evento", "id_evento={$id_evento}");
        }
        
        return $read;
    }

    public function getTipoVisitas(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT id, title, data_visita AS data FROM visitas WHERE status_visita NOT IN ('Cancelado','Recusado') ORDER BY id DESC");
        return $read;
    }

    public function getTipoPalestras(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT id, title, DATE_FORMAT(data_palestra, '%Y-%m-%d') AS data FROM palestras WHERE status_palestra NOT IN ('Cancelado','Recusado') AND id > 11 ORDER BY id DESC");
        return $read;
    }

    public function getTipoEventos(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT id, nome_evento AS title, data_evento_inicio AS data FROM eventos WHERE status_evento = 'Ativo' ORDER BY id DESC");
        return $read;
    }

    // CRIA NOVO PEDIDO
    public function addPedidoSave($params)
    {
        //FAZ O TRATAMENTO DOS ITENS PARA SALVAR
        $itens = $this->tratarParamsPedidos($params);

        $params['id_user'] = $_SESSION['sampel_user_id'];
        $params['id_user_update'] = $_SESSION['sampel_user_id'];
        
        if($params['id_evento'] == ''){
            $params['id_evento'] = 0;
        }

        $emitente_codigo = '';
        $emitente_nome = '';
        $emitente_cep = '';
        $emitente_endereco = '';
        $emitente_bairrro = '';
        $emitente_cidade = '';
        $emitente_estado = '';

        if($params['emitente_codigo'] != ''){
            $emitente_codigo = $params['emitente_codigo'];
        }
        if($params['emitente_nome'] != ''){
            $emitente_nome = $params['emitente_nome'];
        }
        if($params['emitente_cep'] != ''){
            $emitente_cep = $params['emitente_cep'];
        }
        if($params['emitente_endereco'] != ''){
            $emitente_endereco = $params['emitente_endereco'];
        }
        if($params['emitente_bairrro'] != ''){
            $emitente_bairrro = $params['emitente_bairrro'];
        }
        if($params['emitente_cidade'] != ''){
            $emitente_cidade = $params['emitente_cidade'];
        }
        if($params['emitente_estado'] != ''){
            $emitente_estado = $params['emitente_estado'];
        }

        $valor_total_pedido = $params['valor_total_pedido'];
        $valor_total_pedido = str_replace(',', '.', str_replace('.', '', $valor_total_pedido));
        $valor_total_pedido = number_format((float)$valor_total_pedido, 2, '.', '');

        //SALVA O PEDIDO
        $pedido = new Create();
        $pedido->ExeCreate('pedidos', [
            'id_user' => $params['id_user'],
            'id_user_update' => $params['id_user_update'],
            'id_equipe' => $params['id_equipe'],
            'id_evento' => $params['id_evento'],
            'tipo_evento' => $params['tipo_evento'],
            'estado_pedido' => $params['estado_pedido'],
            'descricao_pedido' => $params['descricao_pedido'],
            'emitente_codigo' => $emitente_codigo,
            'emitente_nome' => $emitente_nome,
            'emitente_cep' => $emitente_cep,
            'emitente_endereco' => $emitente_endereco,
            'emitente_bairrro' => $emitente_bairrro,
            'emitente_cidade' => $emitente_cidade,
            'emitente_estado' => $emitente_estado,
            'valor_total_pedido' => $valor_total_pedido
        ]);

        //RETORNA O ID DO PEDIDO
        $id_pedido = $pedido->getResult();

        //SALVA OS ITENS DO PRODUTO
        foreach ($itens as $product) {

            $pedido_itens = new Create();
            $pedido_itens->ExeCreate('pedidos_itens', [
                'id_pedido' => $id_pedido,
                'id_user' => $params['id_user'],
                'id_produto' => $product['id_produto'],
                'quantidade' => $product['quantidade'],
                'valor_unid' => $product['valor_unid'],
                'qt_total' => $product['qt_total'],
                'valor_total' => $product['valor_total']
            ]);

            //ATUALIZA DIMUNINDO O ESTOQUE DOS PRODUTOS
            $estoque_update = new Read();
            $estoque_update->FullRead("UPDATE `produtos` SET `quantidade` = (`quantidade` - :quantidade), `estoque` = (`estoque` - :estoque) 
                                    WHERE `id` = :id", "id={$product['id_produto']}&quantidade={$product['quantidade']}&estoque={$product['qt_total']}");
        }
        
        return 'success';
    }


    // EDITAR PEDIDO
    public function editPedidoSave($params)
    {
        //RETORNA O ID DO PEDIDO
        $id_pedido = $params['id'];
        unset($params['id']);

        //FAZ O TRATAMENTO DOS ITENS PARA SALVAR
        $itens = $this->tratarParamsPedidos($params);

        $params['id_user_update'] = $_SESSION['sampel_user_id'];

        if($params['id_equipe'] == ''){
            $params['id_equipe'] = 0;
        }

        if($params['id_evento'] == ''){
            $params['id_evento'] = 0;
        }

        $emitente_codigo = '';
        $emitente_nome = '';
        $emitente_cep = '';
        $emitente_endereco = '';
        $emitente_bairrro = '';
        $emitente_cidade = '';
        $emitente_estado = '';

        if($params['emitente_codigo'] != ''){
            $emitente_codigo = $params['emitente_codigo'];
        }
        if($params['emitente_nome'] != ''){
            $emitente_nome = $params['emitente_nome'];
        }
        if($params['emitente_cep'] != ''){
            $emitente_cep = $params['emitente_cep'];
        }
        if($params['emitente_endereco'] != ''){
            $emitente_endereco = $params['emitente_endereco'];
        }
        if($params['emitente_bairrro'] != ''){
            $emitente_bairrro = $params['emitente_bairrro'];
        }
        if($params['emitente_cidade'] != ''){
            $emitente_cidade = $params['emitente_cidade'];
        }
        if($params['emitente_estado'] != ''){
            $emitente_estado = $params['emitente_estado'];
        }

        $valor_total_pedido = $params['valor_total_pedido'];
        $valor_total_pedido = str_replace(',', '.', str_replace('.', '', $valor_total_pedido));
        $valor_total_pedido = number_format((float)$valor_total_pedido, 2, '.', '');

        //ATUALIZA O PEDIDO
        $pedido = new Update();
        $pedido->ExeUpdate('pedidos', [
            'id_user_update' => $params['id_user_update'],
            'id_equipe' => $params['id_equipe'],
            'id_evento' => $params['id_evento'],
            'tipo_evento' => $params['tipo_evento'],
            'estado_pedido' => $params['estado_pedido'],
            'descricao_pedido' => $params['descricao_pedido'],
            'emitente_codigo' => $emitente_codigo,
            'emitente_nome' => $emitente_nome,
            'emitente_cep' => $emitente_cep,
            'emitente_endereco' => $emitente_endereco,
            'emitente_bairrro' => $emitente_bairrro,
            'emitente_cidade' => $emitente_cidade,
            'emitente_estado' => $emitente_estado,
            'valor_total_pedido' => $valor_total_pedido
        ], 'WHERE id = :id', "id={$id_pedido}");


        // REALOCA DE VOLTA O ESTOQUE
        $devolver = new Read();
        $devolver->FullRead("SELECT * FROM pedidos_itens WHERE id_pedido = :id_pedido AND `status_itens` = 'S'", "id_pedido={$id_pedido}");

        if ($devolver->getResult()) {
            foreach ($devolver->getResult() as $p_itens) {
                $devolver_update = new Read();
                $devolver_update->FullRead("UPDATE `produtos` SET `quantidade` = (`quantidade` + :quantidade), `estoque` = (`estoque` + :estoque) 
                                    WHERE `id` = :id", "id={$p_itens['id_produto']}&quantidade={$p_itens['quantidade']}&estoque={$p_itens['qt_total']}");
            }
        }
        $devolver_desativa = new Read();
        $devolver_desativa->FullRead("UPDATE `pedidos_itens` SET `status_itens` = 'N' WHERE `id_pedido` = :id_pedido", "id_pedido={$id_pedido}");
        
        //SALVA OS ITENS DO PRODUTO
        foreach ($itens as $product) {

            $pedido_itens = new Create();
            $pedido_itens->ExeCreate('pedidos_itens', [
                'id_pedido' => $id_pedido,
                'id_user' => $params['id_user_update'],
                'id_produto' => $product['id_produto'],
                'quantidade' => $product['quantidade'],
                'valor_unid' => $product['valor_unid'],
                'qt_total' => $product['qt_total'],
                'valor_total' => $product['valor_total']
            ]);

            //ATUALIZA DIMUNINDO O ESTOQUE DOS PRODUTOS
            $estoque_update = new Read();
            $estoque_update->FullRead("UPDATE `produtos` SET `quantidade` = (`quantidade` - :quantidade), `estoque` = (`estoque` - :estoque) 
                                    WHERE `id` = :id", "id={$product['id_produto']}&quantidade={$product['quantidade']}&estoque={$product['qt_total']}");
        }
        
        return 'success';
    }


    public function tratarParamsPedidos($params)
    {
        $filteredProducts = [];
        
        foreach ($params['id_produto'] as $key => $id_produto) {
            if ($params['quantidade'][$key] > 0) {

                $valor_unid = $params['valor_unid'][$key];
                $valor_unid = str_replace(',', '.', str_replace('.', '', $valor_unid));
                $valor_unid = number_format((float)$valor_unid, 2, '.', '');

                $valor_total = $params['valor_total'][$key];
                $valor_total = str_replace(',', '.', str_replace('.', '', $valor_total));
                $valor_total = number_format((float)$valor_total, 2, '.', '');

                $filteredProducts[] = [
                    'id_produto' => $id_produto,
                    'quantidade' => $params['quantidade'][$key],
                    'valor_unid' => $valor_unid,
                    'qt_total' => $params['qt_total'][$key],
                    'valor_total' => $valor_total
                ];
            }
        }

        return $filteredProducts;
    }


    public function statusRecusadoSave($params): Read
    {
        $itens = new Read();
        $itens->FullRead("SELECT * FROM pedidos_itens WHERE id_pedido = :id_pedido AND `status_itens` = 'S'", "id_pedido={$params['id']}");

        if ($itens->getResult()) {
            foreach ($itens->getResult() as $item) {
                $estoque_update = new Read();
                $estoque_update->FullRead("UPDATE `produtos` SET `quantidade` = (`quantidade` + :quantidade), `estoque` = (`estoque` + :estoque) 
                                    WHERE `id` = :id", "id={$item['id_produto']}&quantidade={$item['quantidade']}&estoque={$item['qt_total']}");
            }
        }

        $itens = new Read();
        $itens->FullRead("UPDATE `pedidos` SET `status_pedido` = :status_pedido WHERE id = :id", "id={$params['id']}&status_pedido={$params['status_pedido']}");
        return $itens;
    }

    public function statusPedidoSave($params): Read
    {
        $itens = new Read();
        $itens->FullRead("UPDATE `pedidos` SET `status_pedido` = :status_pedido WHERE id = :id", "id={$params['id']}&status_pedido={$params['status_pedido']}");
        return $itens;
    }

    public function getPedidosTransportadoras()
    {
        $itens = new Read();
        $itens->FullRead("SELECT * FROM pedidos WHERE status_pedido in ('5', '6') AND ativo = 'S'");
        return $itens;
    }

    public function getPedidosTransportadorasTotal()
    {
        $itens = new Read();
        $itens->FullRead("SELECT SUM(valor_total_pedido) as valor_total_pedido FROM pedidos WHERE status_pedido in ('5', '6') AND ativo = 'S'");
        return $itens;
    }

    public function getPedidosRetirada()
    {
        $itens = new Read();
        $itens->FullRead("SELECT * FROM pedidos WHERE status_pedido in ('7', '8') AND ativo = 'S'");
        return $itens;
    }

    public function getPedidosRetiradaTotal()
    {
        $itens = new Read();
        $itens->FullRead("SELECT SUM(valor_total_pedido) as valor_total_pedido FROM pedidos WHERE status_pedido in ('7', '8') AND ativo = 'S'");
        return $itens;
    }

    public function getCurvaABCProdutos()
    {
        $itens = new Read();
        $itens->FullRead("WITH ProdutoQuantidades AS (SELECT pi.id_produto, SUM(pi.qt_total) AS quantidade_total FROM pedidos_itens pi GROUP BY pi.id_produto), ProdutoPercentuais AS (SELECT pq.id_produto, pq.quantidade_total, pq.quantidade_total * 100.0 / SUM(pq.quantidade_total) OVER () AS percentual FROM ProdutoQuantidades pq), ProdutoClassificacao AS (SELECT pq.id_produto, pq.quantidade_total, pq.percentual, SUM(pq.percentual) OVER (ORDER BY pq.percentual DESC) AS acumulado FROM ProdutoPercentuais pq) SELECT p.codigo, p.nome, p.imagem, pc.quantidade_total, ROUND(pc.percentual) AS porcentagem,
        ROUND(pc.acumulado) AS acumulado, CASE WHEN pc.acumulado <= 80 THEN 'A' WHEN pc.acumulado <= 95 THEN 'B' ELSE 'C' END AS curva_abc 
        FROM ProdutoClassificacao pc INNER JOIN produtos p ON p.id = pc.id_produto ORDER BY pc.acumulado");
        return $itens;
    }

    public function getpedidosPorEquipe()
    {
        $itens = new Read();
        $itens->FullRead("SELECT p.id_equipe, u.nome AS nome_usuario, u.email, COUNT(p.id) AS quantidade_pedidos, ROUND((COUNT(p.id) / (SELECT COUNT(*) FROM pedidos) * 100)) AS porcentagem_pedidos, SUM(p.valor_total_pedido) AS valor_total_pedido
                FROM pedidos p INNER JOIN usuarios u ON p.id_equipe = u.id GROUP BY u.id, u.nome ORDER BY valor_total_pedido DESC");
        return $itens;
    }

    public function getpedidosPorEstados()
    {
        $itens = new Read();
        $itens->FullRead("SELECT estado_pedido, COUNT(*) AS quantidade_pedidos, ROUND((COUNT(*) / (SELECT COUNT(*) FROM pedidos) * 100)) AS porcentagem_pedidos
                        FROM pedidos GROUP BY estado_pedido ORDER BY quantidade_pedidos DESC");
        return $itens;
    }

    public function getProdutosByUser($id_user)
    {
        $itens = new Read();
        $itens->FullRead("SELECT pr.*, SUM(p.quantidade) AS total_quantidade, SUM(p.quantidade * pr.unidades) qtd_total, SUM(p.quantidade * pr.unidades) * p.valor_unid AS valor_total
        FROM pedidos_itens p
        INNER JOIN produtos pr ON p.id_produto = pr.id
        INNER JOIN pedidos AS pp ON pp.id = p.id_pedido
        WHERE pp.id_equipe = :id_user AND p.status_itens = 'S' AND pr.PDV = 'N'
        GROUP BY p.id_user, p.id_produto
        ORDER BY qtd_total DESC;", "id_user={$id_user}");
        return $itens;
    }

}