<?php

namespace app\controllers;
use app\models\CrudProcessa;

class Pagamento extends CrudProcessa
{
    public function cartao()
    {
        require_once __DIR__ . '/../views/confiBody.php';

   
        require_once __DIR__ . '/../views/painel-adm/apiconfig.php';

        require_once __DIR__ . '/../views/painel-adm/consulta.php';
      
        require_once __DIR__ . '/../views/painel-adm/cartao.php';

        $this->processa();
    }
}