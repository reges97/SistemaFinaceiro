<?php

namespace app\controllers;

use app\models\CrudChart;
use app\models\CrudUsuarios;
use app\models\Permissoes;



class Site extends CrudUsuarios
{

    public function login()
    {
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/confmenu.php';

    }

    public function home()
    {
        require_once __DIR__ . '/../views/verificar.php';

        require_once __DIR__ . '/../views/menu2.php';
        //require_once __DIR__ . '/../views/confiBody.php';
        require_once __DIR__ . '/../views/home.php';
        require_once __DIR__ . '/../views/footer.php';
       
        

    }

    public function clientes()
{

    require_once __DIR__ . '/../views/confiBody.php';
    require_once __DIR__ . '/../views/painel-adm/clientes.php';
    
    
}
    public function body()
    {
      
        

        require_once __DIR__ . '/../views/confiBody.php';

    }

    public function autenticar()

    {
         $this->logar();

        require_once __DIR__ . '/../views/autenticar.php';
        
   
    }

    public function logout()
    {

        require_once __DIR__ . '/../views/logout.php';
}

public function verificar()
{
    require_once __DIR__ . '/../views/verificar.php';

}

public function homePainel()
{
    require_once __DIR__ . '/../views/verificar.php';
    if (Permissoes::normalizarNivel($_SESSION['nivel'] ?? '') !== 'Administrador') {
        // Painel administrativo: usuarios financeiros sao redirecionados para o painel proprio do perfil.
        header('Location: ?router=Site/painelFinanceiro');
        exit();
    }

    $this->listarUsu();  
    require_once __DIR__ . '/../views/painel-adm/chart.php';
    require_once __DIR__ . '/../views/menu2.php';
     //require_once __DIR__ . '/../views/confiBody.php';
    require_once __DIR__ . '/../views/painel-adm/homePainel.php';
    require_once __DIR__ . '/../views/footer.php';
}

public function painelFinanceiro()
{
    require_once __DIR__ . '/../views/verificar.php';
    $perfil = Permissoes::normalizarNivel($_SESSION['nivel'] ?? '');
    if ($perfil !== 'Financeiro') {
        // Painel financeiro: exibido somente para usuarios com perfil Financeiro.
        header('Location: ' . ($perfil === 'Administrador' ? '?router=Site/homePainel' : '?router=Site/home'));
        exit();
    }

    require_once __DIR__ . '/../views/menu2.php';
    // Painel financeiro: tela exclusiva para perfil Financeiro, sem graficos e dados administrativos.
    require_once __DIR__ . '/../views/painel-financeiro/painelFinanceiro.php';
    require_once __DIR__ . '/../views/footer.php';
}
public function listar()
{
    $this->listarUsu(); 
    $recupera = $this->recuperaDados();

}

public function editar()
{
    $alterar = $this->update();
    echo "Salvo com Sucesso";


}

public function  teste()
{
    //require_once __DIR__ . '/../views/painel-adm/monolog/vendor/autoload.php';
    require_once __DIR__ . '/../views/painel-adm/teste.php'; 
}


}
