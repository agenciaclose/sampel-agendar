<?php

namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Conn;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Conn\Read;
use Agencia\Close\Models\Model;
use mysql_xdevapi\Result;

class VisitasPainel extends Model
{
	private string $table = 'produtos';

    public function getVisitasList(): Read
    {   
        $filtro_ano = '';
        if(isset($_GET['ano_visitas'])){
            $filtro_ano = "WHERE YEAR(v.data_visita) = '".$_GET['ano_visitas']."'";
        }
        
        $read = new Read();
        $read->FullRead("SELECT v.*, u.*, v.id AS visita_id,
                        (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id) AS total_inscricao,
                        (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id AND presenca = 'Sim') AS presencas
                    FROM visitas AS v
                    INNER JOIN usuarios AS u ON u.id = v.id_empresa
                    $filtro_ano
                    GROUP BY v.id
                    ORDER BY
                        CASE WHEN v.status_visita <> 'Concluido' THEN 0 ELSE 1 END,
                        CASE WHEN v.status_visita <> 'Concluido' THEN ABS(DATEDIFF(v.data_visita, CURRENT_DATE)) ELSE NULL END,
                        v.data_visita DESC");
        return $read;
    }

    public function getVisitasListAprovadas(): Read
    {      
        $read = new Read();
        $read->FullRead("SELECT v.*, u.*, v.id AS visita_id, (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id) AS total_inscricao,
        (SELECT COUNT(id) FROM visitas_inscricoes WHERE id_visita = v.id AND presenca = 'Sim') AS presencas
        FROM visitas AS v
        INNER JOIN usuarios AS u ON u.id = v.id_empresa 
        WHERE v.status_visita not in ('Cancelado', 'Recusado')
        ORDER BY v.id DESC");
        return $read;
    }

    public function getEstados(): read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM visitas_limites ORDER BY estado ASC");
        return $read;
    }

    public function getVisitaID($id_visita): read
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, u.*, v.id AS visita_id
                        FROM visitas AS v
                        INNER JOIN usuarios AS u ON u.id = v.id_empresa
                        WHERE v.id = :id_visita ORDER BY v.id DESC", "id_visita={$id_visita}");
        return $read;
    }

    public function listarInscricoes($id_visita): read
    {
        $read = new Read();
        if(!empty($_GET['setor'])){
            $setor = "AND setor = '".$_GET['setor']."'";
        }else{
            $setor = "";
        }
        $read->FullRead("SELECT * FROM visitas_inscricoes WHERE id_visita = :id_visita  $setor ORDER BY `nome` ASC", "id_visita={$id_visita}");
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

    public function visitaStatus($id_visita, $action): read
    {
        $read = new Read();
        $read->FullRead("UPDATE `visitas` SET `status_visita` = :action WHERE `id` = :id_visita", "id_visita={$id_visita}&action={$action}");
        return $read;
    }

    public function visitaExcluir($id_visita): read
    {
        $read = new Read();
        $read->FullRead("DELETE FROM `visitas` WHERE `id` = :id_visita", "id_visita={$id_visita}");
        return $read;
    }

    public function getInscricaoSave($params)
    {
        $update = new Update();
        unset($params['setor_outros']);
        $params['cpf'] = $this->clearCPF($params['cpf']);
        $update->ExeUpdate('visitas_inscricoes', $params, ' WHERE codigo = :codigo', "codigo={$params['codigo']}");
        return $update;
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

    public function listaDeCidades(): read
    {   
        $ano = '';
        if(isset($_GET['ano'])){
            $ano = " AND YEAR(data) = '".$_GET['ano']."' ";
        }
        $read = new Read();
        $read->FullRead("SELECT cidade, estado FROM visitas_inscricoes WHERE cidade <> '' $ano");
        return $read;
    }
   
}