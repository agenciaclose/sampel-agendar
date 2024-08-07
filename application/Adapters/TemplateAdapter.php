<?php

namespace Agencia\Close\Adapters;

use Agencia\Close\Adapters\Twig\DataGoogle;
use Agencia\Close\Adapters\Twig\AbreviarNome;
use Agencia\Close\Adapters\Twig\PedidoStatus;
use Agencia\Close\Adapters\Twig\PedidoStatusColor;
use Agencia\Close\Adapters\Twig\DayTranslate;
use Agencia\Close\Adapters\Twig\MonthTranslate;
use Agencia\Close\Adapters\Twig\FilterHash;
use Agencia\Close\Adapters\Twig\PriceCheck;
use Agencia\Close\Adapters\Twig\UserType;
use Agencia\Close\Adapters\Twig\UserStatus;
use Agencia\Close\Adapters\Twig\UserCPF;
use Agencia\Close\Helpers\String\Strings;
use Agencia\Close\Adapters\Twig\JsonDecode;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TemplateAdapter
{
    private $twig;
    
    public function __construct()
    {
        $loader = new FilesystemLoader('view');
        $this->twig = new Environment($loader, [
            'cache' => false,
        ]);
        $this->twig->addExtension(new FilterHash());
        $this->twig->addExtension(new MonthTranslate());
        $this->twig->addExtension(new DayTranslate());
        $this->twig->addExtension(new PedidoStatus());
        $this->twig->addExtension(new PedidoStatusColor());
        $this->twig->addExtension(new PriceCheck());
        $this->twig->addExtension(new UserType());
        $this->twig->addExtension(new UserStatus());
        $this->twig->addExtension(new UserCPF());
        $this->twig->addExtension(new AbreviarNome());
        $this->twig->addExtension(new DataGoogle());
        $this->twig->addExtension(new JsonDecode());
        $this->globals();

        return $this->twig;
    }

    public function render($view, array $data = []): string
    {
        return $this->twig->render($view, $data);
    }

    private function globals()
    {
        $this->twig->addGlobal('DOMAIN', DOMAIN);
        $this->twig->addGlobal('PATH', PATH);
        $this->twig->addGlobal('NAME', NAME);
        $this->twig->addGlobal('PRODUCTION', PRODUCTION);
        $this->twig->addGlobal('_session', $_SESSION);
        $this->twig->addGlobal('_post', $_POST);
        $this->twig->addGlobal('_get', $_GET);
        $this->twig->addGlobal('_cookie', $_COOKIE);
    }
}