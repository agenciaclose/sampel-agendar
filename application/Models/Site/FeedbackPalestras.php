<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Conn\Read;
use Agencia\Close\Models\Model;

class FeedbackPalestras extends Model
{

    public function getPerguntas(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM feedback_perguntas ORDER BY ordem ASC");
        return $read;
    }

    public function savePerguntas($params): Create
    {
        $create = new Create();
        $create->ExeCreate('feedback_perguntas', $params);
        return $create;
    }

    public function getUserVisita($id_palestra, $ident): read
    {
        $read = new Read();
        $identSql = '';
        if ($ident != '') {
            $identLimpo = $this->clearCPF($ident);
            $identEsc = str_replace("'", "''", $identLimpo);
            $identSql = "AND (cpf = '".$identEsc."' OR codigo = '".$identEsc."')";
        }
        $read->FullRead("SELECT * FROM palestras_participantes WHERE id_palestra = :id_palestra $identSql", "id_palestra={$id_palestra}");
        return $read;
    }

    public function saveFeedback($params)
    {
        if (is_countable($params['pergunta']) && count($params['pergunta']) > 0){
            for ($i=0; $i < count($params['pergunta']); $i++) {

                $extra = '';
                $pergunta_id = $params['pergunta_id'][$i];
                if(isset($params['extra'][$pergunta_id])){
                    $extra = $params['extra'][$pergunta_id];
                }

                $read = new Read();
                $cpf = $this->clearCPF($params['user_cpf']);
                $read->FullRead("INSERT INTO `feedback_palestra` (`id_palestra`, `user_codigo`, `user_cpf`, `pergunta`, `resposta`, `extra`) 
                        VALUES ('".$params['id_palestra']."', '".$params['user_codigo']."', '".$cpf."', '".$params['pergunta'][$i]."', '".$params['resposta'][$i]."', '".$extra."')");
            }
        }

        $read = new Read();
        $read->FullRead("UPDATE `palestras_participantes` SET `presenca` = 'Sim' WHERE `codigo` = '".$params['user_codigo']."'");
    }

    public function checkFeedback($id_palestra, $userCodigo): Read
    {
        $read = new Read();
        if ($userCodigo === '' || $userCodigo === null) {
            $read->FullRead("SELECT * FROM feedback_palestra WHERE 1=0");
            return $read;
        }
        $userCodigoEsc = str_replace("'", "''", $userCodigo);
        $read->FullRead("SELECT * FROM feedback_palestra WHERE id_palestra = :id_palestra AND user_codigo = :user_codigo LIMIT 1", "id_palestra={$id_palestra}&user_codigo={$userCodigoEsc}");
        return $read;
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


    public function getFeedbacksPerguntas(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `feedback_perguntas`");
        return $read;
    }
    
    public function getFeedbacksList($id_palestra, $pergunta): Read
    {
        $read = new Read();
        $read->FullRead(" SELECT pergunta, resposta, COUNT(resposta) AS qtd FROM feedback_palestra WHERE `id_palestra` = :id_palestra AND `pergunta` = :pergunta GROUP BY resposta ORDER BY qtd DESC ", "id_palestra={$id_palestra}&pergunta={$pergunta}");
        return $read;
    }

}