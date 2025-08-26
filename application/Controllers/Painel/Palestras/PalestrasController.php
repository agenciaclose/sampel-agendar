<?php

namespace Agencia\Close\Controllers\Painel\Palestras;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\PalestrasPainel;
use Agencia\Close\Services\Mailchimp\MailchimpService;
use Shuchkin\SimpleXLSX;

use League\Csv\Writer;
use League\Csv\CannotInsertRecord;

class PalestrasController extends Controller
{
    private MailchimpService $mailchimpService;

    public function __construct($router)
    {
        parent::__construct($router);
        $this->mailchimpService = new MailchimpService();
    }

    public function index($params)
    {
        $this->setParams($params);

        $palestras = new PalestrasPainel();
        $palestras = $palestras->getPalestrasList()->getResult();
        $this->render('painel/pages/palestras/lista.twig', ['menu' => 'palestras', 'palestras' => $palestras]);
    }

    public function view($params)
    {
        $this->setParams($params);

        $palestra = new PalestrasPainel(); 
        $palestra = $palestra->getPalestraID($params['id'])->getResult()[0];

        $listas = new PalestrasPainel();
        $listas = $listas->listarInscricoes($params['id'])->getResult();

        $this->render('painel/pages/palestras/view.twig', ['menu' => 'palestras', 'palestra' => $palestra, 'listas' => $listas]);
    }

    public function viewEdit($params)
    {
        $this->setParams($params);

        $palestra = new PalestrasPainel(); 
        $palestra = $palestra->getPalestraID($params['id'])->getResult()[0];

        $editar = new PalestrasPainel(); 
        $editar = $editar->getParticipanteID($params['id_inscricao'])->getResult()[0];

        $listas = new PalestrasPainel();
        $listas = $listas->listarInscricoes($params['id'])->getResult();

        $this->render('painel/pages/palestras/view.twig', ['menu' => 'palestras', 'palestra' => $palestra, 'listas' => $listas, 'editar' => $editar]);
    }

    public function criar($params)
    {
        $this->setParams($params);
        $this->render('painel/pages/palestras/form.twig', ['menu' => 'palestras']);
    }

    public function editar($params)
    {
        $this->setParams($params);
        
        $palestra = new PalestrasPainel();
        $palestra = $palestra->getPalestraID($params['id'])->getResult()[0];

        $this->render('painel/pages/palestras/form.twig', ['menu' => 'palestras', 'palestra' => $palestra,]);
    }

    public function SaveCadastro($params)
    {
        $this->setParams($params);
        $save = new PalestrasPainel();
        $save = $save->saveCadastro($this->params);
        if ($save) {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function SaveEditar($params)
    {
        $this->setParams($params);
        $editar = new PalestrasPainel();
        $editar = $editar->saveEditar($this->params);
        if ($editar) {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function palestraExcluir($params)
    {
        $this->setParams($params);
        $excluir = new PalestrasPainel();
        $excluir = $excluir->palestraExcluir($params);
        if ($excluir) {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function SaveCadastroParticipante($params)
    {
        $this->setParams($params);
        $save = new PalestrasPainel();
        $save = $save->saveCadastroParticipante($this->params);
        if ($save) {
            // Integração automática com Mailchimp
            $this->integrarComMailchimp($this->params, 'palestra_painel');
            
            echo '1';
        } else {
            echo '0';
        }
    }

    public function SaveEditarParticipante($params)
    {
        $this->setParams($params);
        $editar = new PalestrasPainel();
        $editar = $editar->saveEditarParticipante($this->params);
        if ($editar) {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function excluirParticipante($params)
    {
        $this->setParams($params);
        $excluir = new PalestrasPainel();
        $excluir = $excluir->saveExcluirParticipante($this->params);
        if ($excluir) {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function importar($params)
    {
        $this->setParams($params);

        if ($xlsx = SimpleXLSX::parse($_FILES['informacoes_arquivo']['tmp_name'])) {
			$arquivo = $xlsx->rows();

            for($x = 0;$x < count($arquivo);$x++){
				if($x > 0){
                    $palestras = new PalestrasPainel();
                    $palestras = $palestras->getParticipantesImportar($arquivo[$x], $params['id']);
                }
            }

            echo "1";

		} else {
			echo "ALGUM ERRO AO IMPORTAR";
		}
    }

    public function exportPalestrasParticipantes($filename = 'emails_palestras.csv')
    {
        // Cria uma instância de Visitas e obtém os dados
        $visitas = new PalestrasPainel();
        $lista = $visitas->listarUserExportPalestra()->getResult();

        try {
            // Verifica se o nome do arquivo é uma string e termina com .csv
            if (!is_string($filename) || !preg_match('/\.csv$/', $filename)) {
                $filename = 'emails_palestras.csv';
            }
            // Define o cabeçalho HTTP para baixar o arquivo antes de qualquer saída
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename="' . $filename . '"');

            // Cria um escritor de CSV a partir do caminho php://output
            $csv = Writer::createFromPath('php://output', 'w');
            $csv->setDelimiter(';');
            // Insere o cabeçalho
            $csv->insertOne(['Nome', 'Email', 'Telefone']);

            // Insere as linhas no arquivo CSV
            foreach ($lista as $row) {
                $csv->insertOne([$row['nome'], $row['email'], $row['telefone']]);
            }

        } catch (CannotInsertRecord $e) {
            // Captura erros de inserção
            echo 'Erro ao inserir registro: ' . $e->getMessage();
        }
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