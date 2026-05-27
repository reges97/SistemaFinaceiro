<?php

namespace app\controllers;
use app\models\CrudBancos;

class Bancos extends CrudBancos
{
    public function logins()
    {
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/confmenu.php';

    }

    public function bancos()
{    require_once __DIR__ . '/../views/menu2.php';
    //require_once __DIR__ . '/../views/confiBody.php';
    require_once __DIR__ . '/../views/bancos/bancos.php';
    require_once __DIR__ . '/../views/verificar.php';
    require_once __DIR__ . '/../views/footer.php';

}

public function listar()
{
    $lista = $this->listarBanc();
    require_once __DIR__ . '/../views/bancos/listarBancos.php';
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

