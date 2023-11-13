<?php

namespace Agencia\Close\Controllers\Site\Visitas;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\Visitas;
use Agencia\Close\Models\User\User;
use Agencia\Close\Services\Login\Logon;

class VisitasController extends Controller
{
    public function visitas($params)
    {
        $this->setParams($params);
        $visitas = new Visitas();
        $visitas = $visitas->listarVisitasUser()->getResult();
        $this->render('pages/visitas/visitas.twig', ['menu' => 'visitas', 'visitas' => $visitas]);
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

            $equipeSelecionada = array();
            foreach ($equipeVisita as $equipe){
                $equipeSelecionada[] = $equipe['id'];
            }

            $this->render('pages/visitas/lista.twig', ['menu' => 'visitas', 'visita' => $visita, 'listas' => $lista, 'grupos' => $grupos, 'total' => $total, 'sorteados' => $sorteados, 'todasequipes' => $todasEquipes, 'equipevisita' => $equipeVisita, 'equipeselecionada' => $equipeSelecionada]);
        }else{
            $this->render('pages/error/no-permition.twig', ['menu' => 'visitas']);
        }
    }

    public function inscricao($params)
    {
        $this->setParams($params);
        $visita = new Visitas();

        $visita = $visita->listarVisitaID($params['id'])->getResult()[0];

        if(isset($_GET['action'])){

            $inscricao = new Visitas();
            $inscricao = $inscricao->getInscricao($params['id'], $params['inscricao'])->getResult()[0];

        }else{
            $inscricao = '';
        }

        $configuracoes = new Visitas();
        $configuracoes = $configuracoes->getConfiguracoes()->getResult()[0];

        $this->render('pages/visitas/inscricao.twig', ['menu' => 'visitas', 'visita' => $visita, 'inscricao' => $inscricao, 'config' => $configuracoes]);
    }

    public function checkCadastroCampo($params)
    {
        $this->setParams($params);
        $check = new Visitas();
        $check = $check->checkCadastroCampo($this->params)->getResult();
        if ($check) {
            echo '1';
        }else{
            echo '0';
        }
    }

    public function inscricaoCadastro($params)
    {
        $this->setParams($params);
        if( !$this->checkCadastro($params) ){
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

        $this->render('pages/visitas/etiqueta.twig', ['menu' => 'visitas', 'visita' => $visita, 'inscricao' => $inscricao, 'config' => $configuracoes]);
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

}