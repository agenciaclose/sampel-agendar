<?php
namespace Agencia\Close\Models\Painel;

use Agencia\Close\Conn\Read;
use Agencia\Close\Conn\Create;
use Agencia\Close\Conn\Update;
use Agencia\Close\Conn\Delete;
use Agencia\Close\Models\Model;

class ProdutosVisibilidade extends Model
{
    public function getAll()
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, GROUP_CONCAT(r.role) as cargos_nomes, GROUP_CONCAT(r.id) as cargos_ids 
        FROM visibilidades v
        LEFT JOIN visibilidades_cargos vc ON vc.id_visibilidade = v.id
        LEFT JOIN roles r ON r.id = vc.id_cargo
        GROUP BY v.id
        ORDER BY v.nome ASC");
        return $read;
    }

    public function getById($id)
    {
        $read = new Read();
        $read->FullRead("SELECT v.*, GROUP_CONCAT(r.role) as cargos_nomes, GROUP_CONCAT(r.id) as cargos_ids 
        FROM visibilidades v
        LEFT JOIN visibilidades_cargos vc ON vc.id_visibilidade = v.id
        LEFT JOIN roles r ON r.id = vc.id_cargo
        WHERE v.id = :id
        GROUP BY v.id", "id={$id}");
        return $read;
    }

    public function create($params)
    {
        $cargos = isset($params['cargos']) ? $params['cargos'] : [];
        unset($params['cargos']);
        
        $create = new Create();
        $create->ExeCreate('visibilidades', $params);
        $id_visibilidade = $create->getResult();
        
        if (!empty($cargos)) {
            $this->saveCargos($id_visibilidade, $cargos);
        }
        
        return $create;
    }

    public function update($id, $params)
    {
        $cargos = isset($params['cargos']) ? $params['cargos'] : [];
        unset($params['cargos']);
        
        $update = new Update();
        $update->ExeUpdate('visibilidades', $params, 'WHERE id = :id', "id={$id}");
        
        // Atualizar cargos
        $this->deleteCargos($id);
        if (!empty($cargos)) {
            $this->saveCargos($id, $cargos);
        }
        
        return $update;
    }

    public function delete($id)
    {
        // Deletar relacionamentos primeiro (cascade)
        $this->deleteCargos($id);
        
        $delete = new Delete();
        $delete->ExeDelete('visibilidades', 'WHERE id = :id', "id={$id}");
        return $delete;
    }

    private function saveCargos($id_visibilidade, $cargos)
    {
        $create = new Create();
        foreach ($cargos as $id_cargo) {
            if ($id_cargo > 0) {
                $create->ExeCreate('visibilidades_cargos', [
                    'id_visibilidade' => $id_visibilidade,
                    'id_cargo' => $id_cargo
                ]);
            }
        }
    }

    private function deleteCargos($id_visibilidade)
    {
        $delete = new Delete();
        $delete->ExeDelete('visibilidades_cargos', 'WHERE id_visibilidade = :id_visibilidade', "id_visibilidade={$id_visibilidade}");
    }

    public function getCargosByVisibilidade($id_visibilidade)
    {
        $read = new Read();
        $read->FullRead("SELECT r.id, r.role FROM visibilidades_cargos vc 
        JOIN roles r ON r.id = vc.id_cargo 
        WHERE vc.id_visibilidade = :id_visibilidade", "id_visibilidade={$id_visibilidade}");
        return $read;
    }
} 