<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Models\Model;

class Relatorios extends Model
{

    public function getAllVisitas(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `visitas`");
        return $read;
    }

    public function getAllNumeros(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT
        (SELECT COUNT(id) AS total FROM visitas_inscricoes WHERE id_visita = visitas.id) AS total_inscritos,
        (SELECT COUNT(id) AS total FROM visitas_inscricoes WHERE id_visita = visitas.id AND presenca = 'Sim') AS total_confirmados,
        (SELECT COUNT(id) AS total FROM visitas_inscricoes WHERE id_visita = visitas.id AND presenca = 'No') AS total_no_confirmados,
        (SELECT COUNT(id) AS total FROM visitas_inscricoes WHERE id_visita = visitas.id AND certificado = 'Sim') AS total_certificados
        FROM visitas");
        return $read;
    }

    public function getTotalSetor(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT vi.id, vi.setor, COUNT(vi.id) AS total FROM `visitas_inscricoes` AS vi
                        INNER JOIN visitas AS v ON v.id = vi.id_visita
                        WHERE v.status_visita = 'Concluido' AND  vi.setor <> ''
                        GROUP BY vi.setor ORDER BY total DESC LIMIT 10");
        return $read;
    }

    public function getTotalSetorEquipe(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT u.setor, COUNT(u.setor) AS total FROM visitas_equipes AS ve
                        INNER JOIN usuarios AS u ON u.id = ve.id_user
                        INNER JOIN visitas AS v ON v.id = ve.id_visita
                        WHERE v.status_visita = 'Concluido' AND ve.id_user not in ('26') AND u.tipo = '4'
                        GROUP BY u.setor ORDER BY total DESC");
        return $read;
    }

    public function getTotalCidade(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT vi.id, vi.cidade, vi.estado, COUNT(vi.id) AS total FROM `visitas_inscricoes` AS vi
                        INNER JOIN visitas AS v ON v.id = vi.id_visita
                        WHERE v.status_visita = 'Concluido' AND vi.cidade <> ''
                        GROUP BY vi.cidade ORDER BY total DESC");
        return $read;
    }

    public function getTotalEquipeByVisita(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT u.nome, COUNT(id_user) AS total FROM visitas_equipes AS ve
                        INNER JOIN usuarios AS u ON u.id = ve.id_user
                        INNER JOIN visitas AS v ON v.id = ve.id_visita
                        WHERE v.status_visita = 'Concluido' AND ve.id_user not in ('26')
                        GROUP BY ve.id_user ORDER BY total DESC");
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
        $read->FullRead("SELECT pergunta, resposta, COUNT(resposta) AS qtd FROM feedback WHERE `pergunta` = :pergunta GROUP BY resposta ORDER BY qtd DESC", "pergunta={$pergunta}");
        return $read;
    }

    public function getFeedbacksListPessoas($resposta): Read
    {
        $read = new Read();
        $read->FullRead("SELECT vi.nome, vi.email, vi.telefone FROM feedback AS f
                        INNER JOIN visitas_inscricoes AS vi ON vi.cpf = f.user_cpf
                        WHERE f.`resposta` = :resposta", "resposta={$resposta}");
        return $read;
    }

}