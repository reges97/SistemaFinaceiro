<?php

namespace app\controllers;
use app\models\CrudconCiliacao;

class Conci extends CrudconCiliacao
{


    public function login()
    {
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/confmenu.php';

    }
    public function conciliacao()
{
    require_once __DIR__ . '/../views/menu2.php';

    require_once __DIR__ . '/../views/conciliacao/conciliacao.php';
   
    require_once __DIR__ . '/../views/footer.php';
   
}

public function con()
    {
        $this->conectar();
    }
    
    public function cadastrar()
    {
       
        $insere = $this->inserir();
    }


    public function listar()
    {

        require_once __DIR__ . '/../views/conciliacao/listarconci.php';
    }

    public function deletar()
{

    $this->excluir();
}

public function conciliar()
{

    $this->conci();
}


}