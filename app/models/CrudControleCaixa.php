<?php

namespace app\models;

class CrudControleCaixa extends Connection
{
    
    public function listarControle()

{

    $pagina = 'controle_caixa';
//VARIAVEIS DOS INPUTS


    @session_start();

$dataInicial = filter_input(INPUT_POST, 'dataInicial', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$dataFinal =  filter_input(INPUT_POST, 'dataFinal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$status = '%'.filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS).'%';
$alterou_data = filter_input(INPUT_POST, 'alterou_data', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$vencidas =  filter_input(INPUT_POST, 'vencidas', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$hoje =  filter_input(INPUT_POST, 'hoje', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$amanha = filter_input(INPUT_POST, 'amanha', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$relatorio = filter_input(INPUT_POST, 'relatorio', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$data_hoje = date('Y-m-d');
$data_amanha = date('Y/m/d', strtotime("+1 days",strtotime($data_hoje)));

$pdo = $this->connect();

if($alterou_data == 'Sim' ){
	if($dataInicial != "" || $dataFinal != ""){
	$query = $pdo->query("SELECT * from $pagina where (data >= '$dataInicial' and data <= '$dataFinal') and tipo LIKE '$status'  order by id desc ");
	
  
}
}else if($status != '%%' and $alterou_data == ''){
	$query = $pdo->query("SELECT * from $pagina where tipo LIKE '$status'  order by id desc ");
}

else if($vencidas == 'Vencidas'){
	$query = $pdo->query("SELECT * from $pagina where data < curDate()  order by id desc ");
}

else if($vencidas == 'Hoje'){
	$query = $pdo->query("SELECT * from $pagina where data = curDate()  order by id desc ");
}

else if($vencidas == 'Amanha'){
	$query = $pdo->query("SELECT * from $pagina where data = '$data_amanha'  order by id desc ");
}

else{
	
	$query = $pdo->query("SELECT * from $pagina");

    
}

@$res = $query->fetchAll(\PDO::FETCH_ASSOC);


return $res;

}

public function relControle2()
{
	$pagina = 'controle_caixa';
	@$dataInicial = $_GET['dataInicial'];
	$dataFinal = $_GET['dataFinal'];

	$pdo = $this->connect();
	$query = $pdo->prepare("SELECT * from $pagina where data >= :dataInicial and data <= :dataFinal");
        $query->bindValue(":dataInicial", "$dataInicial");
        $query->bindValue(":dataFinal", "$dataFinal");
        $query->execute();
        $res = $query->fetchAll(\PDO::FETCH_ASSOC);
		
		
	return $res;



}

public function chart()

{
	$pagina = 'controle_caixa';

	$pagina = 'controle_caixa';
    $pdo = $this->connect();
	$query = $pdo->query("SELECT  C.data, C.movimento, C.entrada, C.saida, C.id_caixa, C.tipo, C.saldo, CX.data_ab, 
	 CX.usuario_ab, CX.data_fec, CX.usuario_fec, CX.saldo, CX.status, CX.valor_ab, CX.saldo_inicial FROM $pagina AS C 
	INNER JOIN caixa AS CX ON C.id_caixa = CX.id ");
	$res = $query->fetchAll(\PDO::FETCH_ASSOC);

    return $res;

}

public function conectar()
{
	
    $pdo = $this->connect();
	
    return $pdo;
}
}
