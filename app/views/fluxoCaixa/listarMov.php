<?php

use app\models\CrudMov;

$campo1 = 'Tipo';
$campo2 = 'Lançamento';
$campo3 = 'Descrição';
$campo4 = 'Valor';
$campo5 = 'Usuários';
$campo6 = 'Data';
$campo7 = 'Caixa';
$campo8 = 'Plando de contas';
$campo9 = 'Documento';
$campo10 = 'Caixa';

$valorTtalEntrada  = 0.0;
$valorTotalSaida = 0.0;

echo <<<HTML
<table id="example2" class="table table-light table-hover my-4">
<thead>
<tr>
<th>{$campo1}</th>
<th>{$campo2}</th>
<th>{$campo3}</th>	
<th>{$campo4}</th>	
<th>{$campo5}</th>	
<th>{$campo6}</th>	
<th>{$campo7}</th>	
<th>{$campo8}</th>	
<th>{$campo9}</th>												
<th>{$campo10}</th>	
</tr>
</thead>
<tbody>
HTML;

$lista = new CrudMov;
$erroCarregamento = false;

try {
	$res = $lista->listarMov();
} catch (\Throwable $erro) {
	// Movimentacao: exibe erro amigavel quando o banco estiver indisponivel em vez de deixar a tela vazia.
	error_log($erro->getMessage());
	$res = [];
	$erroCarregamento = true;
	echo <<<HTML
	<tr>
	<td colspan="10" class="text-center text-danger py-4">Nao foi possivel carregar as movimentacoes. Verifique se o MySQL/XAMPP esta ativo.</td>
	</tr>
	HTML;
}

if (!$erroCarregamento && empty($res)) {
	// Movimentacao: mensagem clara quando o filtro nao encontrar registros.
	echo <<<HTML
	<tr>
	<td colspan="10" class="text-center text-muted py-4">Nenhuma movimentacao encontrada para o filtro selecionado.</td>
	</tr>
	HTML;
}

for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 

		$id = $res[$i]['id'];
		$cp1 = $res[$i]['tipo'];
		$cp2 = $res[$i]['movimento'];
		$cp3 = $res[$i]['descricao'];
		$cp4 = $res[$i]['valor'];
		$cp5 = $res[$i]['nome_usu'];
		$cp6 = $res[$i]['data'];
		$cp7 = $res[$i]['lancamento'];
		$cp8 = $res[$i]['nome_desp'];
		$cp9 = $res[$i]['nome_fpg'];
		$cp10 = $res[$i]['caixa_periodo'];
		$cp11 = $res[$i]['E'];
		$cp12 = $res[$i]['S'];

        $data_mov = implode('/', array_reverse(explode('-', $cp6)));
		
		$valor = number_format($cp4, 2, ',', '.');
       
	

		if($cp1 == 'Saida'){
            @$classe = 'text-danger'; 
			$valorTotalSaida += $cp12;
			@$valorTotalSaidaF = number_format($valorTotalSaida, 2, ',', '.');
			
		}if($cp1 == 'Entrada'){
			@$classe = 'text-success';
			$valorTtalEntrada += $cp11;
			@$valorTotalEntradaF = number_format($valorTtalEntrada, 2, ',', '.');
		
		}if($cp1 == 'Despesas'){
            @$classe = 'text-danger'; 
			$valorTotalSaida += $cp12;
			@$valorTotalSaidaF = number_format($valorTotalSaida, 2, ',', '.');
		}
		
echo <<<HTML
	<tr class= $classe>
	<td>{$cp1}</td>		
	<td>{$cp2}</td>	
	<td>{$cp3}</td>	
	<td> {$valor}</td>	
	<td>{$cp5}</td>	
	<td>{$data_mov}</td>	
	<td>{$cp7}</td>	
	<td>{$cp8}</td>	
	<td>{$cp9}</td>	
	<td>{$cp10}</td>	
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
	// Movimentacao: protege a tabela caso o plugin DataTables ainda nao esteja disponivel.
	if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#example2')) {
		$('#example2').DataTable({
			"ordering": false
		});
	}
	$('#total_saida').text('R$ <?=@$valorTotalSaidaF ?: '0,00'?>');
		
} );

$(document).ready(function() {
	$('#total_entrada').text('R$ <?=@$valorTotalEntradaF ?: '0,00'?>');
		
		});

	

</script>
