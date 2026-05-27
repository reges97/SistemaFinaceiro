<?php

use app\models\CrudSaldos;

$campo1 = 'Banco';
$campo2 = 'Tipo Conta';
$campo3 = 'Saldo';
$campo4 = 'Usuário';
$campo5 = 'Tipo';
$campo6 = 'Data';
$campo7 = 'Contas';
$campo8 = 'Valor';
$campo9 = 'Conta';
$campo10 = 'Lançamentos';
$campo11 = 'Debito';
$campo12 = 'Credito';

@$valorTtalEntrada  = 0.0;
@$valorTotalSaida = 0.0;

echo <<<HTML

<table id="example2" class="table table-light table-hover my-4">
<thead>
<tr>
<th>{$campo1}</th>
<th>{$campo2}</th>
<th>{$campo4}</th>	
	
<th>{$campo6}</th>	
<th>{$campo7}</th>	
<th>{$campo8}</th>	
<th>{$campo9}</th>												
<th>{$campo10}</th>	
<th>{$campo11}</th>	
<th>{$campo12}</th>	
<th>{$campo3}</th>	

</tr>
</thead>
<tbody>
HTML;

$lista = new CrudSaldos;
@$res = $lista->listaSaldo();

for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 

		$id = $res[$i]['id'];
		$cp1 = $res[$i]['conta'];
		$cp2 = $res[$i]['tipo_conta'];
		$cp3 = $res[$i]['saldo'];
		$cp4 = $res[$i]['usuario'];
		$cp5 = $res[$i]['tipo'];
		$cp6 = $res[$i]['data'];
		@$cp7 = $res[$i]['plano_conta'];
		$cp8 = $res[$i]['valor'];
		$cp9 = $res[$i]['mov'];
		$cp10 = $res[$i]['pagar_receber'];
		$cp11 = $res[$i]['debito'];
		$cp12 = $res[$i]['credito'];

       
		$debito = number_format($cp11, 2, ',', '.');
		$credito = number_format($cp12, 2, ',', '.');
        $data_mov = implode('/', array_reverse(explode('-', $cp6)));
		
		$valor = number_format($cp4, 2, ',', '.');

		if($cp5 == 'Saida'){
            $classe = 'text-danger'; 
			@$valorTotalSaida += $cp12;
			@$valorTotalSaidaF = number_format($valorTotalSaida, 2, ',', '.');
			
		}if($cp5 == 'Entrada'){
			$classe = 'text-success';
			@$valorTtalEntrada += $cp11;
			@$valorTotalEntradaF = number_format($valorTtalEntrada, 2, ',', '.');
		}
           
		
echo <<<HTML
	<tr class = $classe>
	<td>{$cp1}</td>		
	<td>{$cp2}</td>	
	<td>{$cp4}</td>	
	
	<td>{$data_mov}</td>	
	<td>{$cp7}</td>	
	<td>{$cp8}</td>	
	<td>{$cp9}</td>	
	<td>{$cp10}</td>
	<td>{$debito}</td>
	<td>{$credito}</td>
	<td>{$cp3}</td>	
    
    </tr>
HTML;
} 
echo <<<HTML
</tbody>
</table>


HTML;
?>

<script>
$(document).ready(function() {    
	$('#example2').DataTable({
		"ordering": false
	});
    
	$('#total_saida').text('R$ <?=@$valorTotalSaidaF?>');
		
} );

$(document).ready(function() {
	$('#total_entrada').text('R$ <?=@$valorTotalEntradaF?>');
		
		});

	

</script>
