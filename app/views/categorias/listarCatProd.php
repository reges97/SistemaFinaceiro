<?php
use app\controllers\CatProd;
$pagina = 'cat_produtos';
//VARIAVEIS DOS INPUTS
$campo1 = 'Nome';


echo <<<HTML
<table id="example" class="table table-striped table-light table-hover my-4">
<thead>
<tr>
<th>{$campo1}</th>
<th>Produtos</th>								
<th>Ações</th>
</tr>
</thead>
<tbody>
HTML;

$listar = new CatProd;

$res = $listar->listarCatProd();

for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 

		$id = $res[$i]['id'];
		$cp1 = $res[$i]['nome'];

		$pdo = $listar->conectar();
		$query2 = $pdo->query("SELECT * from produtos where categoria = '$id'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_produtos = @count($res2);
	

echo <<<HTML
	<tr>
	<td>{$cp1}</td>		
	<td>{$total_produtos}</td>							
	<td>
	<a href="#" onclick="editar('{$id}', '{$cp1}')" title="Editar Registro">	<i class="bi bi-pencil-square text-primary"></i> </a>
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


function editar(id, nome, email, senha, nivel){
	$('#id').val(id);
	$('#<?=$campo1?>').val(nome);
		
	$('#tituloModal').text('Editar Registro');
	var myModal = new bootstrap.Modal(document.getElementById('modalForm'), {		});
	myModal.show();
	$('#mensagem').text('');
}



function limparCampos(){
	$('#id').val('');
	$('#<?=$campo1?>').val('');
	

	$('#mensagem').text('');
	
}

</script>