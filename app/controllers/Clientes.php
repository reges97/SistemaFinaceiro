<?php

namespace app\controllers;
use app\models\CrudClientes;

class Clientes extends CrudClientes
{

    public function login()
    {
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/confmenu.php';
    }
 
    public function clientes() 
    {
        require_once __DIR__ . '/../views/verificar.php';
        require_once __DIR__ . '/../views/menu2.php';
        
        //require_once __DIR__ . '/../views/confiBody.php';
        require_once __DIR__ . '/../views/clientes/clientes.php';
        require_once __DIR__ . '/../views/footer.php';

    }

    public function cadastro()
    {
        $insere = $this->inserir();
       
    }

    public function listar()

    {
        
        require_once __DIR__ . '/../views/clientes/listaCli.php';
    }

    public function mudarstatus()
    {
        $muda = $this->mudar();

    }

    public function selecao()
    {

        $seleciona = $this->selecaoBanco();
    }


    
    
}
