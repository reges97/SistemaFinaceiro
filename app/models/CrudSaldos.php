<?php

namespace app\models;

class CrudSaldos extends Connection
{

    public function listaSaldo()

{
$pagina = 'saldo_conta'; 

@session_start();



$dataInicial = filter_input(INPUT_POST, 'dataInicial', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$dataFinal =  filter_input(INPUT_POST, 'dataFinal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$alterou_data = filter_input(INPUT_POST, 'alterou_data', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$banco = filter_input(INPUT_POST, 'banco', FILTER_SANITIZE_FULL_SPECIAL_CHARS);




$pagina = 'saldo_conta';
$pdo = $this->connect();

//var_dump($dataInicial);
//var_dump($tipo);
//var_dump($dataFinal);
//var_dump($banco);


if($alterou_data == 'Sim' ){
	if($dataInicial != "" || $dataFinal != ""){
	$query = $pdo->query("SELECT * FROM $pagina where data >= '$dataInicial' and data <= '$dataFinal'  
	and tipo LIKE '$tipo' and conta = '$banco' order by data desc ");
	//var_dump($query);
	}
}else if($tipo != '%%' && $banco !=''){
	$query = $pdo->query("SELECT * from $pagina where tipo = '$tipo' and conta = '$banco'  order by id ");
	//var_dump($query);

}else{
	
	$query = $pdo->query("SELECT * from $pagina order by id  ");

	//var_dump($query);
	  
}

@$res = $query->fetchAll(\PDO::FETCH_ASSOC);
return $res;
    
}

	
public function conectar()
{

    $pdo = $this->connect();

    return $pdo;
}


public function rel()
{
$pagina = 'saldo_conta'; 
$pdo = $this->connect();
$query = $pdo->query("SELECT * FROM $pagina ORDER BY conta");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
return $res;

}




}

