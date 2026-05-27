<?php

namespace app\controllers;
use app\models\CrudAcessos;

class Acessos extends CrudAcessos
{


    public function login()
    {
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/confmenu.php';

    }
    public function acessos()
    {
        require_once __DIR__ . '/../views/menu2.php';

        require_once __DIR__ . '/../views/acessos/acessos.php';

        require_once __DIR__ . '/../views/footer.php';
    }

    public function listar()
    {
        $litar = $this->listarAcesso();
        require_once __DIR__ . '/../views/acessos/listarAcesso.php';
    }

    public function listarAces()
    {
        $this->listarUsu();
    }

    public function alterar()
    {
        $insere = $this->inserir();


    }

     public function deletar()

     {
        $delete = $this->excluir();
     }

    }