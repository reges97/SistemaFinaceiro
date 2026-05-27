<?php

namespace app\controllers;
use app\models\CrudFrequencia;

class Freq extends CrudFrequencia
{

    public function login()
    {
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/confmenu.php';

    }

    public function listar()
    {

        require_once __DIR__ . '/../views/frequencias/listarFreq.php';
    }

    public function frequencias()
    {      require_once __DIR__ . '/../views/menu2.php';
        //require_once __DIR__ . '/../views/confiBody.php';
        require_once __DIR__ . '/../views/frequencias/frequencias.php';
        require_once __DIR__ . '/../views/footer.php';

    }


    public function cadastrar()
    {

        $this->inserir();

    }

    public function deletar()

    {
        
        $this->excluir();

    }
}