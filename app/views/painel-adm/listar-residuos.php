<?php

use app\controllers\ContasPagar;

$pagina = 'contas_pagar';
//VARIAVEIS DOS INPUTS
$campo1 = 'descricao';
$campo2 = 'Cliente';
$campo3 = 'Saida';
$campo4 = 'Documento';
$campo5 = 'plano_conta';
$campo6 = 'data_emissao';
$campo7 = 'Vencimento';
$campo8 = 'Frequencia';
$campo9 = 'Valor';
$campo10 = 'usuario_lanc';
$campo11 = 'usuario_baixa';
$campo12 = 'Caixa';
$campo13 = 'data_recor';
$campo14 = 'juros';
$campo15 = 'Multa';
$campo16 = 'Desconto';
$campo17 = 'SubTotal';
$campo18 = 'data_baixa';
//$id = $_POST['id'];

echo <<<HTML
<table id="example_res" class="table table-striped table-light table-hover my-4">
<thead>
<tr>
<th>Valor</th>
<th>Data</th>								
<th>Usuário</th>
</tr>
</thead>
<tbody>
HTML;

$listar = new ContasPagar;
$res = $listar->listarResiduos();
for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 

		$valor = $res[$i]['valor'];
		$data = $res[$i]['data'];
		$usuario = $res[$i]['usuario'];

        $con = new ContasPagar;
        $pdo = $con->conectar();

		$query2 = $pdo->query("SELECT * from usuarios where id = '$usuario'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$nome_usuario = $res2[0]['nome_usu'];

		$data = implode('/', array_reverse(explode('-', $data)));

		$valor = number_format($valor, 2, ',', '.');

	

echo <<<HTML
	<tr>
	<td>R$ {$valor}</td>		
	<td>{$data}</td>							
	<td>{$nome_usuario}</td>	
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
	$('#example_res').DataTable({
		"ordering": false
	});

} );



</script>