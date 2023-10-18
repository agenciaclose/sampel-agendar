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
        $this->render('pages/visitas/agendamentos.twig', ['menu' => 'visitas', 'visitas' => $visitas]);
    }

    public function lista($params)
    {
        $this->setParams($params);
        
        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['id'])->getResult()[0];

        $lista = new Visitas();
        $lista = $lista->listarVisitasUser()->getResult();

        $this->render('pages/visitas/lista.twig', ['menu' => 'visitas', 'visita' => $visita, 'listas' => $lista]);
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

    public function inscricaoCadastro($params)
    {
        $this->setParams($params);

        if( !$this->checkCadastro($params['cpf'], $params['id_visita']) ){

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

    public function checkCadastro($cpf, $visita_id)
    {
        $checkCadastro = new Visitas();
        $checkCadastro = $checkCadastro->checkCadastro($cpf, $visita_id)->getResult();
        return $checkCadastro;
    }

    public function printEtiqueta($params)
    {
        $this->setParams($params);

        $inscricao = new Visitas();
        $inscricao = $inscricao->getInscricaoByCode($params['codigo'])->getResult()[0];

        $visita = new Visitas();
        $visita = $visita->listarVisitaID($inscricao['id_visita'])->getResult()[0];

        $this->render('pages/visitas/etiqueta.twig', ['menu' => 'visitas', 'visita' => $visita, 'inscricao' => $inscricao]);
    }

}