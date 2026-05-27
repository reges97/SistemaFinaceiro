<?php

namespace app\controllers;
use app\models\CrudFornecedor;

class Forne extends CrudFornecedor
{
    public function login()
    {
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/confmenu.php';
    }

    public function listar()
    {
        require_once __DIR__ . '/../views/fornecedores/listarForne.php';

    }

    public function fornecedores()
    {
        require_once __DIR__ . '/../views/menu2.php';
        //require_once __DIR__ . '/../views/confiBody.php';
        require_once __DIR__ . '/../views/fornecedores/fornecedores.php'; 
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

    public function mudarstatus()
    {
        $muda = $this->mudar();

    }

    public function selecoa()
    {

        $this->selecaoBanco();
    }

}