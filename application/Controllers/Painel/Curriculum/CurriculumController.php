<?php

namespace Agencia\Close\Controllers\Painel\Curriculum;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\Upload;
use Agencia\Close\Models\Painel\CurriculumPainel;
use Agencia\Close\Services\Login\Logon;

class CurriculumController extends Controller
{

    public function index($params)
    {
        $this->setParams($params);

        $user = new CurriculumPainel();
        $user = $user->getUser($_SESSION['sampel_user_id'])->getResult()[0]; 

        $servicos = new CurriculumPainel();
        $servicos_lista = $servicos->getServicos($_SESSION['sampel_user_id'])->getResult();

	    $produtos = new CurriculumPainel();
	    $produtos = $produtos->getProdutos($_SESSION['sampel_user_id'])->getResult();
        
        $this->render('painel/pages/curriculum/curriculum.twig', ['menu' => 'curriculum', 'user' => $user, 'servicos' => $servicos_lista, 'produtos' => $produtos]);
    }

    public function accountUpdate($params)
    {
        $this->setParams($params);

        //var_dump($_FILES);

        if($_FILES['imagem']['tmp_name']) {
            $upload = new Upload;
            $upload->Image($_FILES['imagem'], microtime(), null, 'usuarios/'.$_SESSION['sampel_user_slug']);
        }

        if(isset($upload) && $upload->getResult()) {
            $this->params['imagem'] = $upload->getResult();
        }

        $accountUpdate = new CurriculumPainel();
        $accountUpdate = $accountUpdate->accountUpdate($this->params);

        $logon = new Logon();
        $logon->loginByOnlyEmail($this->params['email']);

    }

    public function accountSecurity($params)
    {
        $this->setParams($params);

        $accountSecurity = new CurriculumPainel();
        $accountSecurity = $accountSecurity->accountSecurity($this->params);

    }

    public function accountTerms($params)
    {
        $this->setParams($params);

        $accountTerms = new CurriculumPainel();
        $accountTerms = $accountTerms->accountTerms($this->params);

    }

}