<?php

namespace app\controllers;
use app\models\CrudCatProd;

class CatProd extends CrudCatProd
{
    public function login()

{

    require_once __DIR__ . '/../views/login.php';
    require_once __DIR__ . '/../views/confmenu.php';
}

public function cat_produtos()
{require_once __DIR__ . '/../views/menu2.php';
    //require_once __DIR__ . '/../views/confiBody.php';
    require_once __DIR__ . '/../views/categorias/cat_produtos.php';
    require_once __DIR__ . '/../views/verificar.php';
    require_once __DIR__ . '/../views/footer.php';

}

public function listar()
{

    require_once __DIR__ . '/../views/categorias/listarCatProd.php'; 
}

public function con()
{
 $this->connect();
}
}

