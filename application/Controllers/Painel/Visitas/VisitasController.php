<?php

namespace Agencia\Close\Controllers\Painel\Visitas;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\Visitas;
use Agencia\Close\Models\Painel\VisitasPainel;
use Agencia\Close\Models\Painel\InscricaoPainel;

use League\Csv\Writer;
use League\Csv\CannotInsertRecord;

class VisitasController extends Controller
{

    public function index($params)
    {
        $this->setParams($params);

        $visitas = new VisitasPainel();
        $visitas = $visitas->getVisitasList()->getResult();
        $this->render('painel/pages/visitas/lista.twig', ['menu' => 'visitas', 'visitas' => $visitas]);
    }

    public function view($params)
    {
        $this->setParams($params);

        $visita = new VisitasPainel();
        $visita = $visita->getVisitaID($params['id'])->getResult()[0];

        $listas = new VisitasPainel();
        $listas = $listas->listarInscricoes($params['id'])->getResult();

        $total = new VisitasPainel();
        $total = $total->listarInscricoesTotal($params['id'])->getResult()[0];

        $grupos = new VisitasPainel();
        $grupos = $grupos->listarInscricoesByGroup($params['id'])->getResult();

        $this->render('painel/pages/visitas/view.twig', ['menu' => 'visitas', 'visita' => $visita, 'listas' => $listas, 'grupos' => $grupos, 'total' => $total]);
    }

    public function aprovar($params)
    {
        $this->setParams($params);
        $visita = new VisitasPainel();
        $visita = $visita->visitaStatus($params['id'], 'Aprovado');
    }

    public function reprovar($params)
    {
        $this->setParams($params);
        $visita = new VisitasPainel();
        $visita = $visita->visitaStatus($params['id'], 'Recusado');
    }

    public function excluir($params)
    {
        $this->setParams($params);
        $visita = new VisitasPainel();
        $visita = $visita->visitaExcluir($params['id']);
    }

    public function criar($params)
    {
        $this->setParams($params);
        $this->render('painel/pages/visitas/form.twig', ['menu' => 'visitas']);
    }

    public function editar($params)
    {
        $this->setParams($params);

        $estados = new VisitasPainel();
        $estados = $estados->getEstados()->getResult();
        
        $visita = new VisitasPainel();
        $visita = $visita->getVisitaID($params['id'])->getResult()[0];

        $this->render('painel/pages/visitas/form.twig', ['menu' => 'visitas', 'visita' => $visita, 'estados' => $estados]);
    }

    public function inscricao($params)
    {
        $this->setParams($params);
        $visita = new InscricaoPainel();
        $visita = $visita->listarVisitaID($params['id'])->getResult()[0];

        $inscricao = '';
        
        $this->render('pages/visitas/inscricao.twig', ['menu' => 'visitas', 'visita' => $visita, 'inscricao' => $inscricao]);
    }

    public function editarInscricao($params)
    {
        $this->setParams($params);

        $inscricao = new Visitas();
        $inscricao = $inscricao->getInscricaoByCode($params['codigo'])->getResult()[0];

        $visita = new Visitas();
        $visita = $visita->listarVisitaID($inscricao['id_visita'])->getResult()[0];

        $this->render('painel/pages/visitas/editarInscricao.twig', ['menu' => 'visitas', 'visita' => $visita, 'inscricao' => $inscricao]);

    }

    public function editarInscricaoSave($params)
    {
        $this->setParams($params);

        $save = new VisitasPainel();
        $save = $save->getInscricaoSave($params);
        if($save){
            echo '1';
        }else{
            echo '0';
        }

    }

    public function exportVisitantes($filename = 'emails_eventos.csv')
    {
        // Cria uma instância de Visitas e obtém os dados
        $visitas = new Visitas();
        $lista = $visitas->listarUserExportVisita()->getResult();

        try {
            // Verifica se o nome do arquivo é uma string e termina com .csv
            if (!is_string($filename) || !preg_match('/\.csv$/', $filename)) {
                $filename = 'emails_eventos.csv';
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

}