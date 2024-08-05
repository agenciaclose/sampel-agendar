<?php

namespace Agencia\Close\Controllers\Site\MinhasInscricoes;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\MinhasInscricoes;

class MinhasInscricoesController extends Controller
{
    public function check()
    {
        $this->render('pages/minhas-inscricoes/check.twig', ['active' => 'minhas-inscricoes']);
    }

    public function checkInscricoes($params)
    {
        $this->setParams($params);

        $checklist = new MinhasInscricoes();
        $checklist = $checklist->getLista($params['cpf']);

        //if($checklist->getResult()){

            $expire = time() + 3600 * 24 * 365;
            setcookie("sampel_user_cpf", $params['cpf'], $expire);
            echo '1';

        // }else{
        //     unset($_COOKIE['sampel_user_cpf']);
        //     setcookie('sampel_user_cpf', '', -1, '/'); 
        //     echo '0';
        // }
    }

    public function lista($params)
    {
        $this->setParams($params);
        if(isset($_COOKIE['sampel_user_cpf'])){

            $getlist = new MinhasInscricoes();
            $lista = $getlist->getLista($_COOKIE['sampel_user_cpf'])->getResult();

            $userDados = $getlist->getLista($_COOKIE['sampel_user_cpf']);
            if($userDados->getResult()){
                $user = $userDados->getResult()[0];
            }else{
                $user = [];
            }

            $this->render('pages/minhas-inscricoes/minhasInscricoes.twig', ['active' => 'minhas-inscricoes', 'lista' => $lista, 'user' => $user]);

        }else{
            unset($_COOKIE['sampel_user_cpf']);
            setcookie('sampel_user_cpf', '', -1, '/'); 
            $this->router->redirect("/minhas-inscricoes?cpf=error");
        }

    }

}