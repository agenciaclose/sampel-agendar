<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Models\Model;

class Visitas extends Model
{

    public function listarVisitas($limit = '999999999'): read
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, u.nome, v.id AS visita_id, (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id) AS total_inscricao,
                        (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id AND presenca = 'Sim') AS presencas
						FROM visitas AS v
						INNER JOIN usuarios AS u ON u.id = v.id_empresa
						WHERE v.id_empresa = :user_id  AND v.`status_visita` <> 'Concluido' ORDER BY v.`data_visita` DESC LIMIT $limit", "user_id={$_SESSION['sampel_user_id']}");
        return $read;
    }

    public function listarVisitasConcluidas(): read
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, u.nome, v.id AS visita_id, (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id) AS total_inscricao,
                        (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id AND presenca = 'Sim') AS presencas
						FROM visitas AS v
						INNER JOIN usuarios AS u ON u.id = v.id_empresa
						WHERE v.`status_visita` = 'Concluido' ORDER BY v.`data_visita` DESC");
        return $read;
    }

    public function listarVisitasOutros(): read
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, u.nome, v.id AS visita_id, (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id) AS total_inscricao,
                        (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id AND presenca = 'Sim') AS presencas
						FROM visitas AS v
						INNER JOIN usuarios AS u ON u.id = v.id_empresa
						WHERE v.`status_visita` <> 'Concluido' ORDER BY v.`data_visita` DESC");
        return $read;
    }

    public function listarVisitasUser($id_visita = 0, $id_empresa = 0): read
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

        if(!empty($_GET['feedback'])){
            if($_GET['feedback'] == "Sim"){
                $feedback = "AND f.user_codigo is not null";
            }else{
                $feedback = "AND f.user_codigo  is null";
            }
        }else{
            $feedback = "";
        }

        $read->FullRead("SELECT vi.*, v.id AS visita_id, v.data_visita, v.horario_visita, f.user_codigo as feedback
                        FROM visitas_inscricoes AS vi
                        INNER JOIN visitas AS v ON v.id = vi.id_visita
                        INNER JOIN usuarios AS u ON u.id = v.id_empresa
                        LEFT JOIN feedback AS f ON f.user_codigo = vi.codigo
                        WHERE v.id_empresa = :user_id AND v.id = :id_visita $setor $presenca $feedback GROUP BY vi.codigo  ORDER BY vi.`data` DESC", "user_id={$id_empresa}&id_visita={$id_visita}");
        return $read;
    }

    public function listarVisitaID($id_visita): read
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, u.nome, v.id AS visita_id
                        FROM visitas AS v
                        INNER JOIN usuarios AS u ON u.id = v.id_empresa
                        WHERE v.id = :id_visita ORDER BY v.`data` DESC", "id_visita={$id_visita}");
        return $read;
    }

    public function listarInscricoes($id_visita): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM visitas_inscricoes WHERE id_visita = :id_visita ORDER BY `nome` ASC", "id_visita={$id_visita}");
        return $read;
    }

    public function listarInscricoesTotal($id_visita): read
    {
        $read = new Read();
        $read->FullRead("SELECT count(id) as total FROM visitas_inscricoes WHERE id_visita = :id_visita ORDER BY `nome` ASC", "id_visita={$id_visita}");
        return $read;
    }

    public function listarComparecimentosTotal($id_visita): read
    {
        $read = new Read();
        $read->FullRead("SELECT count(id) as total FROM visitas_inscricoes WHERE id_visita = :id_visita AND presenca = 'Sim' ORDER BY `nome` ASC", "id_visita={$id_visita}");
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
        $params['codigo'] = $this->genCode($params['id_visita']);
        $params['cpf'] = $this->clearCPF($params['cpf']);

        if($params['setor'] == 'Outros'){
            $params['setor'] = $params['setor_outros'];
        }
        unset($params['setor_outros']);
        unset($params['tipo_visita']);

        $create = new Create();
        $create->ExeCreate('visitas_inscricoes', $params);
        return $create;

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
        if($params['tipo_visita'] != 'visita'){
            $porEvento = "AND id_visita = '".$params['id_visita']."' AND DATEDIFF(CURDATE(), `data`) <= 365";
        }else{
            $porEvento = "";
        }
        
        $read->FullRead("SELECT * FROM visitas_inscricoes WHERE ".$params['campo']." = '".$params['valor']."' $porEvento ORDER BY id DESC LIMIT 1");
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
        
        $read = new Read();
        $read->FullRead("UPDATE `visitas_inscricoes` SET `repescagem` = 'Sim' WHERE id_visita = '".$params['id_visita']."' AND presenca = 'Sim' AND sorteado <> 'Sim' ORDER BY RAND() LIMIT ".$params['repescagem']."");

        return $read;
    }

    public function listarVisitasUserSorteados($id_visita): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM visitas_inscricoes WHERE id_visita = :id_visita AND sorteado = 'Sim'", "id_visita={$id_visita}");
        return $read;
    }

    public function listaEquipes(): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM usuarios WHERE tipo = '4' ORDER BY id DESC");
        return $read;
    }

    public function listaEquipesSave($params): read
    {
        $read = new Read();
        $check = new Read();
        if(!empty($params['editar_equipe'])){
            for ($i=0; $i <count($params['editar_equipe']); $i++) {
                
                $check->FullRead("SELECT * FROM `visitas_equipes` WHERE `id_visita` = :id_visita AND `id_user` = :id_user", "id_visita={$params['id_visita']}&id_user={$params['editar_equipe'][$i]}");
                if(!$check->getResult()){
                    $read->FullRead("INSERT INTO `visitas_equipes` (`id_visita`, `id_user`) VALUES (:id_visita, :id_user)", "id_visita={$params['id_visita']}&id_user={$params['editar_equipe'][$i]}");
                }
            
            }
        }
        return $read;
    }

    public function removeEquipe($params): read
    {
        $read = new Read();
        $read->FullRead("DELETE FROM `visitas_equipes` WHERE `id_visita` = :visita AND `id_user` = :membro", "visita={$params['visita']}&membro={$params['membro']}");
        return $read;
    }
    
    public function listaEquipesVisita($id_visita): read
    {
        $read = new Read();
        $read->FullRead("SELECT u.*, ve.`data` AS data_equipe FROM visitas_equipes AS ve
        INNER JOIN usuarios AS u ON u.id = ve.id_user
        WHERE ve.id_visita = :id_visita ORDER BY u.nome ASC", "id_visita={$id_visita}");
        return $read;
    }

    public function listaEquipesAll(): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM usuarios WHERE tipo = '4'");
        return $read;
    }

    public function sendUpdate($id_visita): read
    {
        $read = new Read();
        $read->FullRead("UPDATE `visitas` SET `email_equipe` = 'S' WHERE `id` = :id_visita", "id_visita={$id_visita}");
        return $read;
    }

    public function CPFAutoComplete($cpf): read
    {
        $read = new Read();
        $cpf = $this->clearCPF($cpf);
        $read->FullRead("SELECT * FROM `visitas_inscricoes` WHERE `cpf` = :cpf", "cpf={$cpf}");
        return $read;
    }


    public function getFeedbacksPerguntas(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `feedback_perguntas`");
        return $read;
    }
    
    public function getFeedbacksList($pergunta): Read
    {
        $read = new Read();
        $read->FullRead(" SELECT pergunta, resposta, COUNT(resposta) AS qtd FROM feedback WHERE `pergunta` = :pergunta GROUP BY resposta ORDER BY qtd DESC ", "pergunta={$pergunta}");
        return $read;
    }

    public function visitaQRcodeFeedbackSave($params)
    {
        $read = new Read();
        $read->FullRead("UPDATE `visitas` SET `qrcode_feedback` = :qrcode WHERE `id` = :id_visita", "qrcode={$params['qrcode']}&id_visita={$params['id_visita']}");
        return $read;
    }

    public function visitaQRcodeSave($params)
    {
        $read = new Read();
        $read->FullRead("UPDATE `visitas` SET `qrcode_inscricao` = :qrcode WHERE `id` = :id_visita", "qrcode={$params['qrcode']}&id_visita={$params['id_visita']}");
        return $read;
    }

    public function updateStatusVisita($id_visita): read
    {
        $read = new Read();
        $read->FullRead("UPDATE `visitas` SET `status_visita` = 'Aprovado' WHERE `id` = :id_visita", "id_visita={$id_visita}");
        return $read;
    }

    public function getVisitasImages($id_visita, $id_user = null): Read
    {
        $read = new Read();
        if($id_user != null) {
            $porUser = "AND id_user = '".$id_user."'";
        }else{
            $porUser = "";
        }
        $read->FullRead("SELECT * FROM visitas_imagens WHERE id_visita = :id_visita ".$porUser." ORDER BY `order`,`id` DESC", "id_visita={$id_visita}");
        return $read;
    }

}