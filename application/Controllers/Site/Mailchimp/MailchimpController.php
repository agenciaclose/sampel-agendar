<?php

namespace Agencia\Close\Controllers\Site\Mailchimp;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Services\Mailchimp\MailchimpService;

class MailchimpController extends Controller
{
    private MailchimpService $mailchimpService;

    public function __construct($router)
    {
        parent::__construct($router);
        $this->mailchimpService = new MailchimpService();
    }

    /**
     * Processa um novo contato
     */
    public function processarContato($params)
    {
        $this->setParams($params);
        
        // Validar dados recebidos
        if (empty($_POST['email']) || empty($_POST['nome']) || empty($_POST['sobrenome'])) {
            $this->responseJson([
                'success' => false, 
                'message' => 'Email, nome e sobrenome são obrigatórios'
            ]);
            return;
        }

        // Preparar dados do contato
        $dados = [
            'email' => trim($_POST['email']),
            'nome' => trim($_POST['nome']),
            'sobrenome' => trim($_POST['sobrenome']),
            'empresa' => trim($_POST['empresa'] ?? ''),
            'telefone' => trim($_POST['telefone'] ?? ''),
            'cargo' => trim($_POST['cargo'] ?? ''),
            'observacoes' => trim($_POST['observacoes'] ?? ''),
            'origem' => $_POST['origem'] ?? 'api'
        ];

        // Processar contato e adicionar etiqueta "Sampel - Eventos"
        $resultado = $this->mailchimpService->processarContato($dados);
        
        $this->responseJson($resultado);
    }


    /**
     * Adiciona etiqueta a um contato
     */
    public function adicionarEtiqueta($params)
    {
        $this->setParams($params);
        
        if (empty($_POST['email']) || empty($_POST['etiqueta'])) {
            $this->responseJson([
                'success' => false, 
                'message' => 'Email e etiqueta são obrigatórios'
            ]);
            return;
        }

        $email = trim($_POST['email']);
        $etiqueta = trim($_POST['etiqueta']);
        
        $resultado = $this->mailchimpService->adicionarEtiquetaSampel($email);
        
        $this->responseJson($resultado);
    }

    /**
     * Remove etiqueta de um contato
     */
    public function removerEtiqueta($params)
    {
        $this->setParams($params);
        
        if (empty($_POST['email']) || empty($_POST['etiqueta'])) {
            $this->responseJson([
                'success' => false, 
                'message' => 'Email e etiqueta são obrigatórios'
            ]);
            return;
        }

        $email = trim($_POST['email']);
        $etiqueta = trim($_POST['etiqueta']);
        
        $resultado = $this->mailchimpService->removerEtiquetaSampel($email);
        
        $this->responseJson($resultado);
    }

    /**
     * Endpoint para receber contatos via API externa
     */
    public function receberContatoAPI($params)
    {
        $this->setParams($params);
        
        // Verificar se é uma requisição POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->responseJson([
                'success' => false, 
                'message' => 'Método não permitido'
            ]);
            return;
        }

        // Obter dados do JSON
        $jsonData = json_decode(file_get_contents('php://input'), true);
        
        if (!$jsonData) {
            $this->responseJson([
                'success' => false, 
                'message' => 'Dados JSON inválidos'
            ]);
            return;
        }

        // Validar dados obrigatórios
        if (empty($jsonData['email']) || empty($jsonData['nome']) || empty($jsonData['sobrenome'])) {
            $this->responseJson([
                'success' => false, 
                'message' => 'Email, nome e sobrenome são obrigatórios'
            ]);
            return;
        }

        // Preparar dados
        $dados = [
            'email' => trim($jsonData['email']),
            'nome' => trim($jsonData['nome']),
            'sobrenome' => trim($jsonData['sobrenome']),
            'empresa' => trim($jsonData['empresa'] ?? ''),
            'telefone' => trim($jsonData['telefone'] ?? ''),
            'cargo' => trim($jsonData['cargo'] ?? ''),
            'observacoes' => trim($jsonData['observacoes'] ?? ''),
            'origem' => 'api_externa'
        ];

        // Processar contato e adicionar etiqueta "Sampel - Eventos"
        $resultado = $this->mailchimpService->processarContato($dados);
        
        $this->responseJson($resultado);
    }

    /**
     * Obtém estatísticas dos contatos com etiqueta "Sampel - Eventos"
     */
    public function obterEstatisticas($params)
    {
        $this->setParams($params);
        
        $resultado = $this->mailchimpService->obterEstatisticasSampel();
        
        $this->responseJson($resultado);
    }

    /**
     * Exporta contatos com etiqueta "Sampel - Eventos" para CSV
     */
    public function exportarCSV($params)
    {
        $this->setParams($params);
        
        $resultado = $this->mailchimpService->exportarContatosSampelCSV();
        
        if ($resultado['success']) {
            // Configurar headers para download do CSV
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $resultado['filename'] . '"');
            
            // Output do CSV
            echo $resultado['data'];
            exit;
        } else {
            $this->responseJson($resultado);
        }
    }

    /**
     * Verifica o status da API do Mailchimp
     */
    public function verificarStatusAPI($params)
    {
        $this->setParams($params);
        
        $resultado = $this->mailchimpService->obterStatusAPI();
        
        $this->responseJson($resultado);
    }
}
