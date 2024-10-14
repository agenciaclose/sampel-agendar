<?php

namespace Agencia\Close\Controllers\Site\Eventos;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\EventosModel;

class EventosController extends Controller
{
    public function eventos($params)
    {
        $this->setParams($params);
        $model = new EventosModel();
        $eventos = $model->listarEventos()->getResult();

        $i = 0;
        foreach($eventos as $evento){
            $totalEquipe = new EventosModel();
            $totalEquipe = $totalEquipe->listaEquipesEventos($evento['id'])->getResult();
            $eventos[$i]['total_equipe'] = count($totalEquipe);
            $i++;
        }

        $this->render('pages/eventos/eventos.twig', ['menu' => 'eventos', 'eventos' => $eventos]);
    }

    public function addEvento()
    {
        $this->render('pages/eventos/form.twig', ['menu' => 'eventos']);
    }

    public function editEvento($params)
    {
        $this->setParams($params);

        $model = new EventosModel();
        $eventos = $model->listarEventosID($params['id'])->getResult();
        if(isset($eventos)) {
            $evento = $eventos[0];
        }else{
            $evento = [];
        }

        $this->render('pages/eventos/form.twig', ['menu' => 'eventos', 'evento' => $evento]);
    }

    public function addEventoSave($params)
    {
        $this->setParams($params);
        $save = new EventosModel();
        $save = $save->addEventoSave($params);
        if($save){ echo $save; }
    }

    public function editEventoSave($params)
    {
        $this->setParams($params);
        $save = new EventosModel();
        $save = $save->editEventoSave($params);
        if($save){ echo '1'; }
    }

    public function equipeGerenciar($params)
    {
        $this->setParams($params);
        $model = new EventosModel();
        $eventos = $model->listarEventosID($params['id'])->getResult();
        if(isset($eventos)) {
            $evento = $eventos[0];

            $todasequipes = new EventosModel();
            $todasequipes = $todasequipes->listaEquipes()->getResult();

            $equipevisita = new EventosModel();
            $equipevisita = $equipevisita->listaEquipesEvento($params['id'])->getResult();

        }else{
            $evento = [];
        }
        $this->render('components/equipes/eventos.twig', ['evento' => $evento, 'todasequipes' => $todasequipes, 'equipevisita' => $equipevisita]);
    }

    public function listaEquipesSave($params)
    {
        $this->setParams($params);
        $save = new EventosModel();
        $save = $save->listaEquipesSave($params);
        if($save){
            echo '0';
        }else{
            echo '1';
        }
    }

    public function removeEquipe($params)
    {
        $this->setParams($params);
        $remove = new EventosModel();
        $remove = $remove->removeEquipe($params);
        if($remove){
            echo '0';
        }else{
            echo '1';
        }
    }

}