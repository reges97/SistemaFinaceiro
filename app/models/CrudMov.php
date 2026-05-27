<?php

namespace app\models;

class CrudMov extends Connection
{

    public function listarMov()

{
$pagina = 'movimentacoes';
    $dataInicial = filter_input(INPUT_POST, 'dataInicial', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $dataFinal =  filter_input(INPUT_POST, 'dataFinal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $status = '%'.filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS).'%';
    $alterou_data = filter_input(INPUT_POST, 'alterou_data', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $entradas =  filter_input(INPUT_POST, 'entradas', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $hoje =  filter_input(INPUT_POST, 'hoje', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $saida = filter_input(INPUT_POST, 'saida', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    $data_hoje = date('Y-m-d');
    $data_amanha = date('Y/m/d', strtotime("+1 days",strtotime($data_hoje)));

    
    
    $pdo = $this->connect();
    
    if($alterou_data == 'Sim'){
        if($dataInicial != "" || $dataFinal != ""){
        $query = $pdo->query("SELECT M.id, M.tipo, M.E, M.S, M.movimento, 
        M.descricao, M.descricao, M.valor, M.usuario, 
        U.nome_usu, M.data, M.lancamento, M.plano_conta,
        D.nome_desp, M.documento, F.nome_fpg, M.caixa_periodo, 
        M.conta_pag, M.mov_contas, M.conta_rec
        FROM movimentacoes as M 
        INNER JOIN usuarios AS U ON U.id = M.usuario 
        INNER JOIN formas_pgtos AS F ON F.id = M.documento
        INNER JOIN despesas AS D ON D.id = M.plano_conta
         where (data >= '$dataInicial' and data <= '$dataFinal') and tipo LIKE '$status'  order by data desc ");
        }
    }else if($status != '%%' and $alterou_data == ''){
        $query = $pdo->query("SELECT * from $pagina where tipo LIKE '$status'  order by id desc ");
    }
    
    else if($entradas == 'Entradas'){
        $query = $pdo->query("SELECT * from $pagina where tipo = 'Entrada'  order by id desc ");
    }
    
    else if($hoje == 'Hoje'){
        $query = $pdo->query("SELECT * from $pagina where data = curDate()  order by id desc ");
    }
    
    else if($saida == 'Saidas'){
        $query = $pdo->query("SELECT * from $pagina where tipo = 'Saída'  order by id desc ");
    }
    
    else{
        $query = $pdo->query("SELECT M.id, M.tipo, M.E, M.S, M.movimento, 
        M.descricao, M.descricao, M.valor, M.usuario, 
        U.nome_usu, M.data, M.lancamento, M.plano_conta,
        D.nome_desp, M.documento, F.nome_fpg, M.caixa_periodo, 
        M.conta_pag, M.mov_contas, M.conta_rec
        FROM movimentacoes as M 
        INNER JOIN usuarios AS U ON U.id = M.usuario 
        INNER JOIN formas_pgtos AS F ON F.id = M.documento
        INNER JOIN despesas AS D ON D.id = M.plano_conta
         where data = curDate() order by id desc ");
    
        
    }
    
    @$res = $query->fetchAll(\PDO::FETCH_ASSOC);
    
    
    return $res;
}

public function conectar()
{

    $pdo = $this->connect();

    return $pdo;
}


public function gerarExcel()
{

$dataInicial = filter_input(INPUT_POST, 'dataInicial', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$dataFinal =  filter_input(INPUT_POST, 'dataFinal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tipo =  filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	//$dataInicial = $_GET['dataInicial'];
	//$dataFinal = $_GET['dataFinal'];

	//var_dump($dataInicial);
    //var_dump($dataFinal);

	$pdo = $this->connect();

if($dataInicial != "" || $dataFinal != "" AND $tipo == ""){
	$query = $pdo->prepare("SELECT 
    M.id, M.tipo, M.E, M.S, M.movimento, 
    M.descricao, M.descricao, M.valor, M.usuario, 
    U.nome_usu, M.data, M.lancamento, M.plano_conta,
    D.nome_desp, M.documento, F.nome_fpg, M.caixa_periodo, 
    M.conta_pag, M.mov_contas, M.conta_rec
    FROM movimentacoes as M 
    INNER JOIN usuarios AS U ON U.id = M.usuario 
    INNER JOIN formas_pgtos AS F ON F.id = M.documento
    INNER JOIN despesas AS D ON D.id = M.plano_conta
    WHERE data >= :dataInicial AND data <= :dataFinal OR tipo = :tipo ");
        
        $query->bindValue(":dataInicial", "$dataInicial");
        $query->bindValue(":dataFinal", "$dataFinal");
		$query->bindValue(":tipo", "$tipo");
        $query->execute();
	
	
	}else if($dataInicial != "" || $dataFinal != ""){         
	
		$query = $pdo->prepare("SELECT 
        M.id, M.tipo, M.E, M.S, M.movimento, 
        M.descricao, M.descricao, M.valor, M.usuario, 
        U.nome_usu, M.data, M.lancamento, M.plano_conta,
        D.nome_desp, M.documento, F.nome_fpg, M.caixa_periodo, 
        M.conta_pag, M.mov_contas, M.conta_rec
        FROM movimentacoes as M 
        INNER JOIN usuarios AS U ON U.id = M.usuario 
        INNER JOIN formas_pgtos AS F ON F.id = M.documento
        INNER JOIN despesas AS D ON D.id = M.plano_conta
		WHERE data >= :dataInicial AND data <= :dataFinal AND tipo = :tipo ");
			
			$query->bindValue(":dataInicial", "$dataInicial");
			$query->bindValue(":dataFinal", "$dataFinal");
			$query->bindValue(":tipo", "$tipo");
			$query->execute();
	
	}else{

	}
		return $query;
		
}
    
}