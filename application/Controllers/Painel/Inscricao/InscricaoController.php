<?php

namespace Agencia\Close\Controllers\Painel\Inscricao;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\InscricaoPainel;

class InscricaoController extends Controller
{
    public function inscricao($params)
    {
        $this->setParams($params);
        $visita = new InscricaoPainel();
        $visita = $visita->listarVisitaID($params['id'])->getResult()[0];

        $inscricao = '';
        
        $this->render('painel/pages/visitas/inscricao.twig', ['menu' => 'visitas', 'visita' => $visita, 'inscricao' => $inscricao]);
    }

    public function inscricaoCadastro($params)
    {
        $this->setParams($params);

        $CheckUser = new InscricaoPainel();
        $CheckUser = $CheckUser->checkClient($params['email'])->getResult();
        
        if($CheckUser){
            $params['sampel_user_id'] = $CheckUser[0]['id'];
        }else{
            $cadastroUser = new InscricaoPainel();
            $cadastroUser = $cadastroUser->saveClient($params)->getResult();
            $params['sampel_user_id'] = $cadastroUser[0]['id'];
        }

        if( !$this->checkCadastro($params['email'], $params['id_visita']) ){
           
            $cadastro = new InscricaoPainel();
            $cadastro = $cadastro->inscricaoCadastro($params);

            if ($cadastro) {
                echo $params['sampel_user_id'];
            }

        }else{
            echo '0';
        }

    }

    public function inscricaoCadastroQRcode($params)
    {
        $this->setParams($params);
        $update = new InscricaoPainel();
        $update = $update->inscricaoCadastroQRcode($params);
    }

    public function checkCadastro($email, $visita_id)
    {
        $checkCadastro = new InscricaoPainel();
        $checkCadastro = $checkCadastro->checkCadastro($email, $visita_id)->getResult();
        return $checkCadastro;
    }

}