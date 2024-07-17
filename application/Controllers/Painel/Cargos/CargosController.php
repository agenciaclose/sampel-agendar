<?php

namespace Agencia\Close\Controllers\Painel\Cargos;

use Agencia\Close\Models\User\User;
use Agencia\Close\Models\Permissions;
use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\CargosPainel;

class CargosController extends Controller
{

    public function lista($params)
    {
        $this->setParams($params);
        $this->permissions('cargos', '"view"');

        $cargos = new CargosPainel();
        $cargos = $cargos->getCargosList()->getResult();

        $this->render('painel/pages/cargos/lista.twig', ['menu' => 'cargos', 'cargos' => $cargos]);
    }

    public function addCargo($params)
    {
        $this->render('painel/pages/cargos/form.twig', []);
    }

    public function editCargo($params)
    {
        $this->setParams($params);

        $cargo = new CargosPainel();
        $cargo = $cargo->getCargoID($params['id'])->getResult();
        if($cargo){ $cargo = $cargo[0]; }
        $this->render('painel/pages/cargos/form.twig', ['cargo' => $cargo]);
    }
    
    public function addCargoSave($params)
    {
        $this->setParams($params);
        $permission = json_encode($params['permission']);
        $save = new CargosPainel();
        $save = $save->addRoleSave($params['role'], $permission);
        if($save){ echo '1'; }
    }

    public function editCargoSave($params)
    {
        $this->setParams($params);
        $permission = json_encode($params['permission']);
        $save = new CargosPainel();
        $save = $save->editRoleSave($params['id'], $params['role'], $permission);
        if($save){ echo '1'; }
    }

    public function deleteCargo($params)
    {
        $this->setParams($params);
        $delete = new CargosPainel();
        $delete = $delete->deleteRole($params['id']);
    }

}