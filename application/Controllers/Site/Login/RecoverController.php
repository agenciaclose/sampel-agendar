<?php

namespace Agencia\Close\Controllers\Site\Login;

use Agencia\Close\Adapters\EmailAdapter;
use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\Link\LinkRecover;
use Agencia\Close\Helpers\Result;
use Agencia\Close\Helpers\User\EmailUser;
use Agencia\Close\Helpers\User\Identification;
use Agencia\Close\Models\User\User;
use Agencia\Close\Services\Login\Logon;

class RecoverController extends Controller
{
    private string $email;
    private Result $result;

    public function __construct($router)
    {
        parent::__construct($router);
        $this->result = new Result();
    }

    public function senha(array $params)
    {
        $this->setParams($params);
        $usuario = new User();
        $usuario = $usuario->getUserPrivateCode($params)->getResult();
        if($usuario){
            $this->render('pages/login/senha.twig', ['usuario' => $usuario[0]]);
        }else{
            $this->render('pages/error/no-permition.twig', ['menu' => 'palestras']);
        }
    }

    public function senhaSave(array $params)
    {
        $this->setParams($params);
        $salvar = new User();
        $salvar = $salvar->salvarSenha($params)->getResult();
    }

    public function index(array $params)
    {
        $this->setParams($params);
        $this->render('pages/login/recover.twig', []);
    }

    public function privateCodeSave(array $params)
    {
        $this->setParams($params);
        $update = new User();
        $update = $update->saveUserPrivateCode($params);
        $this->sendEmailRecover($params['email']);
    }

    //ENVIAR EMAIL DE NOVO EVENTO PARA EQUIPE
    public function sendEmailRecover($email)
    {
        $usuario = new User();
        $usuario = $usuario->getUserByEmail($email)->getResult()[0];

        $data = ['usuario' => $usuario];
        $email = new EmailAdapter();
        $email->setSubject('Sampel - Eventos: Recuperar Senha');
        $email->setBody('components/email/emailRecover.twig', $data);
        $email->addAddress($usuario['email']);
        //$email->addAddress('rl.cold.dev@gmail.com');
        $email->send('Email enviado com sucesso');
        $email->getResult();
    }

}