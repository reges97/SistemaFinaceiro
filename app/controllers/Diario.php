<?php

namespace app\controllers;
use app\models\CrudDiario;

class Diario extends CrudDiario
{

    public function login()
        {
            require_once __DIR__ . '/../views/login.php';
            require_once __DIR__ . '/../views/confmenu.php';
    
        }


    public function fluxoDiario()
    {   require_once __DIR__ . '/../views/menu2.php';
        //require_once __DIR__ . '/../views/confiBody.php';
        require_once __DIR__ . '/../views/diario/diario.php'; 
        require_once __DIR__ . '/../views/footer.php';
        require_once __DIR__ . '/../views/verificar.php';
    }


    public function listar()
    {
    
        require_once __DIR__ . '/../views/diario/listarDiario.php'; 
    }

    public function cadastrar()

    {
     $insere =  $this->inserir();
    }


    public function listar2()
    {
        
        //$lista = $this->listarBanca();
        require_once __DIR__ . '/../views/bancarias/listarBanca.php';
    }
   


    
}