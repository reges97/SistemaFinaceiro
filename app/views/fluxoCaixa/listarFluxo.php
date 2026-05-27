<?php
use app\models\CrudFluxo;



$campo1 = 'Tipo';
$campo2 = 'N.Agenda E';
$campo3 = 'N.Agenda S';
$campo4 = 'Valor';
$campo5 = 'Usuário';
$campo6 = 'Data';
$campo7 = 'Lançamento';
$campo8 = 'Plando de conta';
$campo9 = 'Documento';
$campo10 = 'Caixa';
$campo11 = 'Entrada';
$campo12 = 'Saída';

$valorTtalEntrada  = 0.0;
$valorTotalSaida = 0.0;

echo <<<HTML
<table id="example2" class="table table-light table-hover my-4">
<thead>
<tr>
<th>{$campo1}</th>
<th>{$campo2}</th>
<th>{$campo3}</th>	
<th>{$campo4}</th>	
<th>{$campo5}</th>	
<th>{$campo6}</th>	
<th>{$campo7}</th>	
<th>{$campo8}</th>	
<th>{$campo9}</th>												
<th>{$campo10}</th>	
<th>{$campo11}</th>	
<th>{$campo12}</th>	
</tr>
</thead>
<tbody>
HTML;

$lista = new CrudFluxo;
@$res = $lista->listarFluxo();


for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 

		$id = $res[$i]['id'];
		$cp1 = $res[$i]['tipo'];
		$cp2 = $res[$i]['conta_rec'];
		$cp3 = $res[$i]['conta_pag'];
		$cp4 = $res[$i]['valor'];
		$cp5 = $res[$i]['nome_usu'];
		$cp6 = $res[$i]['data'];
		$cp7 = $res[$i]['lancamento'];
		$cp8 = $res[$i]['nome_desp'];
		$cp9 = $res[$i]['nome_fpg'];
		$cp10 = $res[$i]['caixa_periodo'];
		$cp11 = $res[$i]['E'];
		$cp12 = $res[$i]['S'];

        $data_mov = implode('/', array_reverse(explode('-', $cp6)));
		
		$valor = number_format($cp4, 2, ',', '.');
       
	

		if($cp1 == 'Saida'){
            @$classe = 'text-danger'; 
			$valorTotalSaida += $cp12;
			@$valorTotalSaidaF = number_format($valorTotalSaida, 2, ',', '.');
			
		}if($cp1 == 'Entrada'){
			@$classe = 'text-success';
			$valorTtalEntrada += $cp11;
			@$valorTotalEntradaF = number_format($valorTtalEntrada, 2, ',', '.');
		}
            
		
echo <<<HTML
	<tr class= $classe>
	<td>{$cp1}</td>		
	<td>{$cp2}</td>	
	<td>{$cp3}</td>	
	<td> {$valor}</td>	
	<td>{$cp5}</td>	
	<td>{$data_mov}</td>	
	<td>{$cp7}</td>	
	<td>{$cp8}</td>	
	<td>{$cp9}</td>	
	<td>{$cp10}</td>
	<td>{$cp11}</td>
	<td>{$cp12}</td>	
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
