<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class PedidosPainel extends Model
{
    public function getPedidos(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT p.*, u.nome AS equipe FROM pedidos AS p INNER JOIN usuarios AS u ON u.id = p.id_equipe ORDER BY `id` DESC");
        return $read;
    }

    public function getPedidoID($id_pedido): Read
    {
        $read = new Read();
        $read->FullRead("SELECT p.*, u.nome AS equipe FROM pedidos AS p INNER JOIN usuarios AS u ON u.id = p.id_equipe WHERE p.id = :id ORDER BY p.`id` DESC", "id={$id_pedido}");
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
    public function addProductSave($params)
    {
        //FAZ O TRATAMENTO DOS ITENS PARA SALVAR
        $itens = $this->tratarParamsPedidos($params);

        $params['id_user'] = $_SESSION['sampel_user_id'];
        $params['id_user_update'] = $_SESSION['sampel_user_id'];

        if($params['id_equipe'] == ''){
            $params['id_equipe'] = 0;
        }

        if($params['id_evento'] == ''){
            $params['id_evento'] = 0;
        }

        //SALVA O PEDIDO
        $pedido = new Create();
        $pedido->ExeCreate('pedidos', [
            'id_user' => $params['id_user'],
            'id_user_update' => $params['id_user_update'],
            'id_equipe' => $params['id_equipe'],
            'id_evento' => $params['id_evento'],
            'tipo_evento' => $params['tipo_evento'],
            'solicitante' => $params['solicitante'],
            'estado_pedido' => $params['estado_pedido'],
            'finalidade' => $params['finalidade'],
            'descricao_pedido' => $params['descricao_pedido'],
            'valor_total_pedido' => $params['valor_total_pedido']
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
    public function editProductSave($params)
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

        //ATUALIZA O PEDIDO
        $pedido = new Update();
        $pedido->ExeUpdate('pedidos', [
            'id_user_update' => $params['id_user_update'],
            'id_equipe' => $params['id_equipe'],
            'id_evento' => $params['id_evento'],
            'tipo_evento' => $params['tipo_evento'],
            'solicitante' => $params['solicitante'],
            'estado_pedido' => $params['estado_pedido'],
            'finalidade' => $params['finalidade'],
            'descricao_pedido' => $params['descricao_pedido'],
            'valor_total_pedido' => $params['valor_total_pedido']
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


}