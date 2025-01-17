<?php

namespace Agencia\Close\Controllers\Painel\Fornecedores;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\FornecedoresPainel;

class FornecedoresController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);
        $fornecedores = new FornecedoresPainel();
        $fornecedores = $fornecedores->lista()->getResult();
        $this->render('painel/pages/fornecedores/index.twig', ['menu' => 'fornecedores', 'fornecedores' => $fornecedores]);
    }

    public function itemAdd()
    {
        $this->render('painel/pages/fornecedores/form.twig', []);
    }

    public function itemEdit($params)
    {
        $this->setParams($params);
        $fornecedor = new FornecedoresPainel();
        $fornecedor = $fornecedor->getFornecedorID($params['id'])->getResultSingle();
        $this->render('painel/pages/fornecedores/form.twig', ['fornecedor' => $fornecedor]);
    }

    public function itemAddSave($params)
    {
        $this->setParams($params);
        $save = new FornecedoresPainel();
        $save = $save->addFornecedorSave($params)->getResultSingle();
        if($save){ echo $save['id']; }
    }

    public function itemEditave($params)
    {
        $this->setParams($params);
        $save = new FornecedoresPainel();
        $save = $save->editFornecedorSave($params);
        if($save){ echo '1'; }
    }


    public function findCNPJ(array $params)
    {
        $this->setParams($params);
        header('Access-Control-Allow-Origin: buscanarede.com.br');
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

    public function getTerms($params)
    {
        $this->setParams($params);
        $model = new FornecedoresPainel();
        $terms = $model->getTerms();
  
        $json = [];
        if(count($terms->getResult()) > 0){
            foreach ($terms->getResult() as $key => $value) {
                $json[] = ['id'=>$value['id'], 'text'=>$value['empresa_fantasia']];
            }
        } else {
            $json[] = array('id' => '0', 'text' => 'Nenhum item encontrado');
        }
        echo json_encode($json);
    }
}