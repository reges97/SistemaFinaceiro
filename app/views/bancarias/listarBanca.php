<?php 
use app\models\CrudBancarias;

$pagina = 'bancarias';
//VARIAVEIS DOS INPUTS
$campo1 = 'Banco';
$campo2 = 'Agencia';
$campo3 = 'Conta';
$campo4 = 'Tipo';
$campo5 = 'Pessoa';
$campo6 = 'Doc';
$campo7 = 'Saldo';
$campo8 = 'Saldo_ini';

echo <<<HTML
<table id="example" class="table table-striped table-light table-hover my-4">
<thead>
<tr>
<th>{$campo1}</th>
<th>{$campo2}</th>
<th>{$campo3}</th>	
<th>{$campo4}</th>	
<th>{$campo5}</th>
<th>CPF/CNPJ</th>
<th>Saldo Inicial</th>			
<th>Saldo Atual</th>	

										
<th>Ações</th>
</tr>
</thead>
<tbody>
HTML;

$lista = new CrudBancarias;
$res = $lista->listarBanca();

for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 

		$id = $res[$i]['id'];
		$cp1 = $res[$i]['banco'];
		$cp2 = $res[$i]['agencia'];
		$cp3 = $res[$i]['conta'];
		$cp4 = $res[$i]['tipo'];
		$cp5 = $res[$i]['pessoa'];
		$cp6 = $res[$i]['doc'];
		$cp8 = $res[$i]['saldo_ini'];
		$cp7 = $res[$i]['saldo'];
		

		$cp7f = number_format($cp7, 2, ',', '.');

echo <<<HTML
	<tr>
	<td>{$cp1}</td>		
	<td>{$cp2}</td>	
	<td>{$cp3}</td>	
	<td>{$cp4}</td>	
	<td>{$cp5}</td>	
	<td>{$cp6}</td>	
	<td>{$cp8}</td>	
	<td>{$cp7f}</td>									
	<td>
	<a href="#" onclick="editar('{$id}', '{$cp1}', '{$cp2}', '{$cp3}', '{$cp4}', '{$cp5}', '{$cp6}','{$cp8}', '{$cp7}')" title="Editar Registro">	<i class="bi bi-pencil-square text-primary"></i> </a>
	<a href="#" onclick="excluir('{$id}' , '{$cp1}')" title="Excluir Registro">	<i class="bi bi-trash text-danger"></i> </a>
	</td>
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
	$('#example').DataTable({
		"ordering": false
	});

} );


function editar(id, cp1, cp2, cp3, cp4, cp5, cp6, cp8, cp7){
	$('#id').val(id);
	$('#<?=$campo1?>').val(cp1);
	$('#<?=$campo2?>').val(cp2);
	$('#<?=$campo3?>').val(cp3);
	$('#<?=$campo4?>').val(cp4);
	$('#<?=$campo5?>').val(cp5);
	$('#<?=$campo6?>').val(cp6);
	$('#<?=$campo8?>').val(cp8);
	$('#<?=$campo7?>').val(cp7);
	
	$('#tituloModal').text('Editar Registro');
	var myModal = new bootstrap.Modal(document.getElementById('modalForm'), {		});
	myModal.show();
	$('#mensagem').text('');
}



function limparCampos(){
	$('#id').val('');
	
	$('#<?=$campo2?>').val('');
	$('#<?=$campo3?>').val('');
	
	$('#<?=$campo6?>').val('');
	$('#<?=$campo7?>').val('');

	$('#mensagem').text('');
	
}

</script>