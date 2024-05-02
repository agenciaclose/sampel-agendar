<?php

namespace Agencia\Close\Controllers\Site\Visitas;

use Agencia\Close\Models\User\User;
use Agencia\Close\Models\Site\Agendar;
use Agencia\Close\Models\Site\Visitas;
use Agencia\Close\Services\Login\Logon;

use Picqer\Barcode\BarcodeGeneratorPNG;
use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\VisitasPainel;

class VisitasController extends Controller
{
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

        if(isset($inscricao['codigo'])) {
            $generator = new BarcodeGeneratorPNG();
            $barcode = base64_encode($generator->getBarcode($inscricao['codigo'], $generator::TYPE_CODE_128));
        }else{
            $barcode = '';
        }

        $this->render('pages/visitas/inscricao.twig', ['menu' => 'visitas', 'visita' => $visita, 'inscricao' => $inscricao, 'config' => $configuracoes, 'barcode' => $barcode]);
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
                    echo $last['id'];
                }

            }else{

                if(!$this->checkCadastro($params)){
                    $cadastro = new Visitas();
                    $cadastro = $cadastro->inscricaoCadastro($params);
                    if ($cadastro) {
                        $last = new Visitas();
                        $last = $last->lastInscricao()->getResult()[0];
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

            $imagens = new Visitas();
            $imagem = $imagens->getVisitasImages($this->params['id'], $_SESSION['sampel_user_id'])->getResult();

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

}