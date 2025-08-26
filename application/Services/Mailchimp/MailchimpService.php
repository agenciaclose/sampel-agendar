<?php

namespace Agencia\Close\Services\Mailchimp;

class MailchimpService
{
    private string $apiKey;
    private string $serverPrefix;
    private string $baseUrl;
    private string $listId;

    public function __construct()
    {
        // Configurações do Mailchimp
        $this->apiKey = 'd919b683708b52ba4cc816b50fcead70-us22';
        $this->serverPrefix = 'us22';
        $this->baseUrl = "https://{$this->serverPrefix}.api.mailchimp.com/3.0";
        $this->listId = 'dd52334e80'; // ID padrão da lista
    }

    /**
     * Processa um novo contato e adiciona a etiqueta "Sampel - Eventos"
     */
    public function processarContato($dados)
    {
        try {
            // Validar dados
            if (!$this->validarDadosContato($dados)) {
                return [
                    'success' => false,
                    'message' => 'Dados do contato inválidos'
                ];
            }

            // Preparar dados para o Mailchimp
            $mailchimpData = [
                'email_address' => $dados['email'],
                'status' => 'subscribed',
                'merge_fields' => [
                    'FNAME' => $dados['nome'] ?? '',
                    'LNAME' => $dados['sobrenome'] ?? '',
                    'COMPANY' => $dados['empresa'] ?? '',
                    'PHONE' => $dados['telefone'] ?? '',
                    'TITLE' => $dados['cargo'] ?? ''
                ],
                'tags' => ['Sampel - Eventos']
            ];

            // Adicionar etiquetas adicionais se fornecidas
            if (!empty($dados['etiquetas_adicionais'])) {
                if (is_array($dados['etiquetas_adicionais'])) {
                    foreach ($dados['etiquetas_adicionais'] as $etiqueta) {
                        $mailchimpData['tags'][] = $etiqueta;
                    }
                } else {
                    $mailchimpData['tags'][] = $dados['etiquetas_adicionais'];
                }
            }

            // Fazer chamada para API do Mailchimp
            $response = $this->fazerChamadaApi("/lists/{$this->listId}/members", 'POST', $mailchimpData);

            if ($response && isset($response['id'])) {
                return [
                    'success' => true,
                    'message' => 'Contato processado com sucesso!',
                    'data' => $response,
                    'etiquetas' => $mailchimpData['tags']
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erro ao processar contato no Mailchimp'
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao processar contato: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Adiciona a etiqueta "Sampel - Eventos" a um contato existente
     */
    public function adicionarEtiquetaSampel($email)
    {
        try {
            // Buscar contato existente
            $response = $this->fazerChamadaApi("/lists/{$this->listId}/members/" . md5(strtolower($email)));

            if (!$response) {
                return [
                    'success' => false,
                    'message' => 'Contato não encontrado no Mailchimp'
                ];
            }

            // Adicionar etiqueta "Sampel - Eventos"
            $etiquetas = $response['tags'] ?? [];
            $etiquetas[] = ['name' => 'Sampel - Eventos', 'status' => 'active'];

            $updateData = [
                'tags' => $etiquetas
            ];

            $updateResponse = $this->fazerChamadaApi(
                "/lists/{$this->listId}/members/" . md5(strtolower($email)), 
                'PATCH', 
                $updateData
            );

            if ($updateResponse) {
                return [
                    'success' => true,
                    'message' => 'Etiqueta "Sampel - Eventos" adicionada com sucesso!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erro ao adicionar etiqueta'
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao adicionar etiqueta: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Remove a etiqueta "Sampel - Eventos" de um contato
     */
    public function removerEtiquetaSampel($email)
    {
        try {
            // Buscar contato existente
            $response = $this->fazerChamadaApi("/lists/{$this->listId}/members/" . md5(strtolower($email)));

            if (!$response) {
                return [
                    'success' => false,
                    'message' => 'Contato não encontrado no Mailchimp'
                ];
            }

            // Remover etiqueta "Sampel - Eventos"
            $etiquetas = $response['tags'] ?? [];
            $etiquetas = array_filter($etiquetas, function($tag) {
                return $tag['name'] !== 'Sampel - Eventos';
            });

            $updateData = [
                'tags' => array_values($etiquetas)
            ];

            $updateResponse = $this->fazerChamadaApi(
                "/lists/{$this->listId}/members/" . md5(strtolower($email)), 
                'PATCH', 
                $updateData
            );

            if ($updateResponse) {
                return [
                    'success' => true,
                    'message' => 'Etiqueta "Sampel - Eventos" removida com sucesso!'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Erro ao remover etiqueta'
                ];
            }

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao remover etiqueta: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lista todos os contatos com a etiqueta "Sampel - Eventos"
     */
    public function listarContatosSampel($limit = 1000)
    {
        try {
            $response = $this->fazerChamadaApi("/lists/{$this->listId}/members?count={$limit}&tag=1");

            if ($response && isset($response['members'])) {
                // Filtrar apenas contatos com a etiqueta "Sampel - Eventos"
                $contatosSampel = array_filter($response['members'], function($member) {
                    if (isset($member['tags'])) {
                        foreach ($member['tags'] as $tag) {
                            if ($tag['name'] === 'Sampel - Eventos' && $tag['status'] === 'active') {
                                return true;
                            }
                        }
                    }
                    return false;
                });

                return [
                    'success' => true,
                    'data' => array_values($contatosSampel),
                    'total' => count($contatosSampel)
                ];
            }

            return [
                'success' => false,
                'message' => 'Erro ao listar contatos'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao listar contatos: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtém estatísticas dos contatos com etiqueta "Sampel - Eventos"
     */
    public function obterEstatisticasSampel()
    {
        try {
            $contatos = $this->listarContatosSampel();
            
            if (!$contatos['success']) {
                return $contatos;
            }

            $totalContatos = $contatos['total'];
            $empresas = [];
            $cargos = [];

            foreach ($contatos['data'] as $contato) {
                // Contar empresas
                $empresa = $contato['merge_fields']['COMPANY'] ?? 'Não informado';
                if (!isset($empresas[$empresa])) {
                    $empresas[$empresa] = 0;
                }
                $empresas[$empresa]++;

                // Contar cargos
                $cargo = $contato['merge_fields']['TITLE'] ?? 'Não informado';
                if (!isset($cargos[$cargo])) {
                    $cargos[$cargo] = 0;
                }
                $cargos[$cargo]++;
            }

            // Ordenar por quantidade
            arsort($empresas);
            arsort($cargos);

            return [
                'success' => true,
                'data' => [
                    'total_contatos' => $totalContatos,
                    'top_empresas' => array_slice($empresas, 0, 10, true),
                    'top_cargos' => array_slice($cargos, 0, 10, true)
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao obter estatísticas: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Exporta contatos com etiqueta "Sampel - Eventos" para CSV
     */
    public function exportarContatosSampelCSV()
    {
        try {
            $contatos = $this->listarContatosSampel();
            
            if (!$contatos['success']) {
                return $contatos;
            }

            $csv = "Nome,Sobrenome,Email,Empresa,Telefone,Cargo,Data Inscrição,Status\n";
            
            foreach ($contatos['data'] as $contato) {
                $csv .= sprintf(
                    '"%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                    $contato['merge_fields']['FNAME'] ?? '',
                    $contato['merge_fields']['LNAME'] ?? '',
                    $contato['email_address'] ?? '',
                    $contato['merge_fields']['COMPANY'] ?? '',
                    $contato['merge_fields']['PHONE'] ?? '',
                    $contato['merge_fields']['TITLE'] ?? '',
                    $contato['timestamp_signup'] ?? '',
                    $contato['status'] ?? ''
                );
            }

            return [
                'success' => true,
                'data' => $csv,
                'filename' => 'contatos_sampel_eventos_' . date('Y-m-d_H-i-s') . '.csv'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao exportar contatos: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Valida dados do contato
     */
    private function validarDadosContato($dados)
    {
        if (empty($dados['email']) || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if (empty($dados['nome']) || empty($dados['sobrenome'])) {
            return false;
        }

        return true;
    }

    /**
     * Faz chamada para API do Mailchimp
     */
    private function fazerChamadaApi($endpoint, $metodo = 'GET', $dados = null)
    {
        $url = $this->baseUrl . $endpoint;
        
        $ch = curl_init();
        
        $headers = [
            'Authorization: Basic ' . base64_encode('user:' . $this->apiKey),
            'Content-Type: application/json'
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        if ($metodo === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($dados) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
            }
        } elseif ($metodo === 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            if ($dados) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
            }
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            throw new \Exception('Erro cURL: ' . curl_error($ch));
        }
        
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return json_decode($response, true);
        } else {
            $errorData = json_decode($response, true);
            $errorMessage = $errorData['detail'] ?? 'Erro na API do Mailchimp';
            throw new \Exception($errorMessage . ' (HTTP: ' . $httpCode . ')');
        }
    }

    /**
     * Define o ID da lista (deve ser chamado antes de usar)
     */
    public function setListId($listId)
    {
        // Atualizar o endpoint para usar o ID da lista correto
        $this->baseUrl = "https://{$this->serverPrefix}.api.mailchimp.com/3.0";
        // TODO: Implementar lógica para usar o listId correto
    }

    /**
     * Verifica o status da API do Mailchimp
     */
    public function obterStatusAPI()
    {
        try {
            // Testar conectividade básica
            $response = $this->fazerChamadaApi('/ping');
            
            if ($response && isset($response['health_status'])) {
                return [
                    'success' => true,
                    'message' => 'API do Mailchimp está funcionando',
                    'health_status' => $response['health_status'],
                    'api_version' => '3.0'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Resposta inesperada da API'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao conectar com API: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Processa automaticamente uma inscrição e envia para o Mailchimp
     * Este método é chamado automaticamente quando há inscrições em visitas, palestras ou eventos
     */
    public function processarInscricaoAutomatica($dados)
    {
        try {
            // Preparar dados para o Mailchimp
            $dadosMailchimp = [
                'email' => $dados['email'],
                'nome' => $dados['nome'] ?? '',
                'sobrenome' => $dados['sobrenome'] ?? '',
                'empresa' => $dados['empresa'] ?? '',
                'telefone' => $dados['telefone'] ?? '',
                'cargo' => $dados['cargo'] ?? '',
                'observacoes' => $dados['observacoes'] ?? '',
                'origem' => $dados['tipo_inscricao'] ?? 'sistema'
            ];

            // Processar contato no Mailchimp (já adiciona "Sampel - Eventos")
            $resultado = $this->processarContato($dadosMailchimp);

            if ($resultado['success']) {
                // Adicionar etiqueta específica baseada no tipo de inscrição
                $etiquetaEspecifica = $this->obterEtiquetaEspecifica($dados['tipo_inscricao']);
                if ($etiquetaEspecifica) {
                    $this->adicionarEtiquetaEspecifica($dados['email'], $etiquetaEspecifica);
                }

                // Log de sucesso
                error_log("Inscrição processada automaticamente no Mailchimp: " . $dados['email'] . " com etiquetas: Sampel - Eventos + " . $etiquetaEspecifica);
            } else {
                // Log de erro
                error_log("Erro ao processar inscrição automaticamente no Mailchimp: " . $resultado['message']);
            }

            return $resultado;

        } catch (\Exception $e) {
            error_log("Exceção ao processar inscrição automática: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro interno ao processar inscrição automática'
            ];
        }
    }

    /**
     * Obtém a etiqueta específica baseada no tipo de inscrição
     */
    private function obterEtiquetaEspecifica($tipoInscricao)
    {
        switch ($tipoInscricao) {
            case 'visita':
            case 'visita_painel':
                return 'Sampel - Visitas';
            case 'palestra':
                return 'Sampel - Palestras';
            case 'evento':
                return 'Sampel - Eventos';
            default:
                return null;
        }
    }

    /**
     * Adiciona uma etiqueta específica a um contato
     */
    private function adicionarEtiquetaEspecifica($email, $etiqueta)
    {
        try {
            // Obter o subscriber hash
            $subscriberHash = $this->obterSubscriberHash($email);
            if (!$subscriberHash) {
                error_log("Não foi possível obter subscriber hash para: " . $email);
                return false;
            }

            // Adicionar a etiqueta específica
            $endpoint = "/lists/{$this->listId}/members/{$subscriberHash}/tags";
            $dados = [
                'tags' => [
                    [
                        'name' => $etiqueta,
                        'status' => 'active'
                    ]
                ]
            ];

            $resultado = $this->fazerChamadaApi($endpoint, 'POST', $dados);
            
            if ($resultado !== false) {
                error_log("Etiqueta '{$etiqueta}' adicionada com sucesso para: " . $email);
                return true;
            } else {
                error_log("Erro ao adicionar etiqueta '{$etiqueta}' para: " . $email);
                return false;
            }

        } catch (\Exception $e) {
            error_log("Erro ao adicionar etiqueta específica: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtém o subscriber hash do Mailchimp
     */
    private function obterSubscriberHash($email)
    {
        try {
            $md5Email = md5(strtolower($email));
            return $md5Email;
        } catch (\Exception $e) {
            error_log("Erro ao gerar subscriber hash: " . $e->getMessage());
            return null;
        }
    }
}

