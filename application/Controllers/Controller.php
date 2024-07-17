<?php

namespace Agencia\Close\Controllers;

use CoffeeCode\Router\Router;
use Agencia\Close\Models\Permissions;
use Agencia\Close\Adapters\TemplateAdapter;
use Agencia\Close\Helpers\Device\CheckDevice;
use Agencia\Close\Middleware\MiddlewareCollection;

class Controller
{
    protected TemplateAdapter $template;
    private array $dataDefault = [];
    protected Router $router;
    protected array $params;

    public function __construct($router)
    {
        $this->middleware();
        $this->router = $router;
        $this->template = new TemplateAdapter();
    }

    private function middleware()
    {
        $middlewares = new MiddlewareCollection();
        $middlewares->default();
        $middlewares->run();
    }

    private function isMobileDevice(): bool
    {
        $checkDevice = new CheckDevice();
        return $checkDevice->isMobileDevice();
    }

    protected function render(string $link, array $arrayData = [])
    {
        $arrayDataWithDefault = $this->mergeWithDefault($arrayData);
        echo $this->template->render($link, $arrayDataWithDefault);
    }

    protected function responseJson($response){
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }


    private function mergeWithDefault($arrayToMerge): array
    {
        $this->setDefault();
        $this->getPermissionsUser();
        return array_merge($this->dataDefault, $arrayToMerge);
    }

    protected function setParams(array $params)
    {
        $this->params = $params;
        $this->setDefault();
    }

    protected function getCurrentUrl(): string
    {
        return  parse_url((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", PHP_URL_PATH);
    }

    private function setDefault()
    {
        $this->dataDefault['mobile'] = $this->isMobileDevice();
        $this->dataDefault['currentUrl'] = $this->getCurrentUrl();
        $this->dataDefault['session'] = $_SESSION;
        $this->dataDefault['cookie'] = $_COOKIE;
        $this->dataDefault['get'] = $_GET;        
    }

    protected function getDefault(): array
    {
        return $this->dataDefault;
    }

    protected function redirectUrl(string $url)
    {
        header('Location: '. $url);
    }

    public function permissions($permission, $action)
    {
        if(isset($_SESSION['sampel_user_id']) && $_SESSION['sampel_user_id'] != 1){
            $permissions = new Permissions();
            $permissions = $permissions->getPermissions($permission, $action, $_SESSION['sampel_user_id'])->getResult();
            if(!$permissions){
                $this->render('painel/pages/error/no-permition.twig');
                die();
            }
        }
    }

    public function getPermissionsUser()
    {
        if(isset($_SESSION['sampel_user_id']) && $_SESSION['sampel_user_id'] != 1){
            $permissions = new Permissions();
            $permissions = $permissions->getPermissionsUser($_SESSION['sampel_user_id']);
            if ($permissions->getResult()) {
                $this->dataDefault['permissions'] = $this->listPermissions($permissions->getResult());
            }
        }
    }

    public function listPermissions($permissions) {
        $combinedPermissions = [];

        // Função para combinar permissões
        function combinePermissions($currentPermissions, &$combinedPermissions) {
            foreach ($currentPermissions as $key => $actions) {
                if (!isset($combinedPermissions[$key])) {
                    $combinedPermissions[$key] = [];
                }
                foreach ($actions as $action) {
                    if (!in_array($action, $combinedPermissions[$key])) {
                        $combinedPermissions[$key][] = $action;
                    }
                }
            }
        }

        foreach ($permissions as $index => $permissionData) {
            if (isset($permissionData['permissions']) && is_string($permissionData['permissions'])) {
                $permissionArray = json_decode($permissionData['permissions'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    combinePermissions($permissionArray, $combinedPermissions);
                }
            }
        }

        return $combinedPermissions;
    }

}