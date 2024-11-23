<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class Visitas extends Model
{

    public function listarVisitas($limit = '999999999'): read
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, u.nome, v.id AS visita_id, 
                        (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id) AS total_inscricao,
                        (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id AND presenca = 'Sim') AS presencas,
                        (SELECT p.id FROM pedidos p WHERE p.tipo_evento = 'visitas' AND p.id_evento = v.id LIMIT 1) AS pedido,
                        (SELECT p.status_pedido FROM pedidos p WHERE p.tipo_evento = 'visitas' AND p.id_evento = v.id LIMIT 1) AS status_pedido
						FROM visitas AS v
						INNER JOIN usuarios AS u ON u.id = v.id_empresa
						WHERE v.id_empresa = :user_id  AND v.`status_visita` <> 'Concluido' ORDER BY v.`data_visita` DESC LIMIT $limit", "user_id={$_SESSION['sampel_user_id']}");
        return $read;
    }

    public function listarVisitasConcluidas(): read
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, u.nome, v.id AS visita_id, 
                        (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id) AS total_inscricao,
                        (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id AND presenca = 'Sim') AS presencas,
                        (SELECT p.id FROM pedidos p WHERE p.tipo_evento = 'visitas' AND p.id_evento = v.id LIMIT 1) AS pedido,
                        (SELECT p.status_pedido FROM pedidos p WHERE p.tipo_evento = 'visitas' AND p.id_evento = v.id LIMIT 1) AS status_pedido
						FROM visitas AS v
						INNER JOIN usuarios AS u ON u.id = v.id_empresa
						WHERE v.`status_visita` = 'Concluido' ORDER BY v.`data_visita` DESC");
        return $read;
    }

    public function listarVisitasOutros(): read
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, u.nome, v.id AS visita_id, 
                        (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id) AS total_inscricao,
                        (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id AND presenca = 'Sim') AS presencas,
                        (SELECT p.id FROM pedidos p WHERE p.tipo_evento = 'visitas' AND p.id_evento = v.id LIMIT 1) AS pedido,
                        (SELECT p.status_pedido FROM pedidos p WHERE p.tipo_evento = 'visitas' AND p.id_evento = v.id LIMIT 1) AS status_pedido
						FROM visitas AS v
						INNER JOIN usuarios AS u ON u.id = v.id_empresa
						WHERE v.`status_visita` not in ('Concluido', 'Recusado') ORDER BY v.`data_visita` ASC");
        return $read;
    }

    public function listarVisitasUltimas(): read
    {
        $read = new Read();
        $read->FullRead("SELECT v.id, v.title, vi.imagem, v.data_visita FROM visitas AS v INNER JOIN (SELECT vi1.id_visita, vi1.imagem FROM visitas_imagens vi1 WHERE vi1.id = (SELECT vi2.id FROM visitas_imagens vi2 WHERE vi2.id_visita = vi1.id_visita ORDER BY vi2.`order` ASC, vi2.id DESC LIMIT 1)) AS vi ON vi.id_visita = v.id WHERE v.data_visita <= CURRENT_DATE GROUP BY v.id ORDER BY v.data_visita DESC LIMIT 6");
        return $read;
    }

    public function listarUltimasFotosPalestras(): read
    {
        $read = new Read();
        $read->FullRead("SELECT p.id, p.title, pim.imagem, p.data_palestra FROM palestras AS p INNER JOIN (SELECT pim1.id_palestra, pim1.imagem FROM palestras_imagens pim1 WHERE pim1.id = (SELECT pim2.id FROM palestras_imagens pim2 WHERE pim2.id_palestra = pim1.id_palestra ORDER BY pim2.`order` ASC, pim2.id DESC LIMIT 1)) AS pim ON pim.id_palestra = p.id WHERE p.data_palestra <= CURRENT_DATE GROUP BY p.id ORDER BY p.data_palestra DESC LIMIT 6");
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
                $feedback = "AND vi.presenca = 'Sim' AND f.user_codigo is null";
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

    public function listarFaltasTotal($id_visita): read
    {
        $read = new Read();
        $read->FullRead("SELECT count(id) as total FROM visitas_inscricoes WHERE id_visita = :id_visita AND presenca = 'No' ORDER BY `nome` ASC", "id_visita={$id_visita}");
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

        if(!empty($_SESSION['sampel_user_id'])){
            $params['id_cadastrou'] = $_SESSION['sampel_user_id'];
        }

        $create = new Create();
        $create->ExeCreate('visitas_inscricoes', $params);
        return $create;

    }

    public function inscricaEeditar($params): update
    {
        $params['cpf'] = $this->clearCPF($params['cpf']);

        if($params['setor'] == 'Outros'){
            $params['setor'] = $params['setor_outros'];
        }
        unset($params['setor_outros']);

        if(!empty($_SESSION['sampel_user_id'])){
            $params['id_atualizou'] = $_SESSION['sampel_user_id'];
        }

        $update = new Update();
        $update->ExeUpdate('visitas_inscricoes', $params, 'WHERE id = :id', "id={$params['id']}");
        return $update;

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
        
        $porevento = "";
        if($params['campo'] == 'cpf'){
            $params['valor'] = $this->clearCPF($params['valor']);
            $porevento = " OR cpf = '".$params['valor']."' AND v.id = '".$params['id_visita']."' ";
        }

        if($params['tipo_visita'] != 'visita'){
            //$porEvento = " AND id_visita = '".$params['id_visita']."' ";
            $porEvento = "";
            $porEventoTipo = "AND DATEDIFF(CURDATE(), vi.`data`) <= 365";
        }else{
            $porEvento = "";
            $porEventoTipo = " AND DATEDIFF(CURDATE(), vi.`data`) <= 365 AND v.tipo <> 'evento' ";
        }

        $read->FullRead("SELECT vi.*, v.data_visita FROM visitas_inscricoes AS vi
                        INNER JOIN visitas AS v ON v.id = vi.id_visita 
                        WHERE presenca = 'Sim' AND ".$params['campo']." = '".$params['valor']."' $porevento
                        $porEvento
                        $porEventoTipo
                        ORDER BY vi.id DESC LIMIT 1");
        return $read;

    }

    public function autocomplete($cpf)
    {
        $cpf = $this->clearCPF($cpf);
        $read = new Read();
        $read->FullRead("SELECT * FROM visitas_inscricoes WHERE cpf = :cpf", "cpf={$cpf}");
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
        $read->FullRead("SELECT * FROM visitas_inscricoes WHERE id_visita = :id_visita AND (sorteado = 'Sim' OR repescagem = 'Sim') ORDER BY sorteado ASC", "id_visita={$id_visita}");
        return $read;
    }

    public function listaEquipes(): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM usuarios WHERE tipo = '4' AND `situacao` <> 'Inativo' ORDER BY nome ASC");
        return $read;
    }

    public function listaEquipesSave($params): read
    {
        $read = new Read();
        $check = new Read();
        if(!empty($params['equipe'])){

            if(is_countable($params['equipe'])){

                for ($i=0; $i < count($params['equipe']); $i++) {
                    
                    $check->FullRead("SELECT * FROM `visitas_equipes` WHERE `id_visita` = :id_visita AND `id_user` = :id_user", "id_visita={$params['id_visita']}&id_user={$params['equipe'][$i]['editar_equipe']}");
                    
                    if(!$check->getResult()){
                        //SALVA SE NAO EXISTIR
                        $read->FullRead("INSERT INTO `visitas_equipes` (`id_visita`, `id_user`, `funcao`) VALUES (:id_visita, :id_user, :funcao)", "id_visita={$params['id_visita']}&id_user={$params['equipe'][$i]['editar_equipe']}&funcao={$params['equipe'][$i]['funcao']}");
                    }else{
                        //DELETA E SALVA SE NAO EXISTIR
                        $read->FullRead("DELETE FROM `visitas_equipes` WHERE  `id_visita` = :id_visita AND `id_user` = :id_user", "id_visita={$params['id_visita']}&id_user={$params['equipe'][$i]['editar_equipe']}");
                        $read->FullRead("INSERT INTO `visitas_equipes` (`id_visita`, `id_user`, `funcao`) VALUES (:id_visita, :id_user, :funcao)", "id_visita={$params['id_visita']}&id_user={$params['equipe'][$i]['editar_equipe']}&funcao={$params['equipe'][$i]['funcao']}");
                    }
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
        $read->FullRead("SELECT u.*, ve.`funcao`, ve.`data` AS data_equipe FROM visitas_equipes AS ve
        INNER JOIN usuarios AS u ON u.id = ve.id_user
        WHERE ve.id_visita = :id_visita ORDER BY u.nome ASC", "id_visita={$id_visita}");
        return $read;
    }

    public function listaEquipesAll(): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM usuarios WHERE tipo = '4' AND `situacao` <> 'Inativo'");
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

    public function listarUserExportVisita(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT UPPER(CONVERT(BINARY CONVERT(nome USING latin1) USING UTF8MB4)) AS nome, LOWER(email) AS email, telefone FROM visitas_inscricoes GROUP BY LOWER(email) ORDER BY nome ASC");
        return $read;
    }

    public function listarGalerias(): read
    {
        $read = new Read();
        $read->FullRead("SELECT v.id, v.title, vi.imagem, v.data_visita FROM visitas AS v INNER JOIN (SELECT vi1.id_visita, vi1.imagem FROM visitas_imagens vi1 WHERE vi1.id = (SELECT vi2.id FROM visitas_imagens vi2 WHERE vi2.id_visita = vi1.id_visita ORDER BY vi2.`order` ASC, vi2.id DESC LIMIT 1)) AS vi ON vi.id_visita = v.id WHERE v.data_visita <= CURRENT_DATE GROUP BY v.id ORDER BY v.data_visita DESC");
        return $read;
    }

}