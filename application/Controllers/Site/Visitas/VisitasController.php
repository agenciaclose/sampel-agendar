<?php

namespace Agencia\Close\Controllers\Site\Visitas;

use Agencia\Close\Models\User\User;
use Agencia\Close\Models\Site\Agendar;
use Agencia\Close\Models\Site\Visitas;
use Agencia\Close\Services\Login\Logon;

use Picqer\Barcode\BarcodeGeneratorPNG;
use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\VisitasPainel;
use Agencia\Close\Services\Mailchimp\MailchimpService;
use Shuchkin\SimpleXLSXGen;

class VisitasController extends Controller
{
    private MailchimpService $mailchimpService;

    public function __construct($router)
    {
        parent::__construct($router);
        $this->mailchimpService = new MailchimpService();
    }

    public function visitas($params)
    {
        $this->setParams($params);
        $visitas = new Visitas();
        $visitas = $visitas->listarVisitasUser()->getResult();
        $this->render('pages/visitas/visitas.twig', ['menu' => 'visitas', 'visitas' => $visitas]);
    }

    public function outras($params)
    {
        $this->setParams($params);

        if(!empty($_SESSION['sampel_user_id'])){

            $visitas = new Visitas();
            $visitas = $visitas->listarVisitasOutros()->getResult();

            $i = 0;
            foreach($visitas as $visita){
                $todasEquipes = new Visitas();
                $todasEquipes = $todasEquipes->listaEquipesVisita($visita['visita_id'])->getResult();
                $visitas[$i]['equipevisita'] = $todasEquipes;
                $i++;
            }

            $this->render('pages/visitas/outras.twig', ['menu' => 'home',  'visitas' => $visitas]);

        }else{

            $this->render('pages/login/login.twig', []);

        }
    }

    public function agendamentos($params)
    {
        $this->setParams($params);
        $visitas = new Visitas();
        $visitas = $visitas->listarVisitas()->getResult();
        $i = 0;
        foreach($visitas as $visita){
            $todasEquipes = new Visitas();
            $todasEquipes = $todasEquipes->listaEquipesVisita($visita['visita_id'])->getResult();
            $visitas[$i]['equipevisita'] = $todasEquipes;
            $i++;
        }

        $this->render('pages/visitas/agendamentos.twig', ['menu' => 'visitas', 'visitas' => $visitas]);
    }

    public function concluidas($params)
    {
        $this->setParams($params);
        $visitas = new Visitas();
        $visitas = $visitas->listarVisitasConcluidas()->getResult();
        $i = 0;
        
        foreach($visitas as $visita){
            $todasEquipes = new Visitas();
            $todasEquipes = $todasEquipes->listaEquipesVisita($visita['visita_id'])->getResult();
            $visitas[$i]['equipevisita'] = $todasEquipes;
            $i++;
        }

        $this->render('pages/visitas/concluidas.twig', ['menu' => 'visitas', 'visitas' => $visitas]);
    }

    public function lista($params)
    {
        $this->setParams($params);
        
        if(!empty($_SESSION['sampel_user_id'])){
            $visita = new Visitas();
            $visita = $visita->listarVisitaID($params['id'])->getResult()[0];

            $lista = new Visitas();
            $lista = $lista->listarVisitasUser($params['id'], $visita['id_empresa'])->getResult();

            $total = new Visitas();
            $total = $total->listarInscricoesTotal($params['id'])->getResult()[0];

            $grupos = new Visitas();
            $grupos = $grupos->listarInscricoesByGroup($params['id'])->getResult();

            $sorteados = new Visitas();
            $sorteados = $sorteados->listarVisitasUserSorteados($params['id'])->getResult();

            $todasEquipes = new Visitas();
            $todasEquipes = $todasEquipes->listaEquipes()->getResult();

            $equipeVisita = new Visitas();
            $equipeVisita = $equipeVisita->listaEquipesVisita($params['id'])->getResult();

            $estados = new VisitasPainel();
            $estados = $estados->getEstados()->getResult();

            $motivos = new Agendar();
            $motivos = $motivos->getMotivos()->getResult();

            $equipeSelecionada = array();
            foreach ($equipeVisita as $equipe){
                $equipeSelecionada[] = $equipe['id'];
            }

            $this->render('pages/visitas/lista.twig', ['menu' => 'visitas', 'visita' => $visita, 'listas' => $lista, 'grupos' => $grupos, 'total' => $total, 'sorteados' => $sorteados, 'todasequipes' => $todasEquipes, 'equipevisita' => $equipeVisita, 'equipeselecionada' => $equipeSelecionada, 'estados' => $estados, 'motivos' => $motivos]);
        }else{
            $this->render('pages/error/no-permition.twig', ['menu' => 'visitas']);
        }
    }

