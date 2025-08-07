<?php
namespace Agencia\Close\Controllers\Painel\Emailsconfig;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\EmailsPainel;

class ConfigemailsController extends Controller
{
    public function configEmails()
    {
        $model = new EmailsPainel();
        $config = $model->getByTipo('novo_pedido');
        $lista_emails = $config ? $config['lista_emails'] : '';
        $this->render('painel/pages/configuracoes/emails.twig', [
            'lista_emails' => $lista_emails
        ]);
    }

    public function salvar()
    {
        $lista_emails = isset($_POST['lista_emails']) ? trim($_POST['lista_emails']) : '';
        $model = new EmailsPainel();
        $model->salvar('novo_pedido', $lista_emails);
        $_SESSION['flash_success'] = 'E-mails salvos com sucesso!';
        $this->redirectUrl(DOMAIN . '/painel/configuracoes/emailsconfig');
    }
} 