<?php

    namespace Agencia\Close\Models\Site;

    use Agencia\Close\Conn\Read;
    use Agencia\Close\Models\Model;

    class MinhasInscricoes extends Model
    {

        public function getLista($cpf): read
        {
            $read = new Read();
            $read->FullRead("SELECT vi.nome, vi.id, vi.id_visita AS id_evento, vi.cpf, v.title, v.data_visita AS data_inicio, NULL AS data_fim, v.horario_visita, v.tipo, 'visita' AS origem, vi.codigo AS codigo, vi.presenca
                        FROM visitas_inscricoes AS vi
                        INNER JOIN visitas AS v ON v.id = vi.id_visita 
                        WHERE vi.cpf = :cpf
                        UNION ALL
                        SELECT pp.nome, pp.id, pp.id_palestra AS id_evento, pp.cpf, p.title, p.data_palestra AS data_inicio, p.data_fim, NULL AS horario_visita, NULL AS tipo, 'palestras' AS origem, pp.codigo AS codigo, pp.presenca
                        FROM palestras_participantes AS pp
                        INNER JOIN palestras AS p ON p.id = pp.id_palestra 
                        WHERE pp.cpf = :cpf", "cpf={$cpf}");
            return $read;
        }
    }