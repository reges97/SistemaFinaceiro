<?php

namespace app\controllers;
use app\models\CrudUsuarios;

class User extends CrudUsuarios
{
    public function login()
    {
        

    }
   
    public function usuarios()
    
    {
        require_once __DIR__ . '/../views/menu2.php';
        //require_once __DIR__ . '/../views/confiBody.php';
        require_once __DIR__ . '/../views/usuarios/usuarios.php';
        require_once __DIR__ . '/../views/verificar.php';
        require_once __DIR__ . '/../views/footer.php';

    }

    public function selecaoUsu()

    {

        $selecionar = $this->selecao();
    }

    public function listar()
    {
        $litar = $this->listarUsu();
        require_once __DIR__ . '/../views/usuarios/listarUsu.php';
    }

    public function alterar()
    {
        $insere = $this->inserir();


    }

     public function deletar()

     {
        $delete = $this->excluir();
     }

     public function relusuario()

     {

        require_once __DIR__ . '/../views/menu2.php';

        require_once __DIR__ . '/../views/usuarios/relusuario.php';
        
        require_once __DIR__ . '/../views/footer.php';

     }

     public function relusuex()
     {
        require_once __DIR__ . '/../views/usuarios/listaUsuEx.php';
    }

    public function geraRelusu()
    {
        require_once __DIR__ . '/../views/usuarios/geraRelusu.php';

    }
}