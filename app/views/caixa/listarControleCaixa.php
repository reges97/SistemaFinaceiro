<?php
use app\controllers\ControleCaixa;

$pagina = 'controle-caixa';
$campo1 = 'Data';
$campo2 = 'Movimento';
$campo3 = 'Entrada';
$campo4 = 'Saida';
$campo5 = 'Saldo';

$totalLiquido = 0.0;

echo <<<HTML
<table id="example" class="table table-striped table-light table-hover my-4">
<thead>
<tr>
<th>{$campo1}</th>
<th>{$campo2}</th>
<th>{$campo3}</th>
<th>{$campo4}</th>
<!-- Controle de caixa: coluna de acoes removida porque nao havia botao funcional nesta listagem. -->
<th>{$campo5}</th>
</tr>
</thead>
<tbody>
HTML;

$lista = new ControleCaixa;
try {
	$res = $lista->listarControle();
} catch (\Throwable $erro) {
	// Controle de caixa: erro amigavel evita tela vazia quando banco/consulta falhar.
	error_log($erro->getMessage());
	$res = [];
	echo <<<HTML
	<tr>
	<td colspan="5" class="text-center text-danger py-4">Nao foi possivel carregar o controle de caixa.</td>
	</tr>
	HTML;
}

if (empty($res)) {
	// Controle de caixa: mensagem clara quando nao houver registros no filtro atual.
	echo <<<HTML
	<tr>
	<td colspan="5" class="text-center text-muted py-4">Nenhum registro encontrado para o filtro selecionado.</td>
	</tr>
	HTML;
}

for ($i = 0; $i < @count($res); $i++) {
	$cp1 = $res[$i]['data'];
	// Controle de caixa: exibe a descricao resolvida do movimento em vez do id do plano de contas.
	$cp2 = $res[$i]['movimento_descricao'] ?? $res[$i]['movimento'];
	$cp3 = $res[$i]['entrada'];
	$cp4 = $res[$i]['saida'];
	$cp5 = $res[$i]['saldo'];

	// Controle de caixa: total do filtro e calculado por entradas menos saidas, nao pelo ultimo saldo da listagem.
	$totalLiquido += (float) $cp3 - (float) $cp4;

	$cp3 = number_format($cp3, 2, ',', '.');
	$cp4 = number_format($cp4, 2, ',', '.');
	$cp5 = number_format($cp5, 2, ',', '.');
	$cp1 = implode('/', array_reverse(explode('-', $cp1)));

	echo <<<HTML
	<tr>
	<td>{$cp1}</td>
	<td>{$cp2}</td>
	<td>{$cp3}</td>
	<td>{$cp4}</td>
	<td>{$cp5}</td>
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
	// Controle de caixa: evita reinicializar DataTable quando a tabela for recarregada via AJAX.
	if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#example')) {
		$('#example').DataTable({
			"ordering": false
		});
	}

	$('#total_itens').text('R$ <?=number_format($totalLiquido, 2, ',', '.')?>');
});
</script>
