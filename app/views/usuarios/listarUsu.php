<?php
use app\models\CrudUsuarios;
use app\models\Permissoes;

$campo1 = 'Nome';
$campo2 = 'Email';
$campo3 = 'Senha';
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
<th>Acoes</th>
</tr>
</thead>
<tbody>
HTML;

$listar = new CrudUsuarios;
$res = $listar->listarUsu();
for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){}

	$id = (int) $res[$i]['id'];
	$cp1 = h($res[$i]['nome_usu']);
	$cp2 = h($res[$i]['email']);
	// Exibe o perfil efetivo para deixar claro quais permissoes o usuario tera no sistema.
	$cp4 = h(Permissoes::normalizarNivel($res[$i]['nivel']));

	echo <<<HTML
	<tr>
	<td>{$cp1}</td>
	<td>{$cp2}</td>
	<td>{$cp4}</td>
	<td>
		<a href="#" data-id="{$id}" data-nome="{$cp1}" data-email="{$cp2}" data-nivel="{$cp4}" onclick="editarUsuario(this); return false;" title="Editar Registro"><i class="bi bi-pencil-square text-primary"></i></a>
		<a href="#" data-id="{$id}" data-nome="{$cp1}" onclick="excluirUsuario(this); return false;" title="Excluir Registro"><i class="bi bi-trash text-danger"></i></a>
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
});

function editarUsuario(el){
	$('#id').val($(el).data('id'));
	$('#<?=$campo1?>').val($(el).data('nome'));
	$('#<?=$campo2?>').val($(el).data('email'));
	$('#<?=$campo3?>').val('').prop('required', false);
	$('#<?=$campo4?>').val($(el).data('nivel'));

	$('#tituloModal').text('Editar Registro');
	var myModal = new bootstrap.Modal(document.getElementById('modalForm'), {});
	myModal.show();
	$('#mensagem').text('');
}

function excluirUsuario(el){
	excluir($(el).data('id'), $(el).data('nome'));
}

function limparCampos(){
	$('#id').val('');
	$('#<?=$campo1?>').val('');
	$('#<?=$campo2?>').val('');
	$('#<?=$campo3?>').val('').prop('required', true);

	$('#mensagem').text('');
}

</script>
