<?php

namespace app\controllers;
use app\models\CrudBancarias;



class Banca extends CrudBancarias
{

    public function login()
    {
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/confmenu.php';

    }
    public function bancarias()

    { 
       
        require_once __DIR__ . '/../views/menu2.php';
       // require_once __DIR__ . '/../views/confiBody.php';
        require_once __DIR__ . '/../views/bancarias/bancarias.php'; 
        require_once __DIR__ . '/../views/footer.php';
        require_once __DIR__ . '/../views/verificar.php';
    }

    
    public function listar()
    {
        
        //$lista = $this->listarBanca();
        require_once __DIR__ . '/../views/bancarias/listarBanca.php';
    }
    

    public function cadastrar()

    {
     $insere =  $this->inserir();
    }
    
    public function selecao()
    {

        $seleciona = $this->selecaoBanco();
    }

    public function deletar()
    {

        $exclui = $this->excluir();
        
    }

    public function gerarExcel()
    {
      $this->gerar();

    }

    public function con()
{
    $this->conectar();
}

}