<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Models\Model;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Update;
use Agencia\Close\Conn\Delete;

class Mailchimp extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->create = new Create();
        $this->read = new Read();
        $this->update = new Update();
        $this->delete = new Delete();
    }

    public function salvarInscricao($dados)
    {
        try {
            $this->create->ExeCreate('mailchimp_inscricoes', [
                'email' => $dados['email'],
                'nome' => $dados['nome'],
                'sobrenome' => $dados['sobrenome'],
                'empresa' => $dados['empresa'] ?? '',
                'telefone' => $dados['telefone'] ?? '',
                'cargo' => $dados['cargo'] ?? '',
                'observacoes' => $dados['observacoes'] ?? '',
                'etiqueta' => $dados['etiqueta'] ?? 'Sampel - Eventos',
                'status' => $dados['status'] ?? 'ativo',
                'origem' => $dados['origem'] ?? 'api',
                'data_cadastro' => $dados['data_cadastro'] ?? date('Y-m-d H:i:s'),
                'ip' => $dados['ip'] ?? '',
                'user_agent' => $dados['user_agent'] ?? '',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return $this->create->getResult();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function buscarInscricao($email)
    {
        $this->read->ExeRead('mailchimp_inscricoes', "WHERE email = :email", "email={$email}");
        return $this->read;
    }

    public function atualizarStatusInscricao($email, $status)
    {
        try {
            $this->update->ExeUpdate('mailchimp_inscricoes', [
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s')
            ], "WHERE email = :email", "email={$email}");

            return $this->update->getResult();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function atualizarInscricao($email, $dados)
    {
        try {
            $this->update->ExeUpdate('mailchimp_inscricoes', $dados, "WHERE email = :email", "email={$email}");
            return $this->update->getResult();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function listarInscricoes($filtros = [])
    {
        $where = "WHERE 1=1";
        $parseString = "";

        if (!empty($filtros['status'])) {
            $where .= " AND status = :status";
            $parseString .= "status={$filtros['status']}&";
        }

        if (!empty($filtros['etiqueta'])) {
            $where .= " AND etiqueta = :etiqueta";
            $parseString .= "etiqueta={$filtros['etiqueta']}&";
        }

        if (!empty($filtros['empresa'])) {
            $where .= " AND empresa LIKE :empresa";
            $parseString .= "empresa=%{$filtros['empresa']}%&";
        }

        if (!empty($filtros['origem'])) {
            $where .= " AND origem = :origem";
            $parseString .= "origem={$filtros['origem']}&";
        }

        if (!empty($filtros['data_inicio'])) {
            $where .= " AND data_cadastro >= :data_inicio";
            $parseString .= "data_inicio={$filtros['data_inicio']}&";
        }

        if (!empty($filtros['data_fim'])) {
            $where .= " AND data_cadastro <= :data_fim";
            $parseString .= "data_fim={$filtros['data_fim']}&";
        }

        $order = "ORDER BY data_cadastro DESC";
        
        if (!empty($filtros['limit'])) {
            $order .= " LIMIT :limit";
            $parseString .= "limit={$filtros['limit']}&";
        }

        $this->read->ExeRead('mailchimp_inscricoes', $where . " " . $order, $parseString);
        return $this->read;
    }

    public function removerInscricao($email)
    {
        try {
            $this->delete->ExeDelete('mailchimp_inscricoes', "WHERE email = :email", "email={$email}");
            return $this->delete->getResult();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function buscarInscricaoPorMailchimpId($mailchimpId)
    {
        $this->read->ExeRead('mailchimp_inscricoes', "WHERE mailchimp_id = :mailchimp_id", "mailchimp_id={$mailchimpId}");
        return $this->read;
    }

    public function buscarInscricoesRecentes($limit = 10)
    {
        $this->read->ExeRead('mailchimp_inscricoes', "WHERE status = 'ativo' ORDER BY data_cadastro DESC LIMIT :limit", "limit={$limit}");
        return $this->read;
    }

    public function buscarInscricoesPorEmpresa($empresa)
    {
        $this->read->ExeRead('mailchimp_inscricoes', "WHERE empresa LIKE :empresa AND status = 'ativo' ORDER BY data_cadastro DESC", "empresa=%{$empresa}%");
        return $this->read;
    }

    public function buscarInscricoesPorEtiqueta($etiqueta)
    {
        $this->read->ExeRead('mailchimp_inscricoes', "WHERE etiqueta = :etiqueta AND status = 'ativo' ORDER BY data_cadastro DESC", "etiqueta={$etiqueta}");
        return $this->read;
    }

    public function buscarInscricoesPorOrigem($origem)
    {
        $this->read->ExeRead('mailchimp_inscricoes', "WHERE origem = :origem AND status = 'ativo' ORDER BY data_cadastro DESC", "origem={$origem}");
        return $this->read;
    }

    public function buscarEstatisticas()
    {
        try {
            // Total de contatos ativos
            $this->read->ExeRead('mailchimp_inscricoes', "WHERE status = 'ativo'");
            $total_ativos = $this->read->getRowCount();

            // Total de contatos removidos
            $this->read->ExeRead('mailchimp_inscricoes', "WHERE status = 'removido'");
            $total_removidos = $this->read->getRowCount();

            // Contatos por etiqueta
            $this->read->ExeRead('mailchimp_inscricoes', "WHERE status = 'ativo' GROUP BY etiqueta");
            $contatos_por_etiqueta = $this->read->getResult();

            // Contatos por origem
            $this->read->ExeRead('mailchimp_inscricoes', "WHERE status = 'ativo' GROUP BY origem");
            $contatos_por_origem = $this->read->getResult();

            // Contatos do mês atual
            $mes_atual = date('Y-m');
            $this->read->ExeRead('mailchimp_inscricoes', "WHERE status = 'ativo' AND DATE_FORMAT(data_cadastro, '%Y-%m') = :mes", "mes={$mes_atual}");
            $contatos_mes = $this->read->getRowCount();

            // Contatos da semana atual
            $semana_atual = date('Y-W');
            $this->read->ExeRead('mailchimp_inscricoes', "WHERE status = 'ativo' AND DATE_FORMAT(data_cadastro, '%Y-%u') = :semana", "semana={$semana_atual}");
            $contatos_semana = $this->read->getRowCount();

            // Contatos de hoje
            $hoje = date('Y-m-d');
            $this->read->ExeRead('mailchimp_inscricoes', "WHERE status = 'ativo' AND DATE(data_cadastro) = :hoje", "hoje={$hoje}");
            $contatos_hoje = $this->read->getRowCount();

            // Top empresas
            $this->read->ExeRead('mailchimp_inscricoes', "WHERE status = 'ativo' AND empresa != '' GROUP BY empresa ORDER BY COUNT(*) DESC LIMIT 10");
            $top_empresas = $this->read->getResult();

            return [
                'total_ativos' => $total_ativos,
                'total_removidos' => $total_removidos,
                'contatos_mes' => $contatos_mes,
                'contatos_semana' => $contatos_semana,
                'contatos_hoje' => $contatos_hoje,
                'contatos_por_etiqueta' => $contatos_por_etiqueta,
                'contatos_por_origem' => $contatos_por_origem,
                'top_empresas' => $top_empresas
            ];
        } catch (\Exception $e) {
            return [
                'total_ativos' => 0,
                'total_removidos' => 0,
                'contatos_mes' => 0,
                'contatos_semana' => 0,
                'contatos_hoje' => 0,
                'contatos_por_etiqueta' => [],
                'contatos_por_origem' => [],
                'top_empresas' => []
            ];
        }
    }

    public function buscarContatosDuplicados()
    {
        try {
            $this->read->FullRead("
                SELECT email, COUNT(*) as total, 
                       GROUP_CONCAT(nome SEPARATOR ', ') as nomes,
                       GROUP_CONCAT(empresa SEPARATOR ', ') as empresas
                FROM mailchimp_inscricoes 
                WHERE status = 'ativo'
                GROUP BY email 
                HAVING COUNT(*) > 1
                ORDER BY total DESC
            ");
            return $this->read;
        } catch (\Exception $e) {
            return $this->read;
        }
    }

    public function buscarContatosPorPeriodo($data_inicio, $data_fim)
    {
        try {
            $this->read->ExeRead(
                'mailchimp_inscricoes', 
                "WHERE status = 'ativo' AND data_cadastro BETWEEN :data_inicio AND :data_fim ORDER BY data_cadastro DESC",
                "data_inicio={$data_inicio}&data_fim={$data_fim}"
            );
            return $this->read;
        } catch (\Exception $e) {
            return $this->read;
        }
    }

    public function buscarContatosPorCargo($cargo)
    {
        try {
            $this->read->ExeRead(
                'mailchimp_inscricoes', 
                "WHERE status = 'ativo' AND cargo LIKE :cargo ORDER BY data_cadastro DESC",
                "cargo=%{$cargo}%"
            );
            return $this->read;
        } catch (\Exception $e) {
            return $this->read;
        }
    }

    public function limparContatosDuplicados()
    {
        try {
            // Buscar emails duplicados
            $duplicados = $this->buscarContatosDuplicados();
            
            if (!$duplicados->getResult()) {
                return true;
            }

            $emails_duplicados = $duplicados->getResult();
            $total_removidos = 0;

            foreach ($emails_duplicados as $duplicado) {
                // Manter apenas o primeiro registro, remover os demais
                $this->read->ExeRead(
                    'mailchimp_inscricoes', 
                    "WHERE email = :email ORDER BY id ASC LIMIT 1", 
                    "email={$duplicado['email']}"
                );
                
                $primeiro = $this->read->getResult();
                if ($primeiro) {
                    $primeiro_id = $primeiro[0]['id'];
                    
                    // Remover registros duplicados (manter apenas o primeiro)
                    $this->delete->ExeDelete(
                        'mailchimp_inscricoes', 
                        "WHERE email = :email AND id != :id", 
                        "email={$duplicado['email']}&id={$primeiro_id}"
                    );
                    
                    $total_removidos += $this->delete->getRowCount();
                }
            }

            return $total_removidos;
        } catch (\Exception $e) {
            return false;
        }
    }
}
