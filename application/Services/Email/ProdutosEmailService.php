<?php
namespace Agencia\Close\Services\Email;

use Agencia\Close\Adapters\EmailAdapter;
use Agencia\Close\Models\Painel\PedidosPainel;
use Agencia\Close\Models\Painel\EmailsPainel;

class ProdutosEmailService
{
    public function enviarNovoPedido($params)
    {
        $pedido_id = $params['id_pedido'] ?? null;
        if (!$pedido_id) return;
        $pedidoModel = new PedidosPainel();
        $pedido = $pedidoModel->getPedidoID($pedido_id)->getResult();
        if (!$pedido) return;
        $pedido = $pedido[0];
        $itens = $pedidoModel->getPedidoIDItens($pedido['id'])->getResult();
        $evento = [];
        if ($pedido['tipo_evento'] != 'extra') {
            $evento = $pedidoModel->getPedidoEvento($pedido['tipo_evento'], $pedido['id_evento'])->getResult();
            if ($evento) $evento = $evento[0];
        }
        $dados = [
            'pedido' => $pedido,
            'itens' => $itens,
            'evento' => $evento,
        ];
        $email = new EmailAdapter();
        $email->setSubject('Sampel - Eventos - Novo Pedido Criado');
        $email->setBody('email/produtos/novo_pedido.twig', $dados);
        // Buscar emails configurados
        $emailsPainel = new EmailsPainel();
        $config = $emailsPainel->getByTipo('novo_pedido');
        if ($config && !empty($config['lista_emails'])) {
            $lista = explode(',', $config['lista_emails']);
            foreach ($lista as $mail) {
                $mail = trim($mail);
                if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $email->addAddress($mail);
                }
            }
        } else {
            $email->addAddress('ricardo@agenciaclose.com.br'); // fallback
        }
        $email->send('Novo pedido criado com sucesso!');
    }
    public function enviarEstoqueMinimo($params)
    {
        $email = new EmailAdapter();
        $dados = $params;
        $email->setSubject('Produto com Estoque Mínimo');
        $email->setBody('email/produtos/estoque_minimo.twig', $dados);
        $email->addAddress($dados['email_destinatario']);
        $email->send('Produto atingiu estoque mínimo!');
    }
    public function enviarEstoqueZerado($params)
    {
        $email = new EmailAdapter();
        $dados = $params;
        $email->setSubject('Produto com Estoque Zerado');
        $email->setBody('email/produtos/estoque_zerado.twig', $dados);
        $email->addAddress($dados['email_destinatario']);
        $email->send('Produto ficou sem estoque!');
    }
} 