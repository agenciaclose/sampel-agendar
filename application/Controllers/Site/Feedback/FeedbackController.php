<?php

namespace Agencia\Close\Controllers\Site\Feedback;

use Agencia\Close\Controllers\Controller;
use Agencia\Close\Models\Site\Feedback;
use Agencia\Close\Models\User\User;

class FeedbackController extends Controller
{
    public function pergunta($params)
    {
        $this->setParams($params);
        $this->render('pages/feedback/feedback.twig', ['menu' => 'feedback']);
    }

}