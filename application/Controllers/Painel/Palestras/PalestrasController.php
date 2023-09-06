<?php

namespace Agencia\Close\Controllers\Painel\Palestras;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Helpers\Upload;
use Agencia\Close\Models\Painel\PalestrasPainel;

class PalestrasController extends Controller
{

    public function index($params)
    {
        $this->setParams($params);

        $palestras = new PalestrasPainel();
        $palestras = $palestras->getPalestrasList()->getResult();
        $this->render('painel/pages/palestras/lista.twig', ['menu' => 'palestras', 'palestras' => $palestras]);
    }

    public function view($params)
    {
        $this->setParams($params);

        $palestra = new PalestrasPainel(); 
        $palestra = $palestra->getPalestraID($params['id'])->getResult()[0];

        $listas = new PalestrasPainel();
        $listas = $listas->listarInscricoes($params['id'])->getResult();

        $this->render('painel/pages/palestras/view.twig', ['menu' => 'palestras', 'palestra' => $palestra, 'listas' => $listas]);
    }

    public function viewEdit($params)
    {
        $this->setParams($params);

        $palestra = new PalestrasPainel(); 
        $palestra = $palestra->getPalestraID($params['id'])->getResult()[0];

        $editar = new PalestrasPainel(); 
        $editar = $editar->getParticipanteID($params['id_inscricao'])->getResult()[0];

        $listas = new PalestrasPainel();
        $listas = $listas->listarInscricoes($params['id'])->getResult();

        $this->render('painel/pages/palestras/view.twig', ['menu' => 'palestras', 'palestra' => $palestra, 'listas' => $listas, 'editar' => $editar]);
    }

    public function criar($params)
    {
        $this->setParams($params);
        $this->render('painel/pages/palestras/form.twig', ['menu' => 'palestras']);
    }

    public function editar($params)
    {
        $this->setParams($params);
        
        $palestra = new PalestrasPainel();
        $palestra = $palestra->getPalestraID($params['id'])->getResult()[0];

        $this->render('painel/pages/palestras/form.twig', ['menu' => 'palestras', 'palestra' => $palestra,]);
    }

    public function SaveCadastro($params)
    {
        $this->setParams($params);
        $save = new PalestrasPainel();
        $save = $save->saveCadastro($this->params);
        if ($save) {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function SaveEditar($params)
    {
        $this->setParams($params);
        $editar = new PalestrasPainel();
        $editar = $editar->saveEditar($this->params);
        if ($editar) {
            echo '1';
        } else {
            echo '0';
        }
    }


    public function SaveCadastroParticipante($params)
    {
        $this->setParams($params);
        $save = new PalestrasPainel();
        $save = $save->saveCadastroParticipante($this->params);
        if ($save) {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function SaveEditarParticipante($params)
    {
        $this->setParams($params);
        $editar = new PalestrasPainel();
        $editar = $editar->saveEditarParticipante($this->params);
        if ($editar) {
            echo '1';
        } else {
            echo '0';
        }
    }

    public function excluirParticipante($params)
    {
        $this->setParams($params);
        $excluir = new PalestrasPainel();
        $excluir = $excluir->saveExcluirParticipante($this->params);
        if ($excluir) {
            echo '1';
        } else {
            echo '0';
        }
    }
}