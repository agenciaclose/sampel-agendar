<?php

namespace Agencia\Close\Controllers\Painel\Equipes;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\EquipesPainel;

class EquipesController extends Controller
{

    public function equipes($params)
    {
        $this->setParams($params);

        $equipes = new EquipesPainel();
        $equipes = $equipes->getEquipesList()->getResult();

        $this->render('painel/pages/equipes/lista.twig', ['menu' => 'equipes', 'equipes' => $equipes]);
    }

    public function cadastro()
    {
        $this->render('painel/pages/equipes/cadastro.twig', ['menu' => 'equipes']);
    }

    public function editar($params)
    {
        $this->setParams($params);

        $editar = new EquipesPainel();
        $editar = $editar->getEquipesEditar($params['id'])->getResult()[0];

        $this->render('painel/pages/equipes/cadastro.twig', ['menu' => 'equipes', 'editar' => $editar]);
    }

}