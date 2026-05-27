<?php
@session_start();
use app\controllers\Caixa;
use app\controllers\Diario;

$pagina = 'caixa';
//VARIAVEIS DOS INPUTS
$campo1 = 'data_ab';
$campo2 = 'valor_ab';
$campo3 = 'usuario_ab';
$campo4 = 'data_fec';
$campo6 = 'usuario_fec';
$campo7 = 'Saldo';
$campo8 = 'Status';

echo <<<HTML
<table id="example" class="table table-striped  table-hover my-4">
<thead>
<tr>

<th>Data Abertura</th>	
<th>Saldo Inicial</th>	
<th>Entrada</th>	
<th>Saída</th>	
<th>Saldo Final</th>	


</tr>
</thead>
<tbody>
HTML;

$listar = new Diario;
$res = $listar->listarDiario();

for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 

		$cp1 = $res[$i]['data_flux_ab'];
		$cp2 = $res[$i]['saldo_ini'];
		$cp3 = $res[$i]['E'];
		$cp4 = $res[$i]['S'];
		$cp5 = $res[$i]['saldo_final'];
		
		
		$cp1f = implode('/', array_reverse(explode('-', $cp1)));

		$cp2f = number_format($cp2, 2, ',', '.');
		$cp3f = number_format($cp3, 2, ',', '.');
		$cp4f = number_format($cp4, 2, ',', '.');
		$cp5f = number_format($cp5, 2, ',', '.');
		
echo <<<HTML
	<tr>
		
	<td>{$cp1f}</td>	
	<td>{$cp2f}</td>		
	<td>{$cp3f}</td>	
	<td>{$cp4f}</td>	
	<td>{$cp5f}</td>	
	
	
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


function editar(id, cp2){
	$('#id').val(id);
	$('#<?=$campo2?>').val(cp2);
		
	$('#tituloModal').text('Editar Registro');
	var myModal = new bootstrap.Modal(document.getElementById('modalForm'), {		});
	myModal.show();
	$('#mensagem').text('');
}



function limparCampos(){
	$('#id').val('');
	
	$('#<?=$campo2?>').val('');
	$('#mensagem').text('');
	
}


function fechar(id, cp1){
	$('#id-fechar').val(id);
	$('#data_abert').text(cp1);
	
	var myModal = new bootstrap.Modal(document.getElementById('modalFechar'), {		});
	myModal.show();
	$('#mensagem-fechar').text('');
}

</script>
