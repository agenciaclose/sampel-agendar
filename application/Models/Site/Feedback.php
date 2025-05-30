<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Conn\Read;
use Agencia\Close\Models\Model;

class Feedback extends Model
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

    public function getUserVisita($id_visita, $cpf): read
    {
        $read = new Read();
        $cpf = $this->clearCPF($cpf);
        $read->FullRead("SELECT * FROM visitas_inscricoes WHERE id_visita = :id_visita AND cpf = :cpf", "id_visita={$id_visita}&cpf={$cpf}");
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
                $read->FullRead("INSERT INTO `feedback` (`id_visita`, `user_codigo`, `user_cpf`, `pergunta`, `resposta`, `extra`) 
                        VALUES ('".$params['id_visita']."', '".$params['user_codigo']."', '".$cpf."', '".$params['pergunta'][$i]."', '".$params['resposta'][$i]."', '".$extra."')");

            }
        }

        $read = new Read();
        $read->FullRead("UPDATE `visitas_inscricoes` SET `presenca` = 'Sim' WHERE `codigo` = '".$params['user_codigo']."'");
    }

    public function checkFeedback($id_visita, $cpf): Read
    {
        $read = new Read();
        $cpf = $this->clearCPF($cpf);
        $read->FullRead("SELECT * FROM feedback WHERE id_visita = :id_visita AND user_cpf = :user_cpf LIMIT 1", "id_visita={$id_visita}&user_cpf={$cpf}");
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
    
    public function getFeedbacksList($id_visita, $pergunta): Read
    {
        $read = new Read();
        $read->FullRead("SELECT pergunta, resposta, COUNT(resposta) AS qtd FROM feedback WHERE `id_visita` = :id_visita AND `pergunta` = :pergunta GROUP BY resposta ORDER BY qtd DESC", "id_visita={$id_visita}&pergunta={$pergunta}");
        return $read;
    }

    public function getFeedbacksListPessoas($id_visita, $resposta): Read
    {
        $read = new Read();
        $read->FullRead("SELECT vi.nome, vi.email, vi.telefone FROM feedback AS f
                        INNER JOIN visitas_inscricoes AS vi ON vi.cpf = f.user_cpf
                        WHERE f.`id_visita` = :id_visita AND f.`resposta` = :resposta", "id_visita={$id_visita}&resposta={$resposta}");
        return $read;
    }

}