<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Models\Model;

class RelatoriosPalestras extends Model
{

    public function getAllVisitas(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `palestras` WHERE status_palestra <> 'Recusado' AND id_empresa <> '1'");
        return $read;
    }

    public function getAllNumeros(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT
        (SELECT COUNT(id) AS total FROM palestras_participantes WHERE id_palestra = palestras.id AND codigo <> '') AS total_inscritos,
        (SELECT COUNT(id) AS total FROM palestras_participantes WHERE id_palestra = palestras.id AND codigo <> '' AND presenca = 'Sim') AS total_confirmados,
        (SELECT COUNT(id) AS total FROM palestras_participantes WHERE id_palestra = palestras.id AND codigo <> '' AND presenca = 'No') AS total_no_confirmados,
        (SELECT COUNT(id) AS total FROM palestras_participantes WHERE id_palestra = palestras.id AND codigo <> '' AND certificado = 'Sim') AS total_certificados
        FROM palestras WHERE status_palestra <> 'Recusado'");
        return $read;
    }

    public function getTotalSetor(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT vi.id, vi.setor, COUNT(vi.id) AS total FROM `palestras_participantes` AS vi
                        INNER JOIN palestras AS v ON v.id = vi.id_palestra
                        WHERE v.status_palestra <> 'Recusado' AND  vi.setor <> '' AND vi.codigo <> ''
                        GROUP BY vi.setor ORDER BY total DESC LIMIT 10");
        return $read;
    }

    public function getTotalCidade(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT vi.id, vi.cidade, vi.estado, COUNT(vi.id) AS total FROM `palestras_participantes` AS vi
                        INNER JOIN palestras AS v ON v.id = vi.id_palestra
                        WHERE v.status_palestra <> 'Recusado' AND vi.cidade <> '' AND vi.codigo <> ''
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

}