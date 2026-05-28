<?php

use app\controllers\Banca;
use app\controllers\Caixa;
use app\controllers\ContasPagar;
use app\controllers\ContasReceber;
use app\models\CrudChart;

@session_start();

use app\controllers\ControleCaixa;
use app\controllers\Saldo;
use app\models\CrudContarPagar;

//MOSTRA NO GRAFICO DE FLUXO DE CAIXA DE ENTRADA E SAÍDAS
$lista = new ControleCaixa;
$res = $lista->listaChart();
 
$id = array();
$cp1 = array();


for ($i = 0; $i < @count($res); $i++) {
	foreach ($res[$i] as $key => $value) {
	}
		
	$cp1[] = $res[$i]['entrada'];
	$cp2[] = $res[$i]['saida'];
	$cp3[] = $res[$i]['data'];
	$cp4[] = $res[$i]['movimento'];
	$cp5 = $res[$i]['valor_ab'];
	$cp6[] = $res[$i]['tipo'];
    
	$cp7 = $res[$i]['saldo'];
	$cp1f = $res[$i]['entrada'];
	$cp2f = $res[$i]['saida'];


	@$entradacx += $cp1f;
    @$saidacx += $cp2f;
	

} 
 
@$totalCon = number_format($cp7, 2, ',', '.');
@$totalCx = $entradacx + $saidacx;


$totalCxf = number_format($totalCx, 2, ',', '.');

@$entradacxf = number_format($entradacx, 2, ',', '.');
//var_dump($entradacxf);

@$saidacxf = number_format($saidacx, 2, ',', '.');
//var_dump($saidacxf);


//var_dump($dataf);
 @$saldo = json_encode($cp5);
 @$entrada = json_encode($cp1);
 @$saida = json_encode($cp2);
 @$data = json_encode($cp3);
 @$tipo = json_encode($cp6);


//MOSTRA NO GRAFICO O TOTAL DIARIO DO CAIXA DEPOIS DO FECHAMENTO
 $chart = new Caixa;

 @$res2 = $chart->listarcharcaixa();

 for ($i = 0; $i < @count($res2); $i++) {
	foreach ($res2[$i] as $key => $value) {
	}

	$cx1[] = $res2[$i]['saldo'];
	$cx2[] = $res2[$i]['data_fec'];
	$cx3[] = $res2[$i]['data_ab'];
	$cx4[] = $res2[$i]['status'];

}

@$aberto = $cx4[0];
@$dataAb = $cx3[0];
@$dataFec = $cx2[0];
@$saldoCax = $cx1[0];
@$saldoCaxF = number_format($saldoCax, 2, ',', '.');
@$dataFecF =  implode('/', array_reverse(explode('-', $dataFec)));
@$dataAbF =  implode('/', array_reverse(explode('-', $dataAb)));



@$saldocx = json_encode($cx1);
@$dataab = json_encode($cx3);
@$datafec = json_encode($cx2);
@$status = json_encode($cx4);




//Exibe total geral e quantidade de vencimentos de hoje nos cards do painel.
$con = new ContasPagar;

$pdo = $con->conectar();

