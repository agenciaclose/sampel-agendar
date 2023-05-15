<?php

namespace Agencia\Close\Helpers\User;

use Agencia\Close\Models\User\User;

class EmailUser
{
    public static function verifyIfEmailExist($email): bool
    {

        $userEmail = new User();
        $result = $userEmail->emailExist($email);
        if($result->getResult()){
            return true;
        }else{
            return false;
        }

    }
}