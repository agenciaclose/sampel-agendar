<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Models\Model;

class Visitas extends Model
{

    public function listarVisitas(): read
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, u.*, v.id AS visita_id, (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id) AS total_inscricao
						FROM visitas AS v
						INNER JOIN usuarios AS u ON u.id = v.id_empresa
						WHERE v.id_empresa = :user_id ORDER BY v.`data` DESC", "user_id={$_SESSION['sampel_user_id']}");
        return $read;
    }

    public function listarVisitasUser(): read
    {
        $read = new Read();
        if(!empty($_GET['setor'])){
            $setor = "AND vi.setor = '".$_GET['setor']."'";
        }else{
            $setor = "";
        }

        if(!empty($_GET['presenca'])){
            $presenca = "AND vi.presenca = '".$_GET['presenca']."'";
        }else{
            $presenca = "";
        }

        $read->FullRead("SELECT vi.*, v.id AS visita_id, v.data_visita, v.horario_visita FROM visitas_inscricoes AS vi
                        INNER JOIN visitas AS v ON v.id = vi.id_visita
                        INNER JOIN usuarios AS u ON u.id = v.id_empresa
                        WHERE v.id_empresa = :user_id $setor $presenca ORDER BY vi.`data` DESC", "user_id={$_SESSION['sampel_user_id']}");
        return $read;
    }

    public function listarVisitaID($id_visita): read
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, u.*, v.id AS visita_id
                        FROM visitas AS v
                        INNER JOIN usuarios AS u ON u.id = v.id_empresa
                        WHERE v.id = :id_visita ORDER BY v.`data` DESC", "id_visita={$id_visita}");
        return $read;
    }

    

    public function listarInscricoesTotal($id_visita): read
    {
        $read = new Read();
        $read->FullRead("SELECT count(id) as total FROM visitas_inscricoes WHERE id_visita = :id_visita ORDER BY `nome` ASC", "id_visita={$id_visita}");
        return $read;
    }

    public function listarInscricoesByGroup($id_visita): read
    {
        $read = new Read();
        $read->FullRead("SELECT *, COUNT(id) AS total FROM visitas_inscricoes WHERE id_visita = :id_visita GROUP BY setor", "id_visita={$id_visita}");
        return $read;
    }

    public function getInscricao($id_visita, $inscricao): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM visitas_inscricoes WHERE id_visita = :id_visita AND id = :inscricao", "id_visita={$id_visita}&inscricao={$inscricao}");
        return $read;
    }

    public function getConfiguracoes(): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM configuracoes WHERE id = '1' LIMIT 1");
        return $read;
    }
    
    public function getInscricaoByCode($codigo): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM visitas_inscricoes WHERE codigo = :codigo", "codigo={$codigo}");
        return $read;
    }

    public function inscricaoCadastro($params)
    {
        $read = new Read();

        $codigo = $this->genCode($params['id_visita']);
        $cpf = $this->clearCPF($params['cpf']);

        if($params['setor'] == 'Outros'){
            $params['setor'] = $params['setor_outros'];
        }
        unset($params['setor_outros']);

        $read->FullRead("INSERT INTO `visitas_inscricoes` (`id_visita`, `codigo`, `empresa`, `nome`, `cpf`, `email`, `telefone`, `setor`, `cep`, `cidade`, `estado`) VALUES (:id_visita, :codigo, :empresa, :nome, :cpf, :email, :telefone, :setor, :cep, :cidade, :estado)", "id_visita={$params['id_visita']}&codigo={$codigo}&empresa={$params['empresa']}&nome={$params['nome']}&cpf={$cpf}&email={$params['email']}&telefone={$params['telefone']}&setor={$params['setor']}&cep={$params['cep']}&cidade={$params['cidade']}&estado={$params['estado']}");
        return $read;

    }

    public function inscricaoCadastroQRcode($params)
    {
        $read = new Read();

        $read->FullRead("UPDATE `visitas` SET `inscricoes` = (`inscricoes` + 1) WHERE `id` = :id_visita", "id_visita={$params['id_visita']}");
        $cpf = $this->clearCPF($params['cpf']);
        $read->FullRead("UPDATE `visitas_inscricoes` SET `qrcode` = :qrcode WHERE `id_visita` = :id_visita AND `cpf` = :cpf", "qrcode={$params['qrcode']}&id_visita={$params['id_visita']}&cpf={$cpf}");

        return $read;
    }

    public function checkCadastro($params)
    {
        $read = new Read();
        $cpf = $this->clearCPF($params['cpf']);
        $read->FullRead("SELECT * FROM visitas_inscricoes WHERE (cpf = :cpf OR email = :email OR telefone = :telefone)", "cpf={$cpf}&email={$params['email']}&telefone={$params['telefone']}");
        return $read;
    }

    public function lastInscricao(){
        $read = new Read();
        $read->FullRead("SELECT * FROM visitas_inscricoes ORDER BY id DESC LIMIT 1");
        return $read;
    }

    public function checkCadastroCampo($params){
        $read = new Read();
        if($params['campo'] == 'cpf'){
            $params['valor'] = $this->clearCPF($params['valor']);
        }
        $read->FullRead("SELECT * FROM visitas_inscricoes WHERE ".$params['campo']." = '".$params['valor']."' ORDER BY id DESC LIMIT 1");
        return $read;
    }

    function genCode($id_visita) { 

        $chars = "ABCDEFGHIJKMNOPQRSTUVWXYZ023456789";
        srand((double)microtime() * 1000000);
        $i = 0;
        $pass = '';

        while ($i <= 5) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }

        return $id_visita.$pass;

    } 

    function clearCPF($palavra){
        $palavra = trim(preg_replace("/[\s]+/", " ", $palavra));
        trim($palavra);
        $palavra = str_replace("(","",$palavra);
        $palavra = str_replace(")","",$palavra);
        $palavra = str_replace("+","",$palavra);
        $palavra = str_replace("-","",$palavra);
        $palavra = str_replace(".","",$palavra);
        $palavra = str_replace(" ","",$palavra);
        return($palavra);
    }

    public function sortear($params){
        $read = new Read();
        $read->FullRead("UPDATE `visitas_inscricoes` SET `sorteado` = 'Sim' WHERE id_visita = '".$params['id_visita']."' AND presenca = 'Sim' AND sorteado <> 'Sim' ORDER BY RAND() LIMIT ".$params['quantidade']."");
        return $read;
    }

    public function listarVisitasUserSorteados($id_visita): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM visitas_inscricoes WHERE id_visita = :id_visita AND sorteado = 'Sim'", "id_visita={$id_visita}");
        return $read;
    }

}