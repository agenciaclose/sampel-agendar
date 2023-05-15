<?php

namespace Agencia\Close\Adapters\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PayStatus extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('payStatus', [$this, 'payStatus']),
        ];
    }

    public function payStatus($status): string
    {

        switch ($status) {
            case 'approved': $return = '<div class="badge bg-success" title="A transação foi paga pelo comprador e a 56 minutos já recebeu uma confirmação da instituição financeira responsável pelo processamento.">Paga</div>'; break;
            case 'pending': $return = '<div class="badge bg-warning" title="A transação foi iniciada, mas até o momento a 56 minutos não recebeu nenhuma informação sobre o pagamento.">Aguardando pagamento</div>'; break;
            case 'inprocess': $return = '<div class="badge bg-warning" title="O comprador optou por pagar com um cartão de crédito e a 56 minutos está analisando o risco da transação.">Em análise</div>'; break;
            case 'inmediation': $return = '<div class="badge bg-warning" title="O comprador, dentro do prazo de liberação da transação, abriu uma disputa.">Em disputa</div>'; break;
            case 'refunded': $return = '<div class="badge bg-danger" title="O valor da transação foi devolvido para o comprador.">Devolvida</div>'; break;
            case 'cancelled': $return = '<div class="badge bg-danger" title="A transação foi cancelada sem ter sido finalizada.">Cancelada</div>'; break;
            case 'chargedback': $return = '<div class="badge bg-danger" title="A transação foi cancelada e devolvida.">Devolução</div>'; break;
        }

        return $return;
    }
}