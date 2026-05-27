<?php

namespace app\controllers;
use app\models\CrudMov;

class Mov extends CrudMov
{

    public function login()
    {
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/confmenu.php';

    }

    public function movimentacao()
    {
        require_once __DIR__ . '/../views/menu2.php';

        //require_once __DIR__ . '/../views/confiBody.php';

        require_once __DIR__ . '/../views/fluxoCaixa/movimentacao.php';
        require_once __DIR__ . '/../views/footer.php';
    }

    public function listar()

    {
        require_once __DIR__ . '/../views/fluxoCaixa/listarMov.php';

    }

    public function gerar()

    {
        //$this->gerarExcel();
    
        require_once __DIR__ . '/../views/fluxoCaixa/gerarMovEx.php';
        
       
    }

    public function con()
    {
        $this->conectar();
    }


}