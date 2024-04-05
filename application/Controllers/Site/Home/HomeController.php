<?php

namespace Agencia\Close\Controllers\Site\Home;

use Agencia\Close\Models\User\User;
use Agencia\Close\Models\Site\Visitas;

use Agencia\Close\Models\Site\Feedback;
use Agencia\Close\Adapters\EmailAdapter;
use Agencia\Close\Models\Site\Palestras;
use Agencia\Close\Controllers\Controller;

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

            $palestras = new Palestras();
            $palestras = $palestras->lista($params)->getResult();

            $this->render('pages/home/home.twig', ['menu' => 'home', 'minhasvisitas' => $minhasvisitas, 'visitas' => $visitas, 'palestras' => $palestras]);

        }else{

            $this->render('pages/login/login.twig', []);

        }
    }

    //TESTES DOS TEMPLATES DE EMAILS
    public function emailEquipeTemplate($params)
    {
        $this->setParams($params);

        $equipesall = new Visitas();
        $equipesall = $equipesall->listaEquipesAll()->getResult();

        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['visita_id'])->getResult()[0];

        $inscritos = new Visitas();
        $inscritos = $inscritos->listarInscricoesTotal($params['visita_id'])->getResult()[0];

        $comparecimentos = new Visitas();
        $comparecimentos = $comparecimentos->listarComparecimentosTotal($params['visita_id'])->getResult()[0];

        $perguntas = new Feedback();
        $perguntas = $perguntas->getFeedbacksPerguntas()->getResult();

        $i = 0;
        foreach ($perguntas as $pergunta) {
            $feedbacks = new Feedback();
            $perguntas[$i]['estatisticas'] = $feedbacks->getFeedbacksList($params['visita_id'], $pergunta['pergunta'])->getResult();
            $i++;
        }

        $this->render('components/email/emailEstatisticas.twig', ['visita' => $visita, 'inscritos' => $inscritos, 'comparecimentos' => $comparecimentos]);
    }

    //ENVIAR EMAIL PARA EQUIPE
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

        $dataVisita = new \DateTime($visita['data_visita']);
        $dataFormatada = $dataVisita->format('d/m/Y');

        foreach($equipesall as $lista){

            $email = new EmailAdapter();
            $email->setSubject('Visita na Fabrica: '. $dataFormatada);
    
            $email->setBody('components/email/emailEquipe.twig', $data);
            $email->addAddress($lista['email']);
            $email->send('Email enviado para a Equipe');

        }
                
        $sendUpdate = new Visitas();
        $sendUpdate = $sendUpdate->sendUpdate($params['visita_id']);
        $email->getResult();
    }

    //ENVIAR EMAIL DE ESTATISTICA PARA EQUIPE
    public function sendEmailEstatisticas($params)
    {
        $this->setParams($params);

        $equipesall = new Visitas();
        $equipesall = $equipesall->listaEquipesAll()->getResult();

        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['visita_id'])->getResult()[0];

        $inscritos = new Visitas();
        $inscritos = $inscritos->listarInscricoesTotal($params['visita_id'])->getResult()[0];

        $comparecimentos = new Visitas();
        $comparecimentos = $comparecimentos->listarComparecimentosTotal($params['visita_id'])->getResult()[0];

        $perguntas = new Feedback();
        $perguntas = $perguntas->getFeedbacksPerguntas()->getResult();

        $i = 0;
        foreach ($perguntas as $pergunta) {
            $feedbacks = new Feedback();
            $perguntas[$i]['estatisticas'] = $feedbacks->getFeedbacksList($params['visita_id'], $pergunta['pergunta'])->getResult();
            $i++;
        }

        $dataVisita = new \DateTime($visita['data_visita']);
        $dataFormatada = $dataVisita->format('d/m/Y');

        $data = [
            'inscritos' => $inscritos,
            'comparecimentos' => $comparecimentos,
            'perguntas' => $perguntas,
            'visita' => $visita,
        ];

        foreach($equipesall as $lista){

            $email = new EmailAdapter();
            $email->setSubject('Estatisticas da visita: '. $dataFormatada);
    
            $email->setBody('components/email/emailEstatisticas.twig', $data);
            $email->addAddress($lista['email']);
            $email->send('Email enviado com sucesso');

        }
        $email->getResult();
    }

    //ENVIAR EMAIL DE ESTATISTICA PARA EQUIPE
    public function sendEmailCertificado($params)
    {
        $this->setParams($params);

        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['visita_id'])->getResult()[0];

        $inscritos = new Visitas();
        $inscritos = $inscritos->listarInscricoes($params['visita_id'])->getResult();

        $dataVisita = new \DateTime($visita['data_visita']);
        $dataFormatada = $dataVisita->format('d/m/Y');

        foreach($inscritos as $lista){

            $data = [
                'usuario' => $lista,
                'visita' => $visita,
            ];

            $email = new EmailAdapter();
            $email->setSubject('Seu certificado Sampel: '. $dataFormatada);
    
            $email->setBody('components/email/emailAlertCertificado.twig', $data);
            $email->addAddress($lista['email']);
            $email->send('Email enviado com sucesso');

        }

        $email->getResult();
        
    }


    //ENVIAR EMAIL DE NOVO EVENTO PARA EQUIPE
    public function sendEmailNovoEvento($params)
    {
        $this->setParams($params);

        $visita = new Visitas();
        $visita = $visita->listarVisitaID($params['visita_id'])->getResult()[0];

        $equipe = new Visitas();
        $equipe = $equipe->listaEquipes()->getResult();

        $dataVisita = new \DateTime($visita['data_visita']);
        $dataFormatada = $dataVisita->format('d/m/Y');

        foreach($equipe as $lista){

            $data = [
                'visita' => $visita,
            ];

            $email = new EmailAdapter();
            $email->setSubject('Novo evento criado: '. $dataFormatada);
    
            $email->setBody('components/email/emailNovoEvento.twig', $data);
            $email->addAddress($lista['email']);
            //$email->addAddress('rl.cold.dev@gmail.com');
            $email->send('Email enviado com sucesso');

        }

        $email->getResult();
        
    }

}