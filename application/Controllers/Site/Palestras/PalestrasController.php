<?php

namespace Agencia\Close\Controllers\Site\Palestras;

use Picqer\Barcode\BarcodeGeneratorPNG;
use Agencia\Close\Models\Site\Palestras;
use Agencia\Close\Services\Mailchimp\MailchimpService;
use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\PalestrasPainel;

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

        $palestras = new Palestras();
        $palestras = $palestras->listaTotal($params)->getResult();

        $this->render('pages/palestras/lista.twig', ['menu' => 'palestras', 'palestras' => $palestras]);
    }

    public function cadastro($params)
    {
        $this->setParams($params);
        $this->render('pages/palestras/cadastro.twig', ['menu' => 'palestras']);
    }

    public function palestraSave($params)
    {
        $this->setParams($params);
        $save = new Palestras();
        $save = $save->palestraSave($params);

        if($save){
            $last = new Palestras();
            $last = $last->lastPalestra()->getResult()[0];
            echo $last['id'];
        }else{
            echo "0";
        }
    }

    public function palestraEditar($params)
    {
        $this->setParams($params);
        $save = new Palestras();
        $save = $save->palestraEditar($params);
    }

    public function palestraQRcodeFeedbackSave($params){
        $save = new Palestras();
        $save = $save->palestraQRcodeFeedbackSave($params);
    }

    public function palestraQRcodeSave($params){
        $save = new Palestras();
        $save = $save->palestraQRcodeSave($params);
    }

    public function palestraGetQRcode($params){
        $palestra = new Palestras();
        $palestra = $palestra->getPalestra($params['id'])->getResult()[0];
        $this->render('pages/palestras/qrcode.twig', ['menu' => 'palestras', 'palestra' => $palestra]);
    }

    public function inscricao($params)
    {
        $this->setParams($params);

        $palestra = new Palestras();
        $palestra = $palestra->getPalestra($params['id']);

        if($palestra->getResult()){
            $palestra = $palestra->getResult()[0];
        }

        $totalInscricoes = new Palestras();
        $totalInscricoes = $totalInscricoes->getTotalInscricoes($params['id'])->getResultSingle();

        if(isset($_GET['action'])){

            $inscricao = new Palestras();
            $inscricao = $inscricao->getInscricao($params['id'], $params['inscricao'])->getResult()[0];

        }else{
            $inscricao = '';
        }

        if(isset($inscricao['codigo'])) {
            $generator = new BarcodeGeneratorPNG();
            $barcode = base64_encode($generator->getBarcode($inscricao['codigo'], $generator::TYPE_CODE_128));
        }else{
            $barcode = '';
        }
        
        $this->render('pages/palestras/inscricao.twig', ['menu' => 'palestras', 'palestra' => $palestra, 'inscricao' => $inscricao, 'barcode' => $barcode, 'total_inscricao' => $totalInscricoes['total_inscricoes']]);
    }


    public function inscricaoCadastro($params)
    {
        $this->setParams($params);

        if(!$this->checkCadastro($params)){
            $cadastro = new Palestras();
            $cadastro = $cadastro->inscricaoCadastro($params);
            if ($cadastro) {
                $last = new Palestras();
                $last = $last->lastInscricao()->getResult()[0];
                
                // Integração automática com Mailchimp
                $this->integrarComMailchimp($params, 'palestra');
                
                echo $last['id'];
            }
        }else{
            echo '0';
        }

    }

    public function checkCadastroCampo($params)
    {
        // $this->setParams($params);
        // $check = new Palestras();
        // $check = $check->checkCadastroCampo($this->params)->getResult();
        // if ($check) {
        //     echo '1';
        // }else{
            echo '0';
        //}
    }

    public function checkCadastro($params)
    {
        $checkCadastro = new Palestras();
        $checkCadastro = $checkCadastro->checkCadastro($params)->getResult();
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

    public function inscricaoCadastroQRcode($params)
    {
        $this->setParams($params);
        $update = new Palestras();
        $update = $update->inscricaoCadastroQRcode($params);
    }


    public function palestraInscritos($params)
    {
        $this->setParams($params);
        
        if (isset($_GET['q']) == 'share') {

            $palestra = new Palestras();
            $palestra = $palestra->getPalestra($params['id'])->getResult()[0];

            $lista = new Palestras();
            $lista = $lista->listarPalestraInscritos($params['id'], $palestra['id_empresa'])->getResult();

            $total = new Palestras();
            $total = $total->listarInscricoesTotal($params['id'])->getResult()[0];

            $grupos = new Palestras();
            $grupos = $grupos->listarInscricoesByGroup($params['id'])->getResult();

            $sorteados = new Palestras();
            $sorteados = $sorteados->listarPalestrasUserSorteados($params['id'])->getResult();

            $this->render('pages/palestras/inscritos.twig', ['menu' => 'palestras', 'palestra' => $palestra, 'listas' => $lista, 'grupos' => $grupos, 'total' => $total, 'sorteados' => $sorteados]);
       
        }else{

            if (!empty($_SESSION['sampel_user_id'])){
                
                $palestra = new Palestras();
                $palestra = $palestra->getPalestra($params['id'])->getResult()[0];
    
                $lista = new Palestras();
                $lista = $lista->listarPalestraInscritos($params['id'], $palestra['id_empresa'])->getResult();
    
                $total = new Palestras();
                $total = $total->listarInscricoesTotal($params['id'])->getResult()[0];
    
                $grupos = new Palestras();
                $grupos = $grupos->listarInscricoesByGroup($params['id'])->getResult();
    
                $sorteados = new Palestras();
                $sorteados = $sorteados->listarPalestrasUserSorteados($params['id'])->getResult();
    
                $this->render('pages/palestras/inscritos.twig', ['menu' => 'palestras', 'palestra' => $palestra, 'listas' => $lista, 'grupos' => $grupos, 'total' => $total, 'sorteados' => $sorteados]);
            }else{
                $this->render('pages/error/no-permition.twig', ['menu' => 'palestras']);
            }

        }

    }

    public function printEtiqueta($params)
    {
        $this->setParams($params);

        $configuracoes = new Palestras();
        $configuracoes = $configuracoes->getConfiguracoes()->getResult()[0];

        $inscricao = new Palestras();
        $inscricao = $inscricao->getInscricaoByCode($params['codigo'])->getResult()[0];

        $palatra = new Palestras();
        $palatra = $palatra->getPalestra($inscricao['id_palestra'])->getResult()[0];

        $this->render('pages/palestras/etiqueta.twig', ['menu' => 'palestras', 'palatra' => $palatra, 'inscricao' => $inscricao, 'config' => $configuracoes]);
    }

    public function sortear($params)
    {
        $this->setParams($params);
        $sortear = new Palestras();
        $sortear = $sortear->sortear($params);
    }

    public function sorteados($params)
    {
        $this->setParams($params);
        
        $palestra = new Palestras();
        $palestra = $palestra->getPalestra($params['id'])->getResult()[0];

        $lista = new Palestras();
        $lista = $lista->listarPalestraUserSorteados($params['id'])->getResult();

        $this->render('pages/palestras/sorteados.twig', ['menu' => 'palestras', 'palestra' => $palestra, 'listas' => $lista]);
    }

    public function importeGaleria($params)
    {
        $this->setParams($params);

        if(!empty($_SESSION['sampel_user_id'])){

            $palestra = new Palestras();
            $palestra = $palestra->getPalestra($params['id'])->getResult()[0];

            if($_SESSION['sampel_user_tipo'] == 1){
                $byuser = null;
            }else{
                $byuser = $_SESSION['sampel_user_id'];
            }

            $imagens = new Palestras();
            $imagem = $imagens->getPalestrasImages($this->params['id'], $byuser)->getResult();

            if($palestra['id'] != ''){
                $this->render('pages/palestras/importe_galeria.twig', ['menu' => 'palestras', 'palestra' => $palestra, 'imagens' => $imagem]);
            }else{
                $this->render('pages/error/no-permition.twig', ['menu' => 'palestras']);
            }

        }else{
            $this->render('pages/error/no-permition.twig', ['menu' => 'palestras']);
        }
    }

    public function galeriaPalastra($params)
    {
        $this->setParams($params);

        $palestra = new Palestras();
        $palestra = $palestra->getPalestra($params['id'])->getResult()[0];

        $imagens = new Palestras();
        $imagem = $imagens->getPalestrasImages($this->params['id'])->getResult();

        if($palestra['id'] != ''){
            $this->render('pages/palestras/galeria.twig', ['menu' => 'palestras', 'palestra' => $palestra, 'imagens' => $imagem]);
        }else{
            $this->render('pages/error/no-permition.twig', ['menu' => 'palestras']);
        }

    }

    public function galerias($params)
    {
        $this->setParams($params);

        $ultimasFotosPalestras = new Palestras();
        $ultimasFotosPalestras = $ultimasFotosPalestras->listarUltimasFotosPalestras()->getResult();

        $this->render('pages/palestras/galerias.twig', ['menu' => 'palestras', 'ultimasFotosPalestras' => $ultimasFotosPalestras]);
 
    }

    public function editarInscricao($params)
    {
        $this->setParams($params);

        $palestra = new PalestrasPainel(); 
        $palestra = $palestra->getPalestraID($params['id'])->getResult()[0];

        $editar = new PalestrasPainel(); 
        $editar = $editar->getParticipanteID($params['inscricao'])->getResult()[0];

        $this->render('components/palestras/editar_participante.twig', ['palestra' => $palestra, 'editar' => $editar]);
    }

}