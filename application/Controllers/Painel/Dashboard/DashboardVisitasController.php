<?php

namespace Agencia\Close\Controllers\Painel\Dashboard;

use DateTime; 
use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\VisitasPainel;

class DashboardVisitasController extends Controller
{
	
    public function index($params)
    {
        $this->setParams($params);

        $model = new VisitasPainel();
        $cidades = $model->listaDeCidades()->getResult();

        $this->render('painel/pages/dashboard/visitas.twig', [
            'menu' => 'dashboard', 
            'submenu' => 'visitas',
            'cidades' => $cidades
        ]);
    }
}