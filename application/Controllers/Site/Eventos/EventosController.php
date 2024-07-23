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
}