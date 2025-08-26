<?php

namespace Agencia\Close\Controllers\Painel\Inscricao;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\InscricaoPainel;
use Agencia\Close\Services\Mailchimp\MailchimpService;

class InscricaoController extends Controller
{
    private MailchimpService $mailchimpService;

    public function __construct($router)
    {
        parent::__construct($router);
        $this->mailchimpService = new MailchimpService();
    }

    public function inscricao($params)
    {
        $this->setParams($params);
        $visita = new InscricaoPainel();
        $visita = $visita->listarVisitaID($params['id'])->getResult()[0];

        $inscricao = '';
        
        $this->render('painel/pages/visitas/inscricao.twig', ['menu' => 'visitas', 'visita' => $visita, 'inscricao' => $inscricao]);
    }

    public function inscricaoCadastro($params)
    {
        $this->setParams($params);

        $CheckUser = new InscricaoPainel();
        $CheckUser = $CheckUser->checkClient($params['email'])->getResult();
        
        if($CheckUser){
            $params['sampel_user_id'] = $CheckUser[0]['id'];
        }else{
            $cadastroUser = new InscricaoPainel();
            $cadastroUser = $cadastroUser->saveClient($params)->getResult();
            $params['sampel_user_id'] = $cadastroUser[0]['id'];
        }

        if( !$this->checkCadastro($params['email'], $params['id_visita']) ){
           
            $cadastro = new InscricaoPainel();
            $cadastro = $cadastro->inscricaoCadastro($params);

            if ($cadastro) {
                // Integração automática com Mailchimp
                $this->integrarComMailchimp($params, 'visita_painel');
                
                echo $params['sampel_user_id'];
            }

        }else{
            echo '0';
        }

    }

    public function inscricaoCadastroQRcode($params)
    {
        $this->setParams($params);
        $update = new InscricaoPainel();
        $update = $update->inscricaoCadastroQRcode($params);
    }

    public function checkCadastro($email, $visita_id)
    {
        $checkCadastro = new InscricaoPainel();
        $checkCadastro = $checkCadastro->checkCadastro($email, $visita_id)->getResult();
        return $checkCadastro;
    }

    /**
     * Integra automaticamente com o Mailchimp após inscrição
     */
    private function integrarComMailchimp($params, $tipoInscricao)
    {
        try {
            // Processar nome completo para separar nome e sobrenome
            $nomeCompleto = $params['nome'] ?? '';
            $nome = '';
            $sobrenome = '';
            
            if (!empty($nomeCompleto)) {
                $partesNome = explode(' ', trim($nomeCompleto));
                if (count($partesNome) > 1) {
                    $nome = $partesNome[0];
                    $sobrenome = implode(' ', array_slice($partesNome, 1));
                } else {
                    $nome = $nomeCompleto;
                }
            }
            
            // Preparar dados para o Mailchimp
            $dadosMailchimp = [
                'email' => $params['email'],
                'nome' => $nome,
                'sobrenome' => $sobrenome,
                'empresa' => $params['empresa'] ?? '',
                'telefone' => $params['telefone'] ?? '',
                'cargo' => $params['setor'] ?? '',
                'tipo_inscricao' => $tipoInscricao
            ];

            // Processar inscrição automaticamente no Mailchimp
            $this->mailchimpService->processarInscricaoAutomatica($dadosMailchimp);

        } catch (\Exception $e) {
            // Log do erro, mas não interrompe o fluxo da inscrição
            error_log("Erro na integração automática com Mailchimp: " . $e->getMessage());
        }
    }


}