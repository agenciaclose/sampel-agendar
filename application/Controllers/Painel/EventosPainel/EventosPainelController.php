<?php

namespace Agencia\Close\Controllers\Painel\EventosPainel;

use Agencia\Close\Helpers\Upload;
use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Painel\EventosPainel;

class EventosPainelController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);
        $this->permissions('eventos', '"view"');
        $this->render('painel/pages/eventos/index.twig', ['menu' => 'eventos']);
    }

    public function productAdd($params)
    {
        $this->setParams($params);
        $this->render('painel/pages/eventos/form.twig', []);
    }

    public function productEdit($params)
    {
        $this->setParams($params);

        $evento = new EventosPainel();
        $evento = $evento->getEventoID($params['id'])->getResult();
        $this->render('painel/pages/eventos/form.twig', ['evento' => $evento[0]]);
    }

}