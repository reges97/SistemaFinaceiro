<?php
use app\controllers\Conci;



//VARIAVEIS DOS INPUTS
$id = 'id';
$campo1 = 'Data';
$campo2 = 'Descricao';
$campo3 = 'Documento';
$campo4 = 'Observacao';
$campo5 = 'Valor';
$campo6 = 'Tipo';
$campo7 = 'Conta';
$campo8 = 'SaldoExterno';
$campo10 = 'Saldo Interno consolidado';

echo <<<HTML
<table id="example" class="table table-striped table-light table-hover my-4">
<thead>
<tr>
<th>{$campo1}</th>
<th>{$campo2}</th>
<th>{$campo3}</th>
<th>{$campo4}</th>
<th>{$campo5}</th>
<th>{$campo6}</th>
<th>{$campo7}</th>
<th>Saldo Atual</th>
<th>Saldo Externo</th>
<th>{$campo10}</th>
<th>Status</th>
<th>Diferenca</th>
<th>Acoes</th>
</tr>
</thead>
<tbody>
HTML;

$lista = new Conci;
$res = $lista->listarconci();

for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){}

		$id = $res[$i]['id'];
		$cp1 = $res[$i]['data'];
        // Saida protegida: evita HTML indevido sem quebrar registros antigos com acentos fora do padrao.
		$cp2 = htmlspecialchars($res[$i]['descricao'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
		$cp3 = htmlspecialchars($res[$i]['n_documento'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
		$cp4 = htmlspecialchars($res[$i]['observacao'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
		$cp5 = (float) $res[$i]['valor'];
		$cp6 = $res[$i]['tipo'];
		$cp7 = htmlspecialchars($res[$i]['banco'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $idConta = $res[$i]['id_conta'];
        $cp8 = (float) $res[$i]['saldo'];
		$cp9 = (float) $res[$i]['saldo_externo'];
		$cp10 = (float) ($res[$i]['saldo_interno'] ?? 0);
        $statusConci = $res[$i]['status'] ?? 'Pendente';

        // Aviso visual: diferenca gravada na conciliacao prevalece; pendente mostra previsao pela diferenca atual.
        $diferenca = $res[$i]['diferenca'] !== null ? (float) $res[$i]['diferenca'] : ($cp8 - $cp9);
        $statusClasse = $statusConci === 'Conciliado' ? 'success' : ($statusConci === 'Divergente' ? 'warning text-dark' : 'secondary');
        $diferencaClasse = abs($diferenca) > 0.01 ? 'text-danger fw-bold' : 'text-success';
        $registroAplicado = $statusConci === 'Conciliado' || $statusConci === 'Divergente';

		$cp10f = number_format($cp10, 2, ',', '.');
		$cp5f = number_format($cp5, 2, ',', '.');
        $saldof = number_format($cp8, 2, ',', '.');
		$saldoExtf = number_format($cp9, 2, ',', '.');
		$difef = number_format($diferenca, 2, ',', '.');
        $data = implode('/', array_reverse(explode('-', $cp1)));

        // Regra de tela: conciliacao aplicada fica somente leitura para evitar saldo duplicado.
        if($registroAplicado){
            $acoes = "<span title=\"Registro ja aplicado\"><i class=\"bi bi-pencil-square text-muted\"></i></span>
                <span title=\"Registro ja aplicado\"><i class=\"bi bi-trash text-muted mx-1\"></i></span>
                <span title=\"Registro ja aplicado\"><i class=\"bi bi-check-square text-muted mx-1\"></i></span>";
        } else {
            $acoes = "<a href=\"#\" onclick=\"editar('{$id}','{$cp1}','{$cp2}','{$cp3}','{$cp4}','{$cp5}','{$cp6}','{$idConta}', '{$cp9}')\" title=\"Editar Registro\"><i class=\"bi bi-pencil-square text-primary\"></i> </a>
                <a href=\"#\" onclick=\"excluir('{$id}','{$cp1}')\" title=\"Excluir Registro\"><i class=\"bi bi-trash text-danger\"></i> </a>
                <a href=\"#\" onclick=\"conciliar('{$id}','{$cp5f}')\" title=\"Conciliar\"><i class=\"bi bi-check-square text-success mx-1\"></i> </a>";
        }

echo <<<HTML
	<tr>
	<td>{$data}</td>
	<td>{$cp2}</td>
	<td>{$cp3}</td>
	<td>{$cp4}</td>
	<td>{$cp5f}</td>
	<td>{$cp6}</td>
	<td>{$cp7}</td>
    <td>{$saldof}</td>
	<td>{$saldoExtf}</td>
	<td>{$cp10f}</td>
    <td><span class="badge bg-{$statusClasse}">{$statusConci}</span></td>
    <td class="{$diferencaClasse}">{$difef}</td>
	<td>{$acoes}</td>
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


function editar(id, cp1, cp2, cp3, cp4, cp5, cp6, cp7, cp9){
	$('#id').val(id);
	$('#<?=$campo1?>').val(cp1);
	$('#<?=$campo2?>').val(cp2);
	$('#<?=$campo3?>').val(cp3);
	$('#<?=$campo4?>').val(cp4);
	$('#<?=$campo5?>').val(cp5);
	$('#<?=$campo6?>').val(cp6);
	$('#<?=$campo7?>').val(cp7);
	$('#<?=$campo8?>').val(cp9);

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

function conciliar(id, cp5){

	$('#id_conci').val(id);
	$('#conciliacao').text(cp5);
        var myModal = new bootstrap.Modal(document.getElementById('modalconci'), {       });
        myModal.show();
        $('#mensagem-conci').text('');
    }



</script>
