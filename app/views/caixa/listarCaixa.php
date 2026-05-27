<?php
@session_start();
use app\controllers\Caixa;
$pagina = 'caixa';
//VARIAVEIS DOS INPUTS
$campo1 = 'data_ab';
$campo2 = 'valor_ab';
$campo3 = 'usuario_ab';
$campo4 = 'data_fec';
$campo6 = 'usuario_fec';
$campo7 = 'Saldo';
$campo8 = 'Status';

echo <<<HTML
<table id="example" class="table table-striped table-light table-hover my-4">
<thead>
<tr>
<th>{$campo8}</th>
<th>Data Abertura</th>	
<th>Saldo Inicial</th>	
<th>Movimentação Saldo</th>	
<th>Usuário Ab</th>	
<th>Data Fecham</th>	

<th>Usuário Fec</th>	
<th>Saldo final</th>
<th>Ações</th>
</tr>
</thead>
<tbody>
HTML;

$listar = new Caixa;
$res = $listar->listarCaixa();

for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 

		$id = $res[$i]['id'];
		$cp1 = $res[$i]['data_ab'];
		$cp2 = $res[$i]['valor_ab'];
		$cp3 = $res[$i]['usuario_ab'];
		$cp4 = $res[$i]['data_fec'];
		
		$cp6 = $res[$i]['usuario_fec'];
		$cp7 = $res[$i]['saldo'];
		$cp8 = $res[$i]['status'];
		$cp9 = $res[$i]['saldo_inicial'];

		if($cp8 == 'Aberto'){
			$classe = 'text-success';
		}else{
			$classe = 'text-danger';
			
		}


		//TOTALIZAR O SALDO
		$total_saldo = 0;

		$total_saldo = $cp7;

		if($cp2 < $cp7){
			$classe_saldo = 'text-success';
		}else if($cp2 > $cp7){
			$classe_saldo = 'text-danger';
		}else{
			$classe_saldo = 'text-primary';
		}
          $con = new Caixa;
          $pdo = $con->conectar();
         
		$query1 = $pdo->query("SELECT * from usuarios where id = '$cp3' ");
		$res1 = $query1->fetchAll(PDO::FETCH_ASSOC);
		if(@count($res1) > 0){
			$nome_usu_ab = $res1[0]['nome_usu'];
		}else{
			$nome_usu_ab = 'Sem Usuário';
		}

		$query1 = $pdo->query("SELECT * from usuarios where id = '$cp6' ");
		$res1 = $query1->fetchAll(PDO::FETCH_ASSOC);
		if(@count($res1) > 0){
			$nome_usu_fec = $res1[0]['nome_usu'];
		}else{
			$nome_usu_fec = 'Sem Usuário';
		}

		$data_ab = implode('/', array_reverse(explode('-', $cp1)));
		$data_fec = implode('/', array_reverse(explode('-', $cp4)));

		$valor_ab = number_format($cp2, 2, ',', '.');
		$total_saldo = number_format($total_saldo, 2, ',', '.');
		$saldo_inicial = number_format($cp9, 2, ',', '.');
		
echo <<<HTML
	<tr>
	<td>
	<i class="bi bi-square-fill $classe"></i>
	</td>	
	<td>{$data_ab}</td>	
	<td>R$ {$saldo_inicial}</td>		
	<td>R$ {$valor_ab}</td>	
	<td>{$nome_usu_ab}</td>	
	<td>{$data_fec}</td>	

	<td>{$nome_usu_fec}</td>
	<td class="{$classe_saldo}">R$ {$total_saldo}</td>									
	<td>
	<a href="#" onclick="editar('{$id}', '{$cp2}')" title="Editar Registro">	<i class="bi bi-pencil-square text-primary"></i> </a>
	<a href="#" onclick="excluir('{$id}' , '{$data_ab}')" title="Excluir Registro">	<i class="bi bi-trash text-danger"></i> </a>
	<a href="#" onclick="fechar('{$id}' , '{$data_ab}')" title="Fechar Caixa">	<i class="bi bi-cash text-success"></i> </a>
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


function editar(id, cp2){
	$('#id').val(id);
	$('#<?=$campo2?>').val(cp2);
		
	$('#tituloModal').text('Editar Registro');
	var myModal = new bootstrap.Modal(document.getElementById('modalForm'), {		});
	myModal.show();
	$('#mensagem').text('');
}



function limparCampos(){
	$('#id').val('');
	
	$('#<?=$campo2?>').val('');
	$('#mensagem').text('');
	
}


function fechar(id, cp1){
	$('#id-fechar').val(id);
	$('#data_abert').text(cp1);
	
	var myModal = new bootstrap.Modal(document.getElementById('modalFechar'), {		});
	myModal.show();
	$('#mensagem-fechar').text('');
}

</script>