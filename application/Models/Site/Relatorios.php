<?php

namespace Agencia\Close\Models\Site;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Models\Model;

class Relatorios extends Model
{

    public function getTipoLocal() 
    {
        $filtro_tipo = " AND tipo_local = 'Interna' ";
        if(isset($_GET['t']) && ($_GET['t'] == 'e')){
            $filtro_tipo = " AND tipo_local = 'Externa' ";
        }
        return $filtro_tipo;
    }
    
    public function getAnosVisitas(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT YEAR(data_visita) AS ano FROM visitas WHERE status_visita NOT IN ('Pendente', 'Recusado') ".$this->getTipoLocal()." GROUP BY ano ORDER BY ano DESC");
        return $read;
    }

    public function getAllVisitas($ano): Read
    {
        if($ano != ''){
            $ano = " AND YEAR(data_visita) = '".$ano."' ";
        }
        $read = new Read();
        $read->FullRead("SELECT * FROM `visitas` WHERE status_visita not in ('Pendente','Recusado') ".$this->getTipoLocal()." $ano");
        return $read;
    }

    public function getAllNumeros($ano): Read
    {
        if($ano != ''){
            $ano = " AND YEAR(data_visita) = '".$ano."' ";
        }

        $read = new Read();
        $read->FullRead("SELECT
        (SELECT COUNT(id) AS total FROM visitas_inscricoes WHERE id_visita = visitas.id) AS total_inscritos,
        (SELECT COUNT(id) AS total FROM visitas_inscricoes WHERE id_visita = visitas.id AND presenca = 'Sim') AS total_confirmados,
        (SELECT COUNT(id) AS total FROM visitas_inscricoes WHERE id_visita = visitas.id AND presenca = 'No') AS total_no_confirmados,
        (SELECT COUNT(id) AS total FROM visitas_inscricoes WHERE id_visita = visitas.id AND certificado = 'Sim') AS total_certificados
        FROM visitas WHERE status_visita NOT IN ('Pendente', 'Recusado') ".$this->getTipoLocal()." $ano");
        return $read;
    }

    public function getTotalSetor($ano): Read
    {
        if($ano != ''){
            $ano = " AND YEAR(v.data_visita) = '".$ano."' ";
        }
        $read = new Read();
        $read->FullRead("SELECT vi.id, vi.setor, COUNT(vi.id) AS total FROM `visitas_inscricoes` AS vi
                        INNER JOIN visitas AS v ON v.id = vi.id_visita
                        WHERE  vi.setor <> '' ".$this->getTipoLocal()." $ano
                        GROUP BY vi.setor ORDER BY total DESC LIMIT 10");
        return $read;
    }

    public function getTotalSetorEquipe($ano): Read
    {
        if($ano != ''){
            $ano = " AND YEAR(v.data_visita) = '".$ano."' ";
        }
        $read = new Read();
        $read->FullRead("SELECT u.setor, COUNT(u.setor) AS total FROM visitas_equipes AS ve
                        INNER JOIN usuarios AS u ON u.id = ve.id_user
                        INNER JOIN visitas AS v ON v.id = ve.id_visita
                        WHERE ve.id_user not in ('26') AND u.tipo = '4' ".$this->getTipoLocal()." $ano
                        GROUP BY u.setor ORDER BY total DESC");
        return $read;
    }

    public function getTotalCidade($ano): Read
    {
        if($ano != ''){
            $ano = " AND YEAR(v.data_visita) = '".$ano."' ";
        }
        $read = new Read();
        $read->FullRead("SELECT vi.id, vi.cidade, vi.estado, COUNT(vi.id) AS total FROM `visitas_inscricoes` AS vi
                        INNER JOIN visitas AS v ON v.id = vi.id_visita
                        WHERE vi.cidade <> '' ".$this->getTipoLocal()." $ano
                        GROUP BY vi.cidade ORDER BY total DESC");
        return $read;
    }

    public function getTotalEquipeByVisita($ano): Read
    {
        if($ano != ''){
            $ano = " AND YEAR(v.data_visita) = '".$ano."' ";
        }
        $read = new Read();
        $read->FullRead("SELECT u.nome, COUNT(id_user) AS total FROM visitas_equipes AS ve
                        INNER JOIN usuarios AS u ON u.id = ve.id_user
                        INNER JOIN visitas AS v ON v.id = ve.id_visita
                        WHERE ve.id_user not in ('26') ".$this->getTipoLocal()." $ano
                        GROUP BY ve.id_user ORDER BY total DESC");
        return $read;
    }

    public function getFeedbacksPerguntas(): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM `feedback_perguntas` WHERE `tipo` <> 'Texto'");
        return $read;
    }

    public function getFeedbacksList($pergunta, $ano): Read
    {
        if($ano != ''){
            $ano = " AND YEAR(v.data_visita) = '".$ano."' ";
        }
        $read = new Read();
        $read->FullRead("SELECT pergunta, resposta, COUNT(resposta) AS qtd FROM feedback INNER JOIN visitas AS v ON v.id = feedback.id_visita WHERE `pergunta` = :pergunta $ano GROUP BY resposta ORDER BY qtd DESC", "pergunta={$pergunta}");
        return $read;
    }

    public function getFeedbacksListPessoas($resposta, $ano): Read
    {
        if($ano != ''){
            $ano = " AND YEAR(v.data_visita) = '".$ano."' ";
        }
        $read = new Read();
        $read->FullRead("SELECT vi.nome, vi.email, vi.telefone FROM feedback AS f
                        INNER JOIN visitas_inscricoes AS vi ON vi.cpf = f.user_cpf
                        INNER JOIN visitas AS v ON v.id = feedback.id_visita
                        WHERE f.`resposta` = :resposta $ano", "resposta={$resposta}");
        return $read;
    }

}