<?php

namespace Agencia\Close\Controllers\Painel\LoginPainel;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\User\EmailUser;
use Agencia\Close\Helpers\User\Identification;
use Agencia\Close\Models\Log\RegisterLog;
use Agencia\Close\Models\User\User;
use Agencia\Close\Services\Login\Logon;

class LoginPainelController extends Controller
{
    public function index(array $params)
    {
        $this->setParams($params);
        $this->render('painel/pages/login/login.twig', []);
    }

    public function recover(array $params)
    {
        $this->setParams($params);
        $this->render('painel/pages/login/recover.twig', []);
    }

    public function sign(array $params)
    {
        $this->setParams($params);
        $logon = new Logon();
        if ($logon->loginByEmail($this->params['email'], $this->params['password'])) {
            echo '1';
        } else {
            echo '0';
        } 
    }

    public function logout(array $params)
    {
        $this->setParams($params);

        session_destroy();
        setcookie("CookieLoginEmail", "", time() - 3600);
        setcookie("CookieLoginHash", "", time() - 3600);

        $this->router->redirect("painel/login");
    }

    private function createUser(string $name, string $email, array $arrayIdentification): void
    {
        $identification = new Identification();
        $identification->setIdentification($email);
        $identification->setType('email');

        if (!EmailUser::verifyIfEmailExist($identification)) {
            $user = new User();
            $createdId = $user->saveUserByOauth($name, $email, $arrayIdentification);
        }
    }
}