<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Models\Model;

class Palestras extends Model
{
    public function lista($params): read
    {
        $read = new Read();
        $read->FullRead("SELECT p.*, (SELECT count(id) FROM palestras_participantes AS pp WHERE pp.id_palestra = p.id ) AS total_inscricao FROM palestras AS p ORDER BY p.data_palestra DESC");
        return $read;
    }

    public function palestraSave($params)
    {
        $params['id_empresa'] = $_SESSION['sampel_user_id'];

        $create = new Create();
        $create->ExeCreate('palestras', $params);
        return $create;
    }

    public function getPalestra($id): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM palestras WHERE id = :id", "id={$id}");
        return $read;
    }

    public function lastPalestra()
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM palestras ORDER BY id DESC LIMIT 1");
        return $read;
    }

    public function palestraQRcodeFeedbackSave($params)
    {
        $read = new Read();
        $read->FullRead("UPDATE `palestras` SET `qrcode_feedback` = :qrcode WHERE `id` = :id_palestra", "qrcode={$params['qrcode']}&id_palestra={$params['id_palestra']}");
        return $read;
    }

    public function palestraQRcodeSave($params)
    {
        $read = new Read();
        $read->FullRead("UPDATE `palestras` SET `qrcode_inscricao` = :qrcode WHERE `id` = :id_palestra", "qrcode={$params['qrcode']}&id_palestra={$params['id_palestra']}");
        return $read;
    }

    public function checkCadastro($params)
    {
        $read = new Read();
        $cpf = $this->clearCPF($params['cpf']);
        $read->FullRead("SELECT * FROM palestras_participantes WHERE cpf = :cpf AND id_palestra = :id_palestra", "cpf={$cpf}&id_palestra={$params['id_palestra']}");
        return $read;
    }

    function genCode($id_palestra) { 

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

        return $id_palestra.$pass;

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

    public function inscricaoCadastro($params)
    {
        $params['codigo'] = $this->genCode($params['id_palestra']);
        $params['cpf'] = $this->clearCPF($params['cpf']);

        if($params['setor'] == 'Outros'){
            $params['setor'] = $params['setor_outros'];
        }
        unset($params['setor_outros']);

        $create = new Create();
        $create->ExeCreate('palestras_participantes', $params);
        return $create;

    }

    public function lastInscricao(){
        $read = new Read();
        $read->FullRead("SELECT * FROM palestras_participantes ORDER BY id DESC LIMIT 1");
        return $read;
    }

    public function checkCadastroCampo($params){
        $read = new Read();
        if($params['campo'] == 'cpf'){
            $params['valor'] = $this->clearCPF($params['valor']);
        }
        $porEvento = "AND id_palestra = '".$params['id_palestra']."'";
 
        $read->FullRead("SELECT * FROM palestras_participantes WHERE ".$params['campo']." = '".$params['valor']."' $porEvento ORDER BY id DESC LIMIT 1");
        return $read;
    }

    public function inscricaoCadastroQRcode($params)
    {
        $read = new Read();
        $cpf = $this->clearCPF($params['cpf']);
        $read->FullRead("UPDATE `palestras_participantes` SET `qrcode` = :qrcode WHERE `id_palestra` = :id_palestra AND `cpf` = :cpf", "qrcode={$params['qrcode']}&id_palestra={$params['id_palestra']}&cpf={$cpf}");
        return $read;
    }

    public function getInscricao($id_palestra, $inscricao): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM palestras_participantes WHERE id_palestra = :id_palestra AND id = :inscricao", "id_palestra={$id_palestra}&inscricao={$inscricao}");
        return $read;
    }

    public function listarPalestraInscritos($id_palestra = 0, $id_empresa = 0): read
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

        $read->FullRead("SELECT pp.*, p.id AS palestra_id FROM palestras_participantes AS pp
                        INNER JOIN palestras AS p ON p.id = pp.id_palestra
                        INNER JOIN usuarios AS u ON u.id = p.id_empresa
                        WHERE p.id_empresa = :user_id  AND p.id = :id_palestra $setor $presenca ORDER BY pp.`data` DESC", "user_id={$id_empresa}&id_palestra={$id_palestra}");
        return $read;
    }

    public function listarInscricoesTotal($id_palestra): read
    {
        $read = new Read();
        $read->FullRead("SELECT count(id) as total FROM palestras_participantes WHERE id_palestra = :id_palestra", "id_palestra={$id_palestra}");
        return $read;
    }

    public function listarInscricoesByGroup($id_palestra): read
    {
        $read = new Read();
        $read->FullRead("SELECT *, COUNT(id) AS total FROM palestras_participantes WHERE id_palestra = :id_palestra GROUP BY setor", "id_palestra={$id_palestra}");
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
        $read->FullRead("SELECT * FROM palestras_participantes WHERE codigo = :codigo", "codigo={$codigo}");
        return $read;
    }

    public function sortear($params){
        $read = new Read();
        $read->FullRead("UPDATE `palestras_participantes` SET `sorteado` = 'Sim' WHERE id_palestra = '".$params['id_palestra']."' AND presenca = 'Sim' AND sorteado <> 'Sim' ORDER BY RAND() LIMIT ".$params['quantidade']."");
        return $read;
    }

    public function listarPalestraUserSorteados($id_palestra): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM palestras_participantes WHERE id_palestra = :id_palestra AND sorteado = 'Sim'", "id_palestra={$id_palestra}");
        return $read;
    }

    public function listarPalestrasUserSorteados($id_palestra): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM palestras_participantes WHERE id_palestra = :id_palestra AND sorteado = 'Sim'", "id_palestra={$id_palestra}");
        return $read;
    }

}