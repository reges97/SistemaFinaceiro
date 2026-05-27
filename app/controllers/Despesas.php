<?php

namespace app\controllers;
use app\models\CrudDespesas;

class Despesas extends CrudDespesas
{
    public function login()

    {
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/confmenu.php';

    }

    public function despesas()

    {
        require_once __DIR__ . '/../views/menu2.php';
        //require_once __DIR__ . '/../views/confiBody.php';
        require_once __DIR__ . '/../views/despesas/despesas.php';
        require_once __DIR__ . '/../views/footer.php';
        
    }

    public function listar()
    {
        require_once __DIR__ . '/../views/despesas/listarDesp.php';

    }

    public function cadastrar()
    {
        $insere = $this->inserir();
    }

    public function deletar()
    {
        $exclui = $this->excluir();

    }

    public function selecaoCat()

    {
        $selecao = $this->selecao();
    }

    public function con()
    {
        $this->conectar();
    }

    
}
