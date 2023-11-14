<?php

namespace Agencia\Close\Controllers\Site\Home;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\Visitas;

use Agencia\Close\Adapters\EmailAdapter;
use Agencia\Close\Models\User\User;

class HomeController extends Controller
{
    public function index($params)
    {
        $this->setParams($params);

        if(!empty($_SESSION['sampel_user_id'])){

            $visitas = new Visitas();
            $visitas = $visitas->listarVisitasOutros()->getResult();

            $i = 0;
            foreach($visitas as $visita){
                $todasEquipes = new Visitas();
                $todasEquipes = $todasEquipes->listaEquipesVisita($visita['visita_id'])->getResult();
                $visitas[$i]['equipevisita'] = $todasEquipes;
                $i++;
            }

            $minhasvisitas = new Visitas();
            $minhasvisitas = $minhasvisitas->listarVisitas('3')->getResult();
            $i = 0;
            foreach($minhasvisitas as $minhas){
                $todasEquipes = new Visitas();
                $todasEquipes = $todasEquipes->listaEquipesVisita($minhas['visita_id'])->getResult();
                $minhasvisitas[$i]['equipevisita'] = $todasEquipes;
                $i++;
            }

            $this->render('pages/home/home.twig', ['menu' => 'home', 'minhasvisitas' => $minhasvisitas, 'visitas' => $visitas]);

        }else{

            $this->render('pages/login/login.twig', []);

        }


    }

    public function emailEquipeTemplate($params)
    {
        $this->setParams($params);

        $equipesall = new Visitas();
        $equipesall = $equipesall->listaEquipesAll()->getResult();

        $equipes = new Visitas();
        $equipes = $equipes->listaEquipesVisita($params['visita_id'])->getResult();

        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['visita_id'])->getResult()[0];

        $emails = '';
        foreach($equipesall as $lista){
            $emails .= $lista['email'].',';
        }
        $emails = rtrim($emails, ',');

        //ENVIO DE EMAIL

            $data = [
                'equipes' => $equipes,
                'visita' => $visita,
            ];
            
            // $email = new EmailAdapter();
            // $email->setSubject('Informações sobre a Visita: ');

            // $email->setBody('components/email/emailEquipe.twig', $data);
            // //$email->addAddress($emails);
            // $email->addAddress($emails);
            // $email->send('Email enviado para a Equipe');
            // $email->getResult();

        //
        $this->render('components/email/emailEquipe.twig', ['equipes' => $equipes, 'visita' => $visita]);
    }

    public function sendEmailEquipe($params)
    {
        $this->setParams($params);

        $equipesall = new Visitas();
        $equipesall = $equipesall->listaEquipesAll()->getResult();

        $equipes = new Visitas();
        $equipes = $equipes->listaEquipesVisita($params['visita_id'])->getResult();

        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['visita_id'])->getResult()[0];
        
        $data = [
            'equipes' => $equipes,
            'visita' => $visita,
        ];

        foreach($equipesall as $lista){

            $email = new EmailAdapter();
            $email->setSubject('Informações sobre a Visita: '.$visita['title'].' ');
    
            $email->setBody('components/email/emailEquipe.twig', $data);
            $email->addAddress($lista['email']);
            $email->send('Email enviado para a Equipe');

        }
                
        $sendUpdate = new Visitas();
        $sendUpdate = $sendUpdate->sendUpdate($params['visita_id']);

        $email->getResult();

    }

}