<?php
namespace Agencia\Close\Controllers\Site\Emails;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Services\Email\ProdutosEmailService;

class EmailController extends Controller
{
    public function enviarEmailNovoPedido($params)
    {
        $this->setParams($params);
        $service = new ProdutosEmailService();
        $service->enviarNovoPedido($params);
    }
    public function enviarEmailEstoqueMinimo($params)
    {
        $this->setParams($params);
        $service = new ProdutosEmailService();
        $service->enviarEstoqueMinimo($params);
    }
    public function enviarEmailEstoqueZerado($params)
    {
        $this->setParams($params);
        $service = new ProdutosEmailService();
        $service->enviarEstoqueZerado($params);
    }
} 