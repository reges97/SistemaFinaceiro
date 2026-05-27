<?php

namespace app\controllers;
use app\models\CrudCaixa;

class Caixa extends CrudCaixa
{
        public function login()
        {
            require_once __DIR__ . '/../views/login.php';
            require_once __DIR__ . '/../views/confmenu.php';
    
        }


    public function caixa()
    {   require_once __DIR__ . '/../views/menu2.php';
        //require_once __DIR__ . '/../views/confiBody.php';
        require_once __DIR__ . '/../views/caixa/caixa.php'; 
        require_once __DIR__ . '/../views/footer.php';
        require_once __DIR__ . '/../views/verificar.php';
    }

    public function cadastrar()
    {
       $this->inserir();
    }

    public function deletar()
    {

        $this->excluir();
    }

    public function fechar_caixa()
    {
        $this->fecharcaixa();
    }

    public function listar()
    {
        $lista = $this->listarCaixa();
        require_once __DIR__ . '/../views/caixa/listarCaixa.php'; 
    }

    public function chartCaixa()
    {

         $this->listarcharcaixa();
    }

    public function con()
    {
        $this->conectar();
    }

    

    }
