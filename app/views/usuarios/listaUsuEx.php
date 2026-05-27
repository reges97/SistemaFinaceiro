<?php
use app\models\CrudUsuarios;

$campo1 = 'Nome';
$campo2 = 'Email';
$campo4 = 'Nivel';

if (!function_exists('h')) {
	function h($valor)
	{
		return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
	}
}

echo <<<HTML
<table id="example" class="table table-striped table-light table-hover my-4">
<thead>
<tr>
<th>{$campo1}</th>
<th>{$campo2}</th>
<th>{$campo4}</th>
</tr>
</thead>
<tbody>
HTML;

$listar = new CrudUsuarios;
$res = $listar->listarUsu();
for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){}

	$cp1 = h($res[$i]['nome_usu']);
	$cp2 = h($res[$i]['email']);
	$cp4 = h($res[$i]['nivel']);

	echo <<<HTML
	<tr>
	<td>{$cp1}</td>
	<td>{$cp2}</td>
	<td>{$cp4}</td>
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
});
</script>
