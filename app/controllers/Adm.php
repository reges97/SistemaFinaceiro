<?php

namespace app\controllers;
use app\models\Nivel;

class Adm extends Nivel
{

    public function login()
    {
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/confmenu.php';

    }
   
    public function niveis()
    {
        require_once __DIR__ . '/../views/menu2.php';
       //require_once __DIR__ . '/../views/confiBody.php';
       require_once __DIR__ . '/../views/niveis/niveis.php';
       
        require_once __DIR__ . '/../views/verificar.php';
        require_once __DIR__ . '/../views/footer.php';
        
    }

    public function listar()
    {    
        
        $listar = $this->listarNiv();
        require_once __DIR__ . '/../views/niveis/listarNiv.php';
    
        
        
    }

   /* public function listar()
    {

      
       require_once __DIR__ . '/../views/painel-adm/listar.php';
    }*/
        

    public function cadastrar()
    {

        $insere = $this->inserir();
        //require_once __DIR__ . '/../views/inserir.php';

    }

    public function deletar()
    {

        $deleta = $this->excluir();

    }


    public function menu()
    {
          $this->listaMenu();

    }

   

   
}
