<?php

namespace app\controllers;
use app\models\CrudContasReceber;

class ContasReceber extends CrudContasReceber
{
    public function login()
    {
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/confmenu.php';
    }

    public function contas_receber()
{    
    require_once __DIR__ . '/../views/menu2.php';
    //require_once __DIR__ . '/../views/confiBody.php';
    require_once __DIR__ . '/../views/contasReceber/contas-receber.php'; 
    
    $this->recorrentes();
    $this->jurosPag();
    $this->desconto();
    $this->multa();
    require_once __DIR__ . '/../views/footer.php';

}

public function listar()
{
    require_once __DIR__ . '/../views/contasReceber/listarContasReceber.php';

}

public function con()
{
    
    $this->conectar();
}


public function cadastrar()
{

$insere = $this->inserir();
}

public function deletar()
{

$this->excluir();
}

public function listar_despesas()
{
require_once __DIR__ . '/../views/despesas/listar-despesas.php';


}

public function fechar_caixa()
{
$this->fecharCaixa();
}

public function listar_clientes()
{
    require_once __DIR__ . '/../views/clientes/listar-clientes.php';
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

public function receberCardH()
{
    $this->receberCard();
}

public function gerar()

{
    //$this->gerarExcel();

    require_once __DIR__ . '/../views/contasReceber/gerarEx.php';
    
   
}


}



