<?php

namespace core;

use app\models\Permissoes;
use app\models\CrudUsuarios;

class Router
{

private $controller = 'Site';
private $method = 'login';
private $param = [];

// Rotas liberadas sem login: mantem login/autenticacao/logout fora do bloqueio global.
private $publicRoutes = [
    'Site' => ['login', 'autenticar', 'logout']
];

    public function __construct()
    {
       $router = $this->url();
       
       if(isset($router[0]) && preg_match('/^[a-zA-Z0-9_]+$/', $router[0]) && file_exists('app/controllers/' .ucfirst($router[0]) .'.php')):
        $this->controller = $router[0];
        unset($router[0]);

    endif;
        
        $class = "\\app\\controllers\\" . ucfirst($this->controller);
        $object = new $class;

        if(isset($router[1]) && preg_match('/^[a-zA-Z0-9_]+$/', $router[1]) && method_exists($class, $router[1])):
          $this->method = $router[1];
          unset($router[1]);

        endif;

        $this->enforceAuthentication();

        $this->param = $router ? array_values($router): [];

        call_user_func_array([$object, $this->method], $this->param);

    }

    private function url()
{
    $router = filter_input(INPUT_GET, 'router', FILTER_SANITIZE_URL);

    if (!$router) {
        return [];
    }

    return array_values(array_filter(explode("/", trim($router, "/")), 'strlen'));
}

// Protecao central de acesso: toda rota fora da lista publica exige sessao ativa.
private function enforceAuthentication()
{
    $controller = ucfirst($this->controller);

    if ($this->isPublicRoute($controller, $this->method)) {
        return;
    }

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (!isset($_SESSION['id'], $_SESSION['nivel'])) {
        header('Location: ?router=Site/login');
        exit();
    }

    // Sincronizacao de sessao: garante que mudancas de perfil no cadastro tenham efeito imediato.
    $usuarios = new CrudUsuarios();
    $dadosUsuario = $usuarios->recuperaDados();
    if (isset($dadosUsuario[0])) {
        $_SESSION['nome_usu'] = $dadosUsuario[0]['nome_usu'];
        $_SESSION['email'] = $dadosUsuario[0]['email'];
        $_SESSION['nivel'] = Permissoes::normalizarNivel($dadosUsuario[0]['nivel']);

        // Administrador principal: e-mail definido pelo proprietario sempre recebe acesso total.
        if (Permissoes::emailAdministradorTotal($dadosUsuario[0]['email'])) {
            $_SESSION['nivel'] = 'Administrador';
        }
    }

    // Permissao centralizada: considera perfil e liberacoes manuais da tabela acessos.
    if (!Permissoes::canAccessUser($_SESSION['nivel'], $controller, $this->method, $_SESSION['id'])) {
        header('Location: ?router=Site/home');
        exit();
    }
}

private function isPublicRoute($controller, $method)
{
    return isset($this->publicRoutes[$controller]) && in_array($method, $this->publicRoutes[$controller], true);
}
  
}
