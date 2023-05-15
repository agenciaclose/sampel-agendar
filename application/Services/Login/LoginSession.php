<?php

namespace Agencia\Close\Services\Login;

class LoginSession
{
    public function loginUser(array $login)
    {
        $_SESSION = [
            'sampel_user_id' => $login['id'],
            'sampel_user_slug' => $login['slug'],
            'sampel_user_nome' => $login['nome'],
            'sampel_user_email' => $login['email']
        ];
    }

    public function userIsLogged(): bool
    {
        if (isset($_SESSION['sampel_user_id'])){
            return true;
        }
        return false;
    }
}