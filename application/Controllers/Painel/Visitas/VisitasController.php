<?php

namespace Agencia\Close\Controllers\Painel\Visitas;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\Upload;
use Agencia\Close\Models\Painel\CategoriasVisitasPainel;
use Agencia\Close\Models\Painel\VisitasPainel;
use Agencia\Close\Models\Painel\InscricaoPainel;

class VisitasController extends Controller
{

    public function index($params)
    {
        $this->setParams($params);

        $visitas = new VisitasPainel();
        $visitas = $visitas->getVisitasList()->getResult();
        $this->render('painel/pages/visitas/lista.twig', ['menu' => 'visitas', 'visitas' => $visitas]);
    }

    public function view($params)
    {
        $this->setParams($params);

        $visita = new VisitasPainel();
        $visita = $visita->getVisitaID($params['id'])->getResult()[0];

        $listas = new VisitasPainel();
        $listas = $listas->listarInscricoes($params['id'])->getResult();

        $this->render('painel/pages/visitas/view.twig', ['menu' => 'visitas', 'visita' => $visita, 'listas' => $listas]);
    }

    public function aprovar($params)
    {
        $this->setParams($params);
        $visita = new VisitasPainel();
        $visita = $visita->visitaStatus($params['id'], 'Aprovado');
    }

    public function reprovar($params)
    {
        $this->setParams($params);
        $visita = new VisitasPainel();
        $visita = $visita->visitaStatus($params['id'], 'Recusado');
    }

    public function excluir($params)
    {
        $this->setParams($params);
        $visita = new VisitasPainel();
        $visita = $visita->visitaExcluir($params['id']);
    }

    public function criar($params)
    {
        $this->setParams($params);
        $this->render('painel/pages/visitas/form.twig', ['menu' => 'visitas']);
    }

    public function editar($params)
    {
        $this->setParams($params);
        
        $visita = new VisitasPainel();
        $visita = $visita->getVisitaID($params['id'])->getResult()[0];

        $this->render('painel/pages/visitas/form.twig', ['menu' => 'visitas', 'visita' => $visita,]);
    }

    public function inscricao($params)
    {
        $this->setParams($params);
        $visita = new InscricaoPainel();
        $visita = $visita->listarVisitaID($params['id'])->getResult()[0];

        $inscricao = '';
        
        $this->render('pages/visitas/inscricao.twig', ['menu' => 'visitas', 'visita' => $visita, 'inscricao' => $inscricao]);
    }

}