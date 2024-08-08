<?php

namespace Agencia\Close\Controllers\Site\Equipes;

use Agencia\Close\Models\Site\Equipes;
use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\User\EmailUser;
use Agencia\Close\Models\Painel\CargosPainel;

class EquipesController extends Controller
{

    public function equipes($params)
    {
        $this->setParams($params);

        $equipes = new Equipes();
        $equipes = $equipes->getEquipesList()->getResult();

        $this->render('pages/equipes/lista.twig', ['menu' => 'equipes', 'equipes' => $equipes]);
    }

    public function cadastro()
    {
        $cargos = new CargosPainel();
        $cargos = $cargos->getCargosList()->getResult();
        
        $this->render('pages/equipes/cadastro.twig', ['menu' => 'equipes', 'cargos' => $cargos]);
    }

    public function cadastroSave(array $params)
    {
        $this->setParams($params);

        if(EmailUser::verifyIfEmailExist($this->params['email'])){
            echo '2';
            return;
        }

        $this->params['tipo'] = '4';
        $this->params['status'] = '1';

        $user = new Equipes();
        $idUser = $user->cadastroSave($this->params);
        if ($idUser) {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function cnpj(array $params)
    {
        $this->setParams($params);
        header('Access-Control-Allow-Origin: 56minutos.com.br');
        //Garantir que seja lido sem problemas
        header("Content-Type: text/plain");

        //Capturar CNPJ

        $cnpj = $_GET["cnpj"];
        $cnpj = preg_replace('/\D/', '', $cnpj); //Retira os caracteres que não são dígitos

        ///Criando Comunicação cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.receitaws.com.br/v1/cnpj/".$cnpj);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Comente esta linha quando o seu site estiver rodando em https
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $retorno = curl_exec($ch);
        curl_close($ch);

        $retorno = json_decode($retorno); //Ajuda a ser lido mais rapidamente
        echo json_encode($retorno, JSON_PRETTY_PRINT);
    }

    public function editar($params)
    {
        $this->setParams($params);

        $editar = new Equipes();
        $editar = $editar->getEquipesEditar($params['id'])->getResult()[0];

        $cargos_user = new CargosPainel();
        $cargos_user = $cargos_user->getCargosUser($params['id'])->getResult();

        $cargos = new CargosPainel();
        $cargos = $cargos->getCargosList()->getResult();

        $this->render('pages/equipes/cadastro.twig', ['menu' => 'equipes', 'editar' => $editar, 'cargos' => $cargos, 'cargos_user' => $cargos_user]);
    }

    public function editarSave(array $params)
    {
        $this->setParams($params);

        if($params['email'] != $params['email_old']){
            if(EmailUser::verifyIfEmailExist($params['email'])){
                echo '2';
                return;
            }
        }

        $params['tipo'] = '4';
        $params['status'] = '1';

        $user = new Equipes();
        $idUser = $user->editarSave($params);
        if ($idUser) {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function excluir(array $params)
    {
        $this->setParams($params);

        $editar = new Equipes();
        $editar = $editar->getEquipeExcluir($params['id'])->getResult();
        echo '1';
    }

}