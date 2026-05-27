<?php
use app\controllers\ContasPagar;
use app\controllers\LancaDespesas;

@session_start();
@$nivel_usu = $_SESSION['nivel'];
$id_usuario = $_SESSION['id'];
//VARIAVEIS DOS INPUTS
$campo1 = 'descricao';
@$campo2 = 'Cliente';
$campo3 = 'Saida';
$campo4 = 'Documento';
$campo5 = 'plano_conta';
$campo6 = 'data_emissao';
$campo7 = 'Vencimento';
$campo8 = 'Frequencia';
$campo9 = 'Valor';
$campo10 = 'usuario_lanc';
$campo11 = 'usuario_baixa';
$campo13 = 'Status';
$campo14 = 'Desconto';
$campo15 = 'Multa';
$campo16 = 'Juros';
$classe2 = "";
$total_valor = 0.00;
$total_valorF = 0.00;

//var_dump($nivel_usu);

echo <<<HTML
<table id="example2" class="table table-striped table-light table-hover my-4">
<thead>
<tr>
<th>Descrição</th>
<th>Saída</th>		
<th>Plano de Conta</th>	
<th>Multa/R$</th>	
<th>Desc/R$</th>	
<th>Juros/R$</th>	
<th>Vencimento</th>	
<th>Frequência</th>	
<th>Valor</th>
<th>Ações</th>
</tr>
</thead>
<tbody>
HTML;


$listar = new LancaDespesas;
@$res = $listar->listarLanca();
for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 
//var_dump($res);
$id = $res[$i]['id'];
$cp1 = $res[$i]['descricao'];
$cp2 = $res[$i]['cliente'];
$cp3 = $res[$i]['saida'];
$cp4 = $res[$i]['documento'];
$cp5 = $res[$i]['plano_conta'];
$cp6 = $res[$i]['data_emissao'];
$cp7 = $res[$i]['vencimento'];
$cp8 = $res[$i]['frequencia'];
@$cp9 = $res[$i]['valor'];
$cp10 = $res[$i]['usuario_lanc'];
$cp11 = $res[$i]['usuario_baixa'];
$cp13 = $res[$i]['status'];
@$cp18 = $res[$i]['data_baixa'];
@$cp19 = $res[$i]['juros'];
@$cp20 = $res[$i]['multa'];
@$cp21 = $res[$i]['desconto'];
@$cp14 = $res[$i]['descontoporc'];
@$cp15 = $res[$i]['multaporc'];
@$cp16 = $res[$i]['jurosporc'];
@$cp22 = $res[$i]['fotos'];



//Calcula o juros para valor paracial e valor total
@$valorj = $cp9 + $cp19 + $cp20 - $cp21;

