<?php
use app\controllers\Despesas;
$pagina = 'despesas';
//VARIAVEIS DOS INPUTS
$campo1 = 'Nome';
$campo2 = 'Categoria';
$campo3 = 'Subgrupo';

echo <<<HTML
<table id="example" class="table table-striped table-light table-hover my-4">
<thead>
<tr>
<th>{$campo1}</th>
<th>{$campo2}</th>
<th>{$campo3}</th>
							
<th>Ações</th>
</tr>
</thead>
<tbody>
HTML;

$lista = new Despesas;
$res = $lista->listarDesp();

for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 

		$id = $res[$i]['id'];
		$cp1 = $res[$i]['nome_desp'];
		$cp2 = $res[$i]['cat_despesa'];
		$cp3 = $res[$i]['subgrupo'];
		$cp4 = $res[$i]['cat_nome'];
		
        $pdo = $lista->conectar();
		$query2 = $pdo->query("SELECT * from cat_despesas where id = '$cp2'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		
		$nome_cat = $res2[0]['nome'];

		echo <<<HTML
		<tr>
		<td>{$cp1}</td>	
		<td>{$cp4}</td>	
		<td>{$cp3}</td>		
		
									
		<td>
		<a href="#" onclick="editar('{$id}', '{$cp1}', '{$cp2}','{$cp3}')" title="Editar Registro">	<i class="bi bi-pencil-square text-primary"></i> </a>
		<a href="#" onclick="excluir('{$id}' , '{$cp1}', '{$cp3}')" title="Excluir Registro">	<i class="bi bi-trash text-danger"></i> </a>
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


function editar(id, cp1, cp2, cp3){
	$('#id').val(id);
	$('#<?=$campo1?>').val(cp1);
	$('#<?=$campo2?>').val(cp2);
	$('#<?=$campo3?>').val(cp3);
	
		
	$('#tituloModal').text('Editar Registro');
	var myModal = new bootstrap.Modal(document.getElementById('modalForm'), {		});
	myModal.show();
	$('#mensagem').text('');
}



function limparCampos(){
	$('#id').val('');
	$('#<?=$campo1?>').val('');
	$('#<?=$campo3?>').val('');

	$('#mensagem').text('');
	
}

</script>