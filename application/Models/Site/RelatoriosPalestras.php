<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Models\Model;

class RelatoriosPalestras extends Model
{

    public function getAnosPaletras(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT YEAR(data_palestra) AS ano FROM palestras WHERE status_palestra NOT IN ('Recusado') GROUP BY ano ORDER BY ano DESC");
        return $read;
    }

    public function getAllVisitas($ano): Read
    {
        if($ano != ''){
            $ano = " AND YEAR(data_palestra) = '".$ano."' ";
        }
        $read = new Read();
        $read->FullRead("SELECT * FROM `palestras` WHERE status_palestra <> 'Recusado' $ano");
        return $read;
    }

    public function getAllNumeros($ano): Read
    {
        if($ano != ''){
            $ano = " AND YEAR(data_palestra) = '".$ano."' ";
        }
        $read = new Read();
        $read->FullRead("SELECT
        (SELECT COUNT(id) AS total FROM palestras_participantes WHERE id_palestra = palestras.id) AS total_inscritos,
        (SELECT COUNT(id) AS total FROM palestras_participantes WHERE id_palestra = palestras.id AND presenca = 'Sim') AS total_confirmados,
        (SELECT COUNT(id) AS total FROM palestras_participantes WHERE id_palestra = palestras.id AND presenca = 'No') AS total_no_confirmados,
        (SELECT COUNT(id) AS total FROM palestras_participantes WHERE id_palestra = palestras.id AND certificado = 'Sim') AS total_certificados
        FROM palestras WHERE status_palestra <> 'Recusado' $ano");
        return $read;
    }

    public function getTotalSetor($ano): Read
    {
        if($ano != ''){
            $ano = " AND YEAR(v.data_palestra) = '".$ano."' ";
        }
        $read = new Read();
        $read->FullRead("SELECT vi.id, vi.setor, COUNT(vi.id) AS total FROM `palestras_participantes` AS vi
                        INNER JOIN palestras AS v ON v.id = vi.id_palestra
                        WHERE v.status_palestra <> 'Recusado' AND  vi.setor <> '' AND vi.codigo <> '' $ano
                        GROUP BY vi.setor ORDER BY total DESC LIMIT 10");
        return $read;
    }

    public function getTotalCidade($ano): Read
    {
        if($ano != ''){
            $ano = " AND YEAR(v.data_palestra) = '".$ano."' ";
        }
        $read = new Read();
        $read->FullRead("SELECT vi.id, vi.cidade, vi.estado, COUNT(vi.id) AS total FROM `palestras_participantes` AS vi
                        INNER JOIN palestras AS v ON v.id = vi.id_palestra
                        WHERE v.status_palestra <> 'Recusado' AND vi.cidade <> '' $ano
                        GROUP BY vi.cidade ORDER BY total DESC");
        return $read;
    }

  
    public function getFeedbacksPerguntas(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `feedback_perguntas` WHERE `tipo` <> 'Texto'");
        return $read;
    }

    public function getFeedbacksList($pergunta): Read
    {
        $read = new Read();
        $read->FullRead("SELECT pergunta, resposta, COUNT(resposta) AS qtd FROM feedback_palestra WHERE `pergunta` = :pergunta GROUP BY resposta ORDER BY qtd DESC", "pergunta={$pergunta}");
        return $read;
    }

    public function getFeedbacksListPessoas($resposta): Read
    {
        $read = new Read();
        $read->FullRead("SELECT vi.nome, vi.email, vi.telefone FROM feedback AS f
                        INNER JOIN palestras_participantes AS vi ON vi.cpf = f.user_cpf
                        WHERE f.`resposta` = :resposta", "resposta={$resposta}");
        return $read;
    }

    public function listaDeCidades(): read
    {
        $ano = '';
        if(isset($_GET['ano']) && $_GET['ano'] != ''){
            $ano = " AND YEAR(p.data_palestra) = '".$_GET['ano']."' ";
        }
        
        $read = new Read();
        $read->FullRead("SELECT pp.cidade, pp.estado FROM palestras_participantes AS pp
        INNER JOIN palestras AS p ON p.id = pp.id_palestra
        WHERE pp.cidade <> '' $ano");
        return $read;
    }

    public function getParticipantesAno(): read
    {   
        $ano = '';
        if(isset($_GET['ano']) && $_GET['ano'] != ''){
            $ano = "WHERE YEAR(p.data_palestra) = '".$_GET['ano']."' ";
        }
        $read = new Read();
        $read->FullRead("SELECT 
                        YEAR(STR_TO_DATE(p.data_palestra, '%Y-%m-%dT%H:%i')) AS ano,
                        COUNT(pp.id) AS participantes
                        FROM palestras p
                        JOIN palestras_participantes pp ON pp.id_palestra = p.id $ano
                    GROUP BY ano
                    ORDER BY ano");
        return $read;
    }
}