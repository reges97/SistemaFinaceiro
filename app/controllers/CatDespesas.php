<?php

namespace app\controllers;
use app\models\CrudCatdespesa;

class CatDespesas extends CrudCatdespesa
{
    public function login()
    {
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/confmenu.php';


    }

    public function cat_despesas()
    {require_once __DIR__ . '/../views/menu2.php';
        //require_once __DIR__ . '/../views/confiBody.php';
        require_once __DIR__ . '/../views/categorias/cat_despesas.php'; 
        require_once __DIR__ . '/../views/footer.php';
        require_once __DIR__ . '/../views/verificar.php';
    }

    public function listar()
    {
       
        require_once __DIR__ . '/../views/categorias/listaCatdesp.php'; 

    }

    public function cadastrar()

    {

        $insere = $this->inserir();
    }

    public function deletar()

    {

        $exclui = $this->excluir();
    }


    public function con()
{

    $this->conectar();
}

}