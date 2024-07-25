<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class PedidosPainel extends Model
{
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

    public function addProductSave($params)
    {
        //FAZ O TRATAMENTO DOS ITENS PARA SALVAR
        $itens = $this->tratarParamsPedidos($params);

        $params['id_user'] = $_SESSION['sampel_user_id'];
        $params['id_user_update'] = $_SESSION['sampel_user_id'];

        //SALVA O PEDIDO
        $pedido = new Create();
        $pedido->ExeCreate('pedidos', [
            'id_user' => $params['id_user'],
            'id_user_update' => $params['id_user_update'],
            'tipo_evento' => $params['tipo_evento'],
            'id_evento' => $params['id_evento'],
            'descricao_pedido' => $params['descricao_pedido']
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


}