<?php

namespace Agencia\Close\Controllers\Site\Visitas;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\Visitas;
use Agencia\Close\Models\User\User;
use Agencia\Close\Services\Login\Logon;

class VisitasController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);
        $visitas = new Visitas();
        $visitas = $visitas->listarVisitas()->getResult();
        $this->render('pages/visitas/visitas.twig', ['menu' => 'visitas', 'visitas' => $visitas]);
    }

    public function lista($params)
    {
        $this->setParams($params);
        
        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['id'])->getResult()[0];

        $this->render('pages/visitas/lista.twig', ['menu' => 'visitas', 'visita' => $visita]);
    }

    public function inscricao($params)
    {
        $this->setParams($params);
        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['id'])->getResult()[0];
        $this->render('pages/visitas/inscricao.twig', ['menu' => 'visitas', 'visita' => $visita]);
    }

    public function inscricaoCadastro($params)
    {
        $this->setParams($params);

        if( !$this->checkCadastro($params['cpf'], $params['email'], $params['visita_id']) ){

            if(!empty($_SESSION['sampel_user_id'])){

                $params['sampel_user_id'] = $_SESSION['sampel_user_id'];

            }else{

                $user = new User();
                $user->saveClient($params);
                
                $logon = new Logon();
                $params['sampel_user_id'] = $logon->loginByEmailReturn($params['email'], $params['senha'])->getResult()[0]['id'];
                
            }

            $cadastro = new Visitas();
            $cadastro = $cadastro->inscricaoCadastro($params);
            if ($cadastro) {
                echo "1";
            }

        }else{

            echo '2';

        }

    }

    public function checkCadastro($cpf, $email, $visita_id)
    {
        $checkCadastro = new Visitas();
        $checkCadastro = $checkCadastro->checkCadastro($cpf, $email, $visita_id)->getResult();
        return $checkCadastro;
    }
}