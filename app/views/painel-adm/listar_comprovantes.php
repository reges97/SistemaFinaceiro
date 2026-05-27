<?php

use app\controllers\ContasPagar;

$pagina = 'fotos';
//VARIAVEIS DOS INPUTS

//$id = $_POST['id'];

echo <<<HTML
<table id="example_comprovantes" class="table table-striped table-light table-hover my-4">
<thead>
<tr>
<th>Numero Agenda</th>
<th>Fotos</th>								
<th>Ações</th>
</tr>
</thead>
<tbody>
HTML;

$listar = new ContasPagar;
$res = $listar->listarComprovantes();
for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 

		$idfotos = $res[$i]['id_fotos'];
		$idcontas = $res[$i]['id_contas'];
		$fotos = $res[$i]['fotos'];

       

echo <<<HTML
	<tr>
	<td>{$idcontas}</td>		
	<td><a target="_blank" class="text-secondary" style="text-decoration:none" title="Abrir Arquivo" href="config/img/produtos/{$fotos}">{$fotos}</a></td>							
	<td><a href="#" onclick="excluir('{$idfotos}' , '{$fotos}')" title="Excluir Registro" data-bs-dismiss="modal">	
    <i class="bi bi-trash text-danger"></i> </a></td>

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
	$('#example_comprovantes').DataTable({
		"ordering": false
	});

} );



</script>
