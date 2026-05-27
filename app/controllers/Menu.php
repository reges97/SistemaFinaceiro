<?php

namespace app\controllers;
use app\models\CrudMenu;

class Menu extends CrudMenu
{

    public function menuteste()
    {
        require_once __DIR__ . '/../views/menu/menu.php';

        
        require_once __DIR__ . '/../views/footer.php';
    }
    
}