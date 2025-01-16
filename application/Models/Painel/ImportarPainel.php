<?php

namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Models\Model;

class ImportarPainel extends Model
{
    public function salvar($params)
    {
        unset($params['pessoas']);
        unset($params['data_palestra']);
        unset($params['title']);

        $params['presenca'] = 'Sim';

        $create = new Create();
        $create->ExeCreate('palestras_participantes', $params);
        return $create;
    }

    public function checkPalestras($title, $data_palestra): Read
    {
        $read = new Read();
        $read->FullRead("SELECT * FROM palestras WHERE title = :title AND data_palestra = :data_palestra", "title={$title}&data_palestra={$data_palestra}");
        return $read;
    }

    public function savePalestras($params)
    {
        
        $params['palestrante_nome'] = $params['nome'];
        $params['inscricoes'] = $params['pessoas'];
        

        unset($params['pessoas']);
        unset($params['nome']);

        $create = new Create();
        $create->ExeCreate('palestras', $params);
        
        $read = new Read();
        $read->FullRead("SELECT * FROM palestras ORDER BY id DESC LIMIT 1");
        return $read;
    }
}