if($cp13 == 'Paga'){
	$classe = 'text-success';
	$ocutar = 'd-none';
}else{
	$classe = 'text-danger';
	$total_valor += $cp9;
	$total_valorF = number_format($total_valor, 2, ',', '.');
	$ocutar = '';
}


	    $pdo = $listar->conectar();
        
		$query1 = $pdo->query("SELECT * from usuarios where id = '$cp10' ");
		$res1 = $query1->fetchAll(PDO::FETCH_ASSOC);
		if(@count($res1) > 0){
			$nome_usu_lanc = $res1[0]['nome_usu'];
		}else{
			$nome_usu_lanc = 'Sem Usuário';
		}

		$query1 = $pdo->query("SELECT * from usuarios where id = '$cp11' ");
		$res1 = $query1->fetchAll(PDO::FETCH_ASSOC);
		if(@count($res1) > 0){
			$nome_usu_baixa = $res1[0]['nome_usu'];
		}else{
			$nome_usu_baixa = 'Sem Usuário';
		}

		$query1 = $pdo->query("SELECT * from clientes where id = '$cp2' ");
		$res1 = $query1->fetchAll(PDO::FETCH_ASSOC);
		if(@count($res1) > 0){
			$nome_cliente = $res1[0]['nome'];
		}else{
			$nome_cliente = 'Sem Cliente';
		}
			$descricao = $cp1;

		$data_emissao = implode('/', array_reverse(explode('-', $cp6)));
		$data_venc = implode('/', array_reverse(explode('-', $cp7)));

		$valor = number_format($cp9, 2, ',', '.');
         

		//PEGAR RESIDUOS DA CONTA
		$total_resid = 0;
		$valor_com_residuos = 0;
		$pdo = $listar->conectar();
		$query2 = $pdo->query("SELECT * FROM valor_parcial WHERE id_conta = '$id'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if(@count($res2) > 0){
	
		for($i2=0; $i2 < @count($res2); $i2++){
		foreach ($res2[$i2] as $key => $value){} 
			$id_res = $res2[$i2]['id'];
			$valor_resid = $res2[$i2]['valor'];
			$total_resid += $valor_resid;
		}


		$valor_com_residuos = $cp9 + $total_resid;
	}
		if($valor_com_residuos > 0){
			$vlr_antigo_conta = '('.$valor_com_residuos.')';
			$descricao_link = '';
			$descricao_texto = 'd-none';
		}else{
			$vlr_antigo_conta = '';
			$descricao_link = 'd-none';
			$descricao_texto = '';
		}
		echo <<<HTML
		<tr>
		<td>
		<i class="bi bi-square-fill $classe"></i>
		<span class="{$descricao_link}">
		<a href="#" onclick="mostrarResiduos('{$id}')" class="text-dark" title="Ver Resíduos">{$descricao}</a>
		</span>
		<span class="{$descricao_texto}">
		{$descricao}
		</span>
		</td>		
		<td>{$cp3}</td>	
		<td>{$cp5}</td>	
		<td>{$cp20}</td>	
		<td>{$cp21}</td>	
		<td>{$cp19}</td>	
		<td>{$data_venc}</td>	
		<td>{$cp8}</td>	
		<td>R$ {$valor} <small><a href="#" onclick="mostrarResiduos('{$id}')" class="text-primary" title="Ver Resíduos">{$vlr_antigo_conta}</a></small></td>	
									
		<td>
		<a href="#" onclick="editar('{$id}', '{$cp1}', '{$cp2}', '{$cp3}', '{$cp4}', '{$cp5}', '{$cp6}', '{$cp7}', '{$cp8}', '{$cp9}','{$cp14}', '{$cp15}', '{$cp16}', '{$nome_cliente}')" title="Editar Registro">	<i class="bi bi-pencil-square text-primary {$ocutar}"></i> </a>
		<a href="#" onclick="excluir('{$id}' , '{$cp1}')" title="Excluir Registro">	<i class="bi bi-trash text-danger {$ocutar}"></i> </a>
	
		<a class="mx-1" href="#" onclick="mostrarDados('{$id}', '{$cp1}', '{$nome_cliente}', '{$cp3}', '{$cp4}', '{$cp5}', '{$data_emissao}', '{$data_venc}', '{$cp8}', '{$valor}', '{$nome_usu_lanc}', '{$nome_usu_baixa}', '{$cp13}', '$cp18')" title="Ver Dados da Conta">
		<i class="bi bi-binoculars"></i></a>
		
		<a href="#" onclick="parcelar('{$id}' , '{$cp1}', '{$cp9}')" title="Parcelar Conta">	<i class="bi bi-calendar-week text-secondary {$ocutar}"></i> </a>
	
		<a href="#" onclick="baixar('{$id}' , '{$cp1}', '{$cp9}', '{$cp3}', '{$valorj}','{$cp21}','{$cp20}','{$cp19}')" title="Dar Baixa">	<i class="bi bi-check-square text-success mx-1 {$ocutar}"></i> </a>
		
		<a href="#" onclick="fotos('{$id}')" title="Comprovante">	<i class="bi bi-upload text-success mx-1 {$ocutar}"></i> </a>
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
	$('#example2').DataTable({
		"ordering": false
	});

	$('#total_itens').text('R$ <?=$total_valorF?>');
} );


