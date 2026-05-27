<?php
use app\models\CrudAcessos;

$campo1 = 'Usuario';
$campo2 = 'Menu';
$campo3 = 'Nivel';
$campo4 = 'Acesso';

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
<th>{$campo3}</th>
<th>{$campo4}</th>
<th>Acoes</th>
</tr>
</thead>
<tbody>
HTML;

$listar = new CrudAcessos;
$res = $listar->listarAcesso();
for($i=0; $i < @count($res); $i++){
	$id = (int) $res[$i]['id_aces'];
	$usuId = (int) $res[$i]['usu_id'];
	$menuId = (int) $res[$i]['menu_id'];
	$usuario = h($res[$i]['nome_usu'] ?: 'Usuario removido');
	$menu = h($res[$i]['menu'] ?: 'Menu removido');
	$nivel = h($res[$i]['nivel_aces']);
	$acesso = h($res[$i]['acesso']);

	// Exibicao legivel: mostra nomes em vez de ids para reduzir erro ao revisar permissoes.
	echo <<<HTML
	<tr>
	<td>{$usuario}</td>
	<td>{$menu}</td>
	<td>{$nivel}</td>
	<td>{$acesso}</td>
	<td>
		<a href="#" data-id="{$id}" data-usuario="{$usuId}" data-menu="{$menuId}" data-nivel="{$nivel}" data-acesso="{$acesso}" onclick="editarAcesso(this); return false;" title="Editar Registro"><i class="bi bi-pencil-square text-primary"></i></a>
		<a href="#" onclick="excluir('{$id}' , '{$usuario}'); return false;" title="Excluir Registro"><i class="bi bi-trash text-danger"></i></a>
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

function editarAcesso(el){
	$('#id').val($(el).data('id'));
	$('#Nome').val($(el).data('usuario'));
	$('#Menu').val($(el).data('menu'));
	$('#Nivel').val($(el).data('nivel'));
	$('#Acesso').val($(el).data('acesso'));

	$('#tituloModal').text('Editar Registro');
	var myModal = new bootstrap.Modal(document.getElementById('modalForm'), {});
	myModal.show();
	$('#mensagem').text('');
}

function limparCampos(){
	$('#id').val('');
	$('#Nome').val('');
	$('#Menu').val('');
	$('#Nivel').val('');
	$('#Acesso').val('Nao');
	$('#Sub_menu').val('');
	$('#mensagem').text('');
}
</script>
