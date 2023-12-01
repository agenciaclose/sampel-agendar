<?php

    namespace Agencia\Close\Models\Site;

    use Agencia\Close\Conn\Read;
    use Agencia\Close\Models\Model;

    class MinhasInscricoes extends Model
    {

        public function checkInscricoes($cpf): read
        {
            $read = new Read();
            $read->FullRead("SELECT * FROM `visitas_inscricoes` WHERE cpf = :cpf", "cpf={$cpf}");
            return $read;
        }

        public function getLista($cpf): read
        {
            $read = new Read();
            $read->FullRead("SELECT vi.*, v.title, v.data_visita, v.horario_visita FROM `visitas_inscricoes` AS vi
            INNER JOIN visitas AS v ON v.id = vi.id_visita WHERE vi.cpf = :cpf", "cpf={$cpf}");
            return $read;
        }
    }