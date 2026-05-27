<?php

namespace app\controllers;
use app\models\CrudContarPagar;

class ContasPagar extends CrudContarPagar
{
    public function login()
    {
        require_once __DIR__ . '/../views/login.php';
        require_once __DIR__ . '/../views/confmenu.php';

    }

    public function contas_pagar()
    
    {
        $this->jurosPag();
        $this->desconto();
        $this->multa();
        $this->recorrentes();
       //require_once __DIR__ . '/../views/contasPagar/recorrente.php';
        require_once __DIR__ . '/../views/menu2.php';
        require_once __DIR__ . '/../views/contasPagar/contas-pagar.php';
                
       
              
        require_once __DIR__ . '/../views/footer.php';
        
       
    }

    public function listar()
    {
        require_once __DIR__ . '/../views/contasPagar/listarContasPagar.php';

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

public function listar_comprovantes()
{
    require_once __DIR__ . '/../views/painel-adm/listar_comprovantes.php';

}


public function parcelar()
{

    $this->parcela();
}

public function comprovante()
{
    $this->imagens();

}

public function ativar()
{
    $this->mudarStatus();
}

public function listarCardH()
{
    $this->listarCard();
}


public function gerar()

{
    //$this->gerarExcel();

    require_once __DIR__ . '/../views/contasPagar/gerarEx.php';
    
   
}


}