function editar(id, cp1, cp2, cp3, cp4, cp5, cp6, cp7, cp8, cp9, cp14, cp15, cp16, nome){

	$('#id').val(id);
	$('#<?=$campo1?>').val(cp1);
	//$('#<?=$campo2?>').val(cp2);
	$('#<?=$campo3?>').val(cp3);
	$('#<?=$campo4?>').val(cp4);
	
	$('#<?=$campo6?>').val(cp6);
	$('#<?=$campo7?>').val(cp7);
	$('#<?=$campo8?>').val(cp8);
	$('#<?=$campo9?>').val(cp9);
	$('#<?=$campo14?>').val(cp14);
	$('#<?=$campo15?>').val(cp15);
	$('#<?=$campo16?>').val(cp16);
	
	$('#nome-cliente').val(nome);
	$('#id-cliente').val(cp2);

	var usuario = "<?=$nivel_usu?>";
	if(usuario != 'Administrador'){
		document.getElementById("<?=$campo9?>").readOnly = true;
	}
	
	//var plano = cp5.split("-");

	$('#cat_despesas').val(cp5)
	$('#cat_despesas').val(cp5)
	listarDespesas(cp5)
	//$('#<?=$campo5?>').val(plano[0].trim());
		
	$('#tituloModal').text('Editar Registro');
	var myModal = new bootstrap.Modal(document.getElementById('modalForm'), {		});
	myModal.show();
	$('#mensagem').text('');
}



function limparCampos(){
	$('#id').val('');
	
	$('#<?=$campo1?>').val('');
	$('#<?=$campo9?>').val('');
	$('#id-cliente').val('');
	$('#nome-cliente').val('');
	$('#mensagem').text('');
	$('#<?=$campo14?>').val('');
	$('#<?=$campo15?>').val('');
	$('#<?=$campo16?>').val('');
	

	$('#usuario_adm').val('');
	$('#senha_adm').val('');
	document.getElementById("<?=$campo9?>").readOnly = false;
}


function mostrarDados(id, cp1, cp2, cp3, cp4, cp5, cp6, cp7, cp8, cp9, cp10, cp11, cp13, cp18){
	
	$('#campo1').text(cp1);
	$('#campo2').text(cp2);
	$('#campo3').text(cp3);
	$('#campo4').text(cp4);
	$('#campo5').text(cp5);
	$('#campo6').text(cp6);
	$('#campo7').text(cp7);
	$('#campo8').text(cp8);
	$('#campo9').text(cp9);
	$('#campo10').text(cp10);
	$('#campo11').text(cp11);
	$('#campo13').text(cp13);
	$('#campo18').text(cp18);
		
	
	var myModal = new bootstrap.Modal(document.getElementById('modalDados'), {		});
	myModal.show();
	
}



function mostrarResiduos(id){

	 $.ajax({
        url: pag + "/listar_residuos",
        method: 'POST',
        data: {id},
        dataType: "html",
           
        success:function(result){
            $("#listar_residuos").html(result);
        }
		
    });
	console.log(id);
	var myModal = new bootstrap.Modal(document.getElementById('modalResiduos'), {		});
	myModal.show();
	$('#mensagem').text('');
}

function parcelar(id, descricao, valor){
    $('#id-parcelar').val(id);
    $('#descricao-parcelar').text(descricao);
    $('#valor-parcelar').val(valor);
    $('#qtd-parcelar').val('');

    
    var myModal = new bootstrap.Modal(document.getElementById('modalParcelar'), {       });
    myModal.show();
    $('#mensagem-parcelar').text('');
}

function baixar(id, descricao, valor, saida,  valorj, desconto, multa, juros){
        $('#id-baixar').val(id);
        $('#descricao-baixar').text(descricao);
        $('#valor-baixar').val(valor);
        $('#saida-baixar').val(saida);
         $('#subtotal').val(valorj);
    
        $('#valor-multa').val(multa);
        $('#valor-desconto').val(desconto);
        $('#valor-juros').val(juros);
		
        var myModal = new bootstrap.Modal(document.getElementById('modalBaixar'), {       });
        myModal.show();
        $('#mensagem-baixar').text('');
    }


	function fotos(id){
		$('#id_contas').val(id);
        var myModal = new bootstrap.Modal(document.getElementById('modalFotos'), {       });
        myModal.show();
        $('#mensagem-fotos').text('');
    }
       




</script>
