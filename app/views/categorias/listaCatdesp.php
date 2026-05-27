<?php

use app\controllers\CatDespesas;

$pagina = 'cat_despesas';
//VARIAVEIS DOS INPUTS
$campo1 = 'Nome';
$campo2 = 'Grupo';

echo <<<HTML
<table id="example" class="table table-striped table-light table-hover my-4">
<thead>
<tr>
<th>{$campo1}</th>
<th>{$campo2}</th>
<th>Plano de contas</th>

									
<th>Ações</th>
</tr>
</thead>
<tbody>
HTML;

$categoria = new CatDespesas;
$res = $categoria->listaCatDesp();


for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 

		$id = $res[$i]['id'];
		$cp1 = $res[$i]['nome'];
		$cp2 = $res[$i]['grupo'];
		
	$pdo = $categoria->conectar();
			
		$query2 = $pdo->query("SELECT * from despesas where cat_despesa = '$id'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_despesas = @count($res2);
			

echo <<<HTML
	<tr>
	<td>{$cp1}</td>
	<td>{$cp2}</td>		
	<td>{$total_despesas} Despesas</td>									
	<td>
	<a href="#" onclick="editar('{$id}', '{$cp1}', '{$cp2}')" title="Editar Registro">	<i class="bi bi-pencil-square text-primary"></i> </a>
	<a href="#" onclick="excluir('{$id}' , '{$cp1}', '{$cp2}')" title="Excluir Registro">	<i class="bi bi-trash text-danger"></i> </a>
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


function editar(id, nome, grupo){
	$('#id').val(id);
	$('#<?=$campo1?>').val(nome);
	$('#<?=$campo2?>').val(grupo);
		
	$('#tituloModal').text('Editar Registro');
	var myModal = new bootstrap.Modal(document.getElementById('modalForm'), {		});
	myModal.show();
	$('#mensagem').text('');
}



function limparCampos(){
	$('#id').val('');
	$('#<?=$campo1?>').val('');
	$('#<?=$campo2?>').val('');
	

	$('#mensagem').text('');
	
}

</script>