    public function exportInscritosExcel($params)
    {
        $this->setParams($params);

        if (empty($_SESSION['sampel_user_id'])) {
            http_response_code(403);
            header('Content-Type: text/plain; charset=utf-8');
            echo 'Acesso negado.';
            return;
        }

        $visitasModel = new Visitas();
        $visitaRows = $visitasModel->listarVisitaID($params['id'])->getResult();
        if (empty($visitaRows)) {
            http_response_code(404);
            header('Content-Type: text/plain; charset=utf-8');
            echo 'Visita não encontrada.';
            return;
        }

        $visita = $visitaRows[0];
        $lista = $visitasModel->listarVisitasUser($params['id'], $visita['id_empresa'])->getResult();

        $rows = [];
        $rows[] = ['Nome completo', 'CPF', 'Empresa', 'Cidade', 'Estado'];
        foreach ($lista as $inscricao) {
            $rows[] = [
                $inscricao['nome'] ?? '',
                $this->formatCpfParaExportacao($inscricao['cpf'] ?? ''),
                $inscricao['empresa'] ?? '',
                $inscricao['cidade'] ?? '',
                $inscricao['estado'] ?? '',
            ];
        }

        $filename = 'inscritos_visita_' . (int) $params['id'] . '_' . date('Y-m-d') . '.xlsx';
        SimpleXLSXGen::fromArray($rows)->downloadAs($filename);
        exit;
    }

    private function formatCpfParaExportacao($cpf): string
    {
        $digits = preg_replace('/[^0-9]/', '', (string) $cpf);
        if (strlen($digits) !== 11) {
            return trim((string) $cpf);
        }

        return substr($digits, 0, 3) . '.' . substr($digits, 3, 3) . '.' . substr($digits, 6, 3) . '-' . substr($digits, 9, 2);
    }

    public function inscritos($params)
    {
        $this->setParams($params);
        
        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['id'])->getResult()[0];

        $lista = new Visitas();
        $lista = $lista->listarVisitasUser($params['id'], $visita['id_empresa'])->getResult();

        $total = new Visitas();
        $total = $total->listarInscricoesTotal($params['id'])->getResult()[0];

        $grupos = new Visitas();
        $grupos = $grupos->listarInscricoesByGroup($params['id'])->getResult();

        $sorteados = new Visitas();
        $sorteados = $sorteados->listarVisitasUserSorteados($params['id'])->getResult();

        $todasEquipes = new Visitas();
        $todasEquipes = $todasEquipes->listaEquipes()->getResult();

        $equipeVisita = new Visitas();
        $equipeVisita = $equipeVisita->listaEquipesVisita($params['id'])->getResult();

        $equipeSelecionada = array();
        foreach ($equipeVisita as $equipe){
            $equipeSelecionada[] = $equipe['id'];
        }