$stmt = $pdo->query("SELECT
    COALESCE(SUM(CASE WHEN status = 'Pendente' THEN valor ELSE 0 END), 0) AS total,
    COALESCE(SUM(CASE WHEN status = 'Pendente' AND vencimento = CURDATE() THEN 1 ELSE 0 END), 0) AS totalStatus
    FROM contas_pagar");
$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
 $pg = $result[0]['total'];
 $pg1 = $result[0]['totalStatus'];


 $total = number_format($pg, 2, ',', '.');
 $hoje = date('d/m/Y');

 $con = new ContasReceber;

$pdo = $con->conectar();

$stmt = $pdo->query("SELECT
    COALESCE(SUM(CASE WHEN status = 'Pendente' THEN valor ELSE 0 END), 0) AS total,
    COALESCE(SUM(CASE WHEN status = 'Pendente' AND vencimento = CURDATE() THEN 1 ELSE 0 END), 0) AS totalStatus
    FROM contas_receber");
$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
 $cr = $result[0]['total'];
 $cr1 = $result[0]['totalStatus'];

 $totalR = number_format($cr, 2, ',', '.');


 //MOVIMENTAÇÃO DE CONTAS

 $con2 = new Saldo;

$pdo2 = $con2->conectar();

$stmt2 = $pdo2->query("SELECT S.conta, S.saldo, S.debito, S.credito, B.saldo AS saldoB FROM 
saldo_conta AS S 
INNER JOIN bancarias AS B
 ON S.mov = B.id order by S.id ");
$result2= $stmt2->fetchAll(\PDO::FETCH_ASSOC);

for ($i = 0; $i < @count($result2); $i++) {
	foreach ($result2[$i] as $key => $value) {
	}
 @$saldo1[] = $result2[$i]['conta'];
 @$saldo2[] = $result2[$i]['saldo'];
 @$saldo3[] = $result2[$i]['data'];
 $saldo4 = $result2[$i]['saldoB'];

 @$debito = $result2[$i]['debito'];
 @$credito = $result2[$i]['credito'];
 @$nomeConta = $result2[$i]['conta'];

 @$debitoTotal += $debito;
 @$creditoTotal += $credito;
 $totalDC = $debitoTotal + $creditoTotal;
}


@$saldo4F += $saldo4;


//var_dump($saldo4F);


@$debitoTotalF = number_format($debitoTotal, 2, ',', '.');
@$creditoTotalF = number_format($creditoTotal, 2, ',', '.');
@$totalDC = number_format($totalDC, 2, ',', '.');

 
 @$saldoConta = json_encode($saldo1);
 @$saldoSaldo = json_encode($saldo2);
 @$saldoData = json_encode($saldo3);

 

 


 $con3 = new Banca;

$pdo3 = $con3->conectar();

$stmt3 = $pdo3->query("SELECT banco, saldo FROM bancarias order by id ");
$result3= $stmt3->fetchAll(\PDO::FETCH_ASSOC);
for ($i = 0; $i < @count($result3); $i++) {
	foreach ($result3[$i] as $key => $value) {
	}
 $banca1[] = $result3[$i]['banco'];
 $banca2[] = $result3[$i]['saldo']; 
 

 
 
}



 
 $banco = json_encode($banca1);
 $bancoSaldo = json_encode($banca2);
 

 
 $chart = new CrudChart;

 $result4 = $chart->listarChartTotal();

 for ($i = 0; $i < @count($result4); $i++) {
	foreach ($result4[$i] as $key => $value) {
	}  
	  
	   $mov1[] = $result4[$i]['ENTRADA'];
	   $mov2[] = $result4[$i]['SAIDA'];
	  
	   $mov1[] = $result4[$i]['ENTRADA'];
	   $mov2[] = $result4[$i]['SAIDA'];
 
}



 $mov1f = json_encode($mov1);
 $mov2f = json_encode($mov2);
 



$result5 = $chart->listarchartmov();

for ($i = 0; $i < @count($result5); $i++) {
	foreach ($result5[$i] as $key => $value) {
	}
	   $mov[]= $result5[$i]['data'];
	   $mo1[] = $result5[$i]['E'];
	   $mo2[] = $result5[$i]['S']; 
	   $mo3 = $result5[$i]['tipo'];

	   $mo1t = $result5[$i]['E'];
	   $mo2t = $result5[$i]['S']; 

	   @$totalMov1 += $mo1t;
	  

	   @$totalMov2 += $mo2t;
	}	   

@$totalMov1f = number_format($totalMov1, 2, ',', '.');
@$totalMov2f = number_format($totalMov2, 2, ',', '.');


//var_dump($totalMov1f);
//var_dump($totalMov2f);

@$movdata =  $mov;
//$movdataf = $movdata;

 $mof = json_encode($movdata);
 @$mo1f = json_encode($mo1);
 @$mo2f = json_encode($mo2);
 @$mo3f = json_encode($mo3);

//var_dump($mof);
//var_dump($mo3f);
//var_dump($mo2f);


 ?>
