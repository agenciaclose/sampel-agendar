<?php

namespace Agencia\Close\Controllers\Admin\Usuarios;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Admin\Usuarios;

class UsuariosController extends Controller
{
    public function list($params)
    {
        $this->setParams($params);

        $usuarios = new Usuarios();
        $usuarios = $usuarios->getUsuarios()->getResult();

        $this->render('painel/admin/usuarios/lista.twig', ['menu' => 'usuarios', 'usuarios' => $usuarios]);
    }

    public function status($params)
    {
        $this->setParams($params);

        $status = new Usuarios();
        $status->getStatusUpdate($this->params['status'], $this->params['user']);

    }

    public function editar($params)
    {
        $this->setParams($params);

        $usuario = new Usuarios();
        $usuario = $usuario->getUsuarioID($this->params['id'])->getResult()[0];

        $this->render('painel/admin/usuarios/editar.twig', ['menu' => 'usuarios', 'usuario' => $usuario]);
    }

    public function manuaisAdmin($params)
    {
        $this->setParams($params);
        
        $manuais = new Usuarios();
        $manuais = $manuais->getManuais()->getResult();

        $this->render('painel/admin/manuais/index.twig', ['menu' => 'manuais', 'manuais' => $manuais]);
    }

}