        $this->render('pages/visitas/inscritos.twig', ['menu' => 'visitas', 'visita' => $visita, 'listas' => $lista, 'grupos' => $grupos, 'total' => $total, 'sorteados' => $sorteados, 'todasequipes' => $todasEquipes, 'equipevisita' => $equipeVisita, 'equipeselecionada' => $equipeSelecionada]);
    
    }

    public function inscricao($params)
    {
        $this->setParams($params);
        $visita = new Visitas();

        $visita = $visita->listarVisitaID($params['id'])->getResult()[0];
        
        if(isset($_GET['action'])){
            
            //SE CHEGAR A 60% APROVA A VISITA
            if($visita['status_visita'] == 'Pendente'){
                $porcentagem = $visita['qtd_visitas'] * 0.6;
                if ($visita['inscricoes'] >= $porcentagem) {
                    $update_status = new Visitas();
                    $update_status = $update_status->updateStatusVisita($params['id']);
                }
            }

            $inscricao = new Visitas();
            $inscricao = $inscricao->getInscricao($params['id'], $params['inscricao'])->getResult()[0];

        }else{
            $inscricao = '';
        }

        $configuracoes = new Visitas();
        $configuracoes = $configuracoes->getConfiguracoes()->getResult()[0];

        if(isset($_GET['link_inscricao'])){
            $link_codigo = new Visitas();
            $link_codigo = $link_codigo->getLinkInscricao($params['id'], $_GET['link_inscricao']);
            if($link_codigo->getResult()){
                $link_codigo = $link_codigo->getResult()[0];
            }else{
                $link_codigo = [];
            }
        }else{
            $link_codigo = [];
        }

        if(isset($inscricao['codigo'])) {
            $generator = new BarcodeGeneratorPNG();
            $barcode = base64_encode($generator->getBarcode($inscricao['codigo'], $generator::TYPE_CODE_128));
        }else{
            $barcode = '';
        }

        $this->render('pages/visitas/inscricao.twig', ['menu' => 'visitas', 'visita' => $visita, 'inscricao' => $inscricao, 'config' => $configuracoes, 'barcode' => $barcode, 'link_codigo' => $link_codigo]);
    }

    public function checkCadastroCampo($params)
    {
        $this->setParams($params);
        $check = new Visitas();
        $check = $check->checkCadastroCampo($this->params)->getResult();
        
        if ($check) {
            echo $check[0]['data_visita'];
        }else{
            echo '0';
        }
    }

    public function inscricaoCadastro($params)
    {
        $this->setParams($params);

        if( $params['tipo_visita'] == 'visita'){
            
            if(!empty($_SESSION['sampel_user_id'])){

                $cadastro = new Visitas();
                $cadastro = $cadastro->inscricaoCadastro($params);
                if ($cadastro) {
                    $last = new Visitas();
                    $last = $last->lastInscricao()->getResult()[0];
                    
                    // Integração automática com Mailchimp
                    $this->integrarComMailchimp($params, 'visita');
                    
                    echo $last['id'];
                }

            }else{

                // Verifica se há link_inscricao sem restrição
                $temLinkSemRestricao = false;
                if(!empty($params['link_inscricao'])){
                    $visita = new Visitas();
                    $link_codigo = $visita->getLinkInscricao($params['id_visita'], $params['link_inscricao']);
                    if($link_codigo->getResult()){
                        $link_data = $link_codigo->getResult()[0];
                        if(isset($link_data['restricao']) && $link_data['restricao'] == 'N'){
                            $temLinkSemRestricao = true;
                        }
                    }
                }

                // Se não tem link sem restrição, verifica se já existe cadastro
                if($temLinkSemRestricao || !$this->checkCadastro($params)){
                    $cadastro = new Visitas();
                    $cadastro = $cadastro->inscricaoCadastro($params);
                    if ($cadastro) {
                        $last = new Visitas();
                        $last = $last->lastInscricao()->getResult()[0];
                        
                        // Integração automática com Mailchimp
                        $this->integrarComMailchimp($params, 'visita');
                        
                        echo $last['id'];
                    }
                }else{
                    echo '0';
                }

            }
            
        }else{
            $cadastro = new Visitas();
            $cadastro = $cadastro->inscricaoCadastro($params);
            if ($cadastro) {
                $last = new Visitas();
                $last = $last->lastInscricao()->getResult()[0];
                
                // Integração automática com Mailchimp
                $this->integrarComMailchimp($params, 'evento');
                
                echo $last['id'];
            }
        }

    }

    public function inscricaoEditar($params)
    {
        $this->setParams($params);

        $inscricao = new Visitas();
        $inscricao = $inscricao->getInscricao($params['visita_id'], $params['id'])->getResult()[0];

        $this->render('pages/visitas/inscricao_editar.twig', ['inscricao' => $inscricao]);
    }

    public function inscricaoEditarSave($params)
    {
        $this->setParams($params);
        $editar = new Visitas();
        $editar = $editar->inscricaEeditar($params);
        if($editar){
            echo 'success';
        }else{
            echo 'error';
        }
    }

    public function inscricaoCadastroQRcode($params)
    {
        $this->setParams($params);
        $update = new Visitas();
        $update = $update->inscricaoCadastroQRcode($params);
    }

    public function checkCadastro($params)
    {
        $checkCadastro = new Visitas();
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

    public function printEtiqueta($params)
    {
        $this->setParams($params);

        $configuracoes = new Visitas();
        $configuracoes = $configuracoes->getConfiguracoes()->getResult()[0];

        $inscricao = new Visitas();
        $inscricao = $inscricao->getInscricaoByCode($params['codigo'])->getResult()[0];

        $visita = new Visitas();
        $visita = $visita->listarVisitaID($inscricao['id_visita'])->getResult()[0];

        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode($generator->getBarcode($inscricao['codigo'], $generator::TYPE_CODE_128));

        $this->render('pages/visitas/etiqueta.twig', ['menu' => 'visitas', 'visita' => $visita, 'inscricao' => $inscricao, 'config' => $configuracoes, 'barcode' => $barcode]);
    }

    public function printEtiqueatAll($params)
    {
        $this->setParams($params);

        $configuracoes = new Visitas();
        $configuracoes = $configuracoes->getConfiguracoes()->getResult()[0];

        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['id'])->getResult()[0];

        $inscricoes = new Visitas();
        $inscricoes = $inscricoes->listarInscricoes($params['id'])->getResult();

        $this->render('pages/visitas/etiqueta_all.twig', ['menu' => 'visitas', 'visita' => $visita, 'inscricoes' => $inscricoes, 'config' => $configuracoes]);
    }

    public function sortear($params)
    {
        $this->setParams($params);
        $sortear = new Visitas();
        $sortear = $sortear->sortear($params);
    }

    public function inscricaoExcluir($params)
    {
        $this->setParams($params);

        if (empty($_SESSION['sampel_user_id'])) {
            echo 'error';
            return;
        }

        $idInscricao = (int)($params['id'] ?? 0);
        $idVisita = (int)($params['visita_id'] ?? 0);
        if ($idInscricao <= 0 || $idVisita <= 0) {
            echo 'error';
            return;
        }

        $visitasModel = new Visitas();
        $visita = $visitasModel->listarVisitaID($idVisita)->getResult();
        if (!$visita) {
            echo 'error';
            return;
        }

        $visita = $visita[0];

        if (
            $_SESSION['sampel_user_id'] != $visita['id_empresa'] &&
            $_SESSION['sampel_user_id'] != 1 &&
            $_SESSION['sampel_user_id'] != 4
        ) {
            echo 'forbidden';
            return;
        }

        $delete = $visitasModel->inscricaoExcluir($idInscricao);
        echo $delete ? 'success' : 'error';
    }

    public function sorteados($params)
    {
        $this->setParams($params);
        
        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['id'])->getResult()[0];

        $lista = new Visitas();
        $lista = $lista->listarVisitasUserSorteados($params['id'])->getResult();

        $this->render('pages/visitas/sorteados.twig', ['menu' => 'visitas', 'visita' => $visita, 'listas' => $lista]);
    }

    public function listaEquipesSave($params)
    {
        $this->setParams($params);
        $save = new Visitas();
        $save = $save->listaEquipesSave($params);
        if($save){
            echo '0';
        }else{
            echo '1';
        }
    }

    public function removeEquipe($params)
    {
        $this->setParams($params);
        $remove = new Visitas();
        $remove = $remove->removeEquipe($params);
        if($remove){
            echo '0';
        }else{
            echo '1';
        }
    }

    public function CPFAutoComplete()
    {
        $result = new Visitas();
        $result = $result->CPFAutoComplete($_GET['cpf'])->getResult();
        if($result){
            $json = json_encode($result[0]);
            echo $json;
        }
    }

    public function relatorios($params)
    {
        $this->setParams($params);

        $perguntas = new Visitas();
        $perguntas = $perguntas->getFeedbacksPerguntas()->getResult();

        $i = 0;
        foreach ($perguntas as $pergunta) {
            $feedbacks = new Visitas();
            $perguntas[$i]['estatisticas'] = $feedbacks->getFeedbacksList($pergunta['pergunta'])->getResult();
            $i++;
        }

        $this->render('pages/visitas/relatorios.twig', ['menu' => 'visitas', 'perguntas' => $perguntas]);
    }

    public function visitaGetQRcode($params){
        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['id'])->getResult()[0];
        $this->render('pages/visitas/qrcode.twig', ['menu' => 'visita', 'visita' => $visita]);
    }

    public function visitaQRcodeFeedbackSave($params){
        $save = new Visitas();
        $save = $save->visitaQRcodeFeedbackSave($params);
    }

    public function visitaQRcodeSave($params){
        $save = new Visitas();
        $save = $save->visitaQRcodeSave($params);
    }

    public function importeGaleria($params)
    {
        $this->setParams($params);

        if(!empty($_SESSION['sampel_user_id'])){

            $visita = new Visitas();
            $visita = $visita->listarVisitaID($params['id'])->getResult()[0];

            //VERIFICA SE É ADMIN EDITANDO
            if($_SESSION['sampel_user_tipo'] == 1){
                $byuser = null;
            }else{
                $byuser = $_SESSION['sampel_user_id'];
            }

            $imagens = new Visitas();
            $imagem = $imagens->getVisitasImages($this->params['id'], $byuser)->getResult();

            if($visita['id'] != ''){
                $this->render('pages/visitas/importe_galeria.twig', ['menu' => 'visitas', 'visita' => $visita, 'imagens' => $imagem]);
            }else{
                $this->render('pages/error/no-permition.twig', ['menu' => 'visitas']);
            }

        }else{
            $this->render('pages/error/no-permition.twig', ['menu' => 'visitas']);
        }
    }

    public function galeriaVisita($params)
    {
        $this->setParams($params);

        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['id'])->getResult()[0];

        $imagens = new Visitas();
        $imagem = $imagens->getVisitasImages($this->params['id'])->getResult();

        if($visita['id'] != ''){
            $this->render('pages/visitas/galeria.twig', ['menu' => 'visitas', 'visita' => $visita, 'imagens' => $imagem]);
        }else{
            $this->render('pages/error/no-permition.twig', ['menu' => 'visitas']);
        }

    }

    public function galerias($params)
    {
        $this->setParams($params);

        $galerias = new Visitas();
        $galerias = $galerias->listarGalerias()->getResult();

        $this->render('pages/visitas/galerias.twig', ['menu' => 'visitas', 'galerias' => $galerias]);
 
    }

    public function inscricaoAutocomplete($params)
    {
        $this->setParams($params);
        $autocomplete = new Visitas();
        $autocomplete = $autocomplete->autocomplete($params['cpf'])->getResult();
        if($autocomplete){
            $json = json_encode($autocomplete[0]);
            echo $json;
        }
    }

    public function linkIncricao($params)
    {
        $this->setParams($params);
        $visita = new Visitas();
        $visita = $visita->listarVisitaID($_GET['visita']);
        if($visita->getResult()){
            $visita = $visita->getResult()[0];
        }
        $this->render('components/inscricao/link_inscricao.twig', ['visita' => $visita]);
    }

    /**
     * Página para o usuário gerenciar (listar e editar) seus links de inscrição da visita.
     */
    public function linksGerenciar($params)
    {
        $this->setParams($params);
        if (empty($_SESSION['sampel_user_id'])) {
            $this->render('pages/error/no-permition.twig', ['menu' => 'visitas']);
            return;
        }
        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['id'])->getResult();
        if (empty($visita)) {
            $this->render('pages/error/no-permition.twig', ['menu' => 'visitas']);
            return;
        }
        $visita = $visita[0];
        $links = new Visitas();
        $links = $links->listarLinksByUserAndEvento($visita['id'], $_SESSION['sampel_user_id'])->getResult();
        $this->render('pages/visitas/links_gerenciar.twig', ['menu' => 'visitas', 'visita' => $visita, 'links' => $links ?: []]);
    }

    /**
     * Salva edição de um link (qtd_usos, restricao). Apenas o dono do link pode editar.
     */
    public function linkInscricaoEditarSave($params)
    {
        $this->setParams($params);
        if (empty($_SESSION['sampel_user_id'])) {
            echo 'error';
            return;
        }
        $codigo = $params['codigo'] ?? '';
        $qtd_usos = (int) ($params['qtd_usos'] ?? 0);
        $restricao = isset($params['restricao']) && $params['restricao'] === 'N' ? 'N' : 'S';
        if ($codigo === '') {
            echo 'error';
            return;
        }
        $visita = new Visitas();
        $ok = $visita->updateLinkInscricaoEditar($codigo, $_SESSION['sampel_user_id'], $qtd_usos, $restricao);
        echo $ok ? 'success' : 'error';
    }

    /**
     * Deleta um link de inscrição do usuário logado.
     */
    public function linkInscricaoDelete($params)
    {
        $this->setParams($params);
        if (empty($_SESSION['sampel_user_id'])) {
            echo 'error';
            return;
        }

        $codigo = $params['codigo'] ?? '';
        if ($codigo === '') {
            echo 'error';
            return;
        }

        $visita = new Visitas();
        $ok = $visita->deleteLinkInscricao($codigo, $_SESSION['sampel_user_id']);
        echo $ok ? 'success' : 'error';
    }

    public function linkIncricaoSave($params)
    {
        $this->setParams($params);
        $visita = new Visitas();
        $codigo = $this->genCode();

        $params['id_user'] = $_SESSION['sampel_user_id'];
        $params['codigo'] = $codigo;

        $visita->saveLinkInscricao($params);
        echo $codigo;
    }

    function genCode() { 

        $chars = "ABCDEFGHIJKMNOPQRSTUVWXYZ023456789";

        $i = 0;
        $pass = '';

        while ($i <= 5) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }

        return $pass;
    }

}