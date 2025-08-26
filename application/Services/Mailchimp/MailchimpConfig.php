<?php

namespace Agencia\Close\Services\Mailchimp;

use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Update;

class MailchimpConfig
{
    private Read $read;
    private Update $update;
    private array $config = [];

    public function __construct()
    {
        $this->read = new Read();
        $this->update = new Update();
        $this->carregarConfiguracoes();
    }

    /**
     * Carrega todas as configurações do banco de dados
     */
    private function carregarConfiguracoes()
    {
        $this->read->ExeRead('mailchimp_config');
        if ($this->read->getResult()) {
            foreach ($this->read->getResult() as $config) {
                $this->config[$config['chave']] = $config['valor'];
            }
        }
    }

    /**
     * Obtém uma configuração específica
     */
    public function get($chave, $padrao = null)
    {
        return $this->config[$chave] ?? $padrao;
    }

    /**
     * Define uma configuração
     */
    public function set($chave, $valor, $descricao = null)
    {
        try {
            $this->update->ExeUpdate('mailchimp_config', [
                'valor' => $valor,
                'descricao' => $descricao,
                'updated_at' => date('Y-m-d H:i:s')
            ], "WHERE chave = :chave", "chave={$chave}");

            if ($this->update->getResult()) {
                $this->config[$chave] = $valor;
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtém a API key
     */
    public function getApiKey(): string
    {
        return $this->get('api_key', 'd919b683708b52ba4cc816b50fcead70-us22');
    }

    /**
     * Obtém o server prefix
     */
    public function getServerPrefix(): string
    {
        return $this->get('server_prefix', 'us22');
    }

    /**
     * Obtém o ID da lista
     */
    public function getListId(): string
    {
        return $this->get('list_id', '');
    }

    /**
     * Define o ID da lista
     */
    public function setListId($listId): bool
    {
        return $this->set('list_id', $listId, 'ID da lista de distribuição');
    }

    /**
     * Obtém a URL do webhook
     */
    public function getWebhookUrl(): string
    {
        return $this->get('webhook_url', '');
    }

    /**
     * Define a URL do webhook
     */
    public function setWebhookUrl($url): bool
    {
        return $this->set('webhook_url', $url, 'URL do webhook para receber notificações');
    }

    /**
     * Verifica se a sincronização está habilitada
     */
    public function isSyncEnabled(): bool
    {
        return $this->get('sync_enabled', '1') === '1';
    }

    /**
     * Habilita ou desabilita a sincronização
     */
    public function setSyncEnabled(bool $enabled): bool
    {
        return $this->set('sync_enabled', $enabled ? '1' : '0', 'Habilitar sincronização automática');
    }

    /**
     * Obtém o intervalo de sincronização em segundos
     */
    public function getSyncInterval(): int
    {
        return (int) $this->get('sync_interval', '3600');
    }

    /**
     * Define o intervalo de sincronização
     */
    public function setSyncInterval(int $seconds): bool
    {
        return $this->set('sync_interval', (string) $seconds, 'Intervalo de sincronização em segundos');
    }

    /**
     * Obtém a URL base da API
     */
    public function getBaseUrl(): string
    {
        $serverPrefix = $this->getServerPrefix();
        return "https://{$serverPrefix}.api.mailchimp.com/3.0";
    }

    /**
     * Obtém todas as configurações
     */
    public function getAll(): array
    {
        return $this->config;
    }

    /**
     * Verifica se as configurações estão completas
     */
    public function isConfigured(): bool
    {
        return !empty($this->getApiKey()) && 
               !empty($this->getServerPrefix()) && 
               !empty($this->getListId());
    }

    /**
     * Obtém informações de status da configuração
     */
    public function getStatus(): array
    {
        return [
            'api_key' => !empty($this->getApiKey()),
            'server_prefix' => !empty($this->getServerPrefix()),
            'list_id' => !empty($this->getListId()),
            'webhook_url' => !empty($this->getWebhookUrl()),
            'sync_enabled' => $this->isSyncEnabled(),
            'sync_interval' => $this->getSyncInterval(),
            'configured' => $this->isConfigured()
        ];
    }

    /**
     * Reseta as configurações para os valores padrão
     */
    public function resetToDefault(): bool
    {
        $defaults = [
            'api_key' => 'd919b683708b52ba4cc816b50fcead70-us22',
            'server_prefix' => 'us22',
            'list_id' => '',
            'webhook_url' => '',
            'sync_enabled' => '1',
            'sync_interval' => '3600'
        ];

        $success = true;
        foreach ($defaults as $chave => $valor) {
            if (!$this->set($chave, $valor)) {
                $success = false;
            }
        }

        return $success;
    }
}
