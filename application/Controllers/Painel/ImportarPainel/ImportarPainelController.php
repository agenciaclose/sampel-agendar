<?php
namespace Agencia\Close\Controllers\Painel\ImportarPainel;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\ImportarPainel;
use Shuchkin\SimpleXLSX;

class ImportarPainelController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);
        $this->render('painel/pages/importar/index.twig', ['menu' => 'importar']);
    }

    public function salvar($params)
    {
        $this->setParams($params);
        
        if (!empty($_FILES['informacoes_arquivo']['tmp_name'])) {
            if ($xlsx = SimpleXLSX::parse($_FILES['informacoes_arquivo']['tmp_name'])) {
                $arquivo = $xlsx->rows();
            } else {
                echo "ALGUM ERRO AO IMPORTAR";
                return;
            }

            // Monta o array com os dados
            $header_values = [];
            $rows = [];

            foreach ($arquivo as $k => $r) {
                if ($k === 0) {
                    $header_values = $r;
                    continue;
                }
                $rows[] = array_combine($header_values, $r);
            }

            // Organiza os dados no formato desejado
            $dadosOrganizados = [];
            foreach ($rows as $row) {
                $dadosOrganizados[] = [
                    'data_palestra' => date('Y-m-d\TH:i', strtotime($row['DATA'] ?? '')),
                    'title' => $row['CLIENTE'] ?? '',
                    'cidade' => $row['CIDADE'] ?? '',
                    'estado' => $row['ESTADO'] ?? '',
                    'nome' => $row['REPRES.'] ?? '',
                    'pessoas' => (int) ($row['PESSOAS'] ?? 0),
                ];
            }

            // Aqui você pode processar os dados organizados
            foreach ($dadosOrganizados as $dado) {
                $this->salvarDado($dado);
            }

            echo "IMPORTADO COM SUCESSO!!!";
            
        }
    }

    // Método para salvar os dados (exemplo)
    private function salvarDado($palestra)
    {
        $model = new ImportarPainel();

        // Verifica se a palestra já existe
        $checkPalestras = $model->checkPalestras($palestra['title'], $palestra['data_palestra'])->getResultSingle();

        if ($checkPalestras) {
            $palestra['id_palestra'] = $checkPalestras['id'];
        } else {
            $savePalestras = $model->savePalestras($palestra)->getResultSingle();
            $palestra['id_palestra'] = $savePalestras['id'];
        }

        // Salva os dados das pessoas
        for ($i = 0; $i < $palestra['pessoas']; $i++) {
            $model->salvar($palestra);
        }
    }
}