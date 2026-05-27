<?php

namespace app\controllers;
use app\models\CrudFormPgtos;

class FormPgtos extends CrudFormPgtos
{

    public function login()
    {
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/confmenu.php';


    }

    public function formas_pgtos()
    {  require_once __DIR__ . '/../views/menu2.php';
        //require_once __DIR__ . '/../views/confiBody.php';
        require_once __DIR__ . '/../views/formasPgtos/formas_pgtos.php';
        require_once __DIR__ . '/../views/footer.php';


    }

    public function listar()
    {
        require_once __DIR__ . '/../views/formasPgtos/listarFormPgtos.php';

    }

    public function cadastrar()
    {

        $this->inserir();
    }

    public function deletar()
{

    $this->excluir();

}   

public function con()
{
    $this->conectar();
}

}