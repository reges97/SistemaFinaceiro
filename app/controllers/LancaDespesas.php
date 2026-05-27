<?php

namespace app\controllers;
use app\models\CrudLancaDespesa;

class LancaDespesas extends CrudLancaDespesa
{

    public function login()
    {
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/confmenu.php';

    }
    public function lanca_depesas()
    {
        $this->recorrentes();
        //require_once __DIR__ . '/../views/contasPagar/recorrente.php';
         require_once __DIR__ . '/../views/menu2.php';
         require_once __DIR__ . '/../views/LancaDespesas/lancadespesas.php';
                 
         $this->jurosPag();
         $this->desconto();
         $this->multa();
               
         require_once __DIR__ . '/../views/footer.php';

    } 

    public function listar()
    {
        require_once __DIR__ . '/../views/LancaDespesas/listalancdespesas.php';

    }

    public function listar_despesas()
{
    require_once __DIR__ . '/../views/despesas/listar-despesas.php';
   

}

public function listar_forne()
{
    require_once __DIR__ . '/../views/fornecedores/listar-forne.php';
}


public function baixar()
{
    $baixar = $this->baixa();

}



public function listar_residuos()
{
    require_once __DIR__ . '/../views/painel-adm/listar-residuos.php';

}


public function parcelar()
{

    $this->parcela();
}

public function comprovante()
{
    $this->imagens();

}

}