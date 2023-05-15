<?php

namespace Agencia\Close\Controllers\Site\Login;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\User\EmailUser;
use Agencia\Close\Models\Log\RegisterLog;
use Agencia\Close\Models\User\User;
use Agencia\Close\Services\Login\Logon;

class RegisterController extends Controller
{
    
    public function index(array $params)
    {
        $this->setParams($params);        
        $this->render('pages/login/cadastro.twig', []);
    }

    public function createClient(array $params)
    {
        $this->setParams($params);

        if(EmailUser::verifyIfEmailExist($this->params['email'])){
            echo '2';
            return;
        }

        if($this->params['tipo_cadastro'] == 'tipo_cnpj'){
            $this->params['tipo'] = '2';
        }else{
            $this->params['tipo'] = '3';
        }

        $this->params['status'] = '1';

        $user = new User();
        $idUser = $user->saveClient($this->params);
        if ($idUser) {

            $logon = new Logon();
            $logon->loginByEmail($this->params['email'], $this->params['senha']);
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


}