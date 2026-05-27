<?php 
use app\controllers\ContasPagar;
@session_start();
@$nivel_usu = $_SESSION['nivel'];
$id_usuario = $_SESSION['id'];
//var_dump($nivel_usu);
$pagina = '?router=ControleCaixa';
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
$campo13 = 'Status';
$campo14 = 'Desconto';
$campo15 = 'Multa';
$campo16 = 'Juros'

//include_once 'recorrente.php'

 ?>

<div class="row my-3">
	<div class="col-md-9 container-fluid mb-4 mx-4">
		
		

		<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Inicial" class="bi bi-calendar-date"></i></small></span></div>
		<div style="float:left; margin-right:20px">
			<input type="date" class="form-control form-control-sm" name="data-inicial"  id="data-inicial" value="<?php echo date('Y-m-d') ?>" required>
		</div>

		<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Final" class="bi bi-calendar-date"></i></small></span></div>
		<div style="float:left; margin-right:40px">
			<input type="date" class="form-control form-control-sm" name="data-final"  id="data-final" value="<?php echo date('Y-m-d') ?>" required>
		</div>


		<div style="float:left; margin-right:10px"><span><small><i title="Filtrar por Status" class="bi bi-search"></i></small></span></div>
		<div style="float:left; margin-right:10px">
			<select class="form-select form-select-sm" aria-label="Default select example" name="status-busca" id="status-busca">
				<option value="">Entrada / Saída</option>
				<option value="Entrada">Entrada</option>
				<option value="Saida">Saída</option>
				
			</select>
		</div>

		<small class="mx-4">
			<a title="Contas à Pagar Vencidas" class="text-muted" href="#" onclick="listarContasVencidas('Vencidas')"><span>Vencidas</span></a> / 
			<a title="Contas à Pagar Hoje" class="text-muted" href="#" onclick="listarContasVencidas('Hoje')"><span>Hoje</span></a> / 
			<a title="Contas à Pagar Amanhã" class="text-muted" href="#" onclick="listarContasVencidas('Amanha')"><span>Amanhã</span></a> /
            <a title="Contas à Pagar Amanhã" class="text-muted" onclick="listarContasVencidas('relatorio')" href="?router=ControleCaixa/relProdutos_class" target="_blank" ><span>Relatório</span></a>
		</small>

		
	</div>

	<div align="right" class="col-md-2">
		<small><i class="bi bi-cash text-danger"></i> <span class="text-dark">Total: <span class="text-danger" id="total_itens"></span></span></small>
	</div>
</div>


<div class="container-fluid mb-4 mx-4">

<small>
	<div class="tabela bg-light" id="listar">

	</div>
</small>
</div>

</div>
<script type="text/javascript">var pag = "<?=$pagina?>"</script>



<script>
$(document).ready(function() {
    relControle();
    
  
} );
	
		$('#data-inicial').change(function(){
			var dataInicial = $('#data-inicial').val();
			var dataFinal = $('#data-final').val();
			var status = $('#status-busca').val();
			var alterou_data = 'Sim';
			listarBusca(dataInicial, dataFinal, status, alterou_data);
		});

		$('#data-final').change(function(){
			var dataInicial = $('#data-inicial').val();
			var dataFinal = $('#data-final').val();
			var status = $('#status-busca').val();
			var alterou_data = 'Sim';
			listarBusca(dataInicial, dataFinal, status, alterou_data);
		});

		$('#status-busca').change(function(){
			var dataInicial = $('#data-inicial').val();
			var dataFinal = $('#data-final').val();
			var status = $('#status-busca').val();
			listarBusca(dataInicial, dataFinal, status);
		});


    


	
function listarBusca(dataInicial, dataFinal, status, alterou_data){
    $.ajax({
        url: pag + "/relControle",
        method: 'POST',
        data: {dataInicial, dataFinal, status, alterou_data},
        dataType: "html",

        success:function(result){
            $("#listar").html(result);
        }
    });
}



function listarContasVencidas(vencidas){
    $.ajax({
        url: pag + "/relControle",
        method: 'POST',
        data: {vencidas},
        dataType: "html",

        success:function(result){
            $("#listar").html(result);
        }
    });
}


function listarContasHoje(hoje){
    $.ajax({
        url: pag + "/relControle",
        method: 'POST',
        data: {hoje},
        dataType: "html",

        success:function(result){
            $("#listar").html(result);
        }
    });
}


function listarContasAmanha(amanha){
    $.ajax({
        url: pag + "/relControle",
        method: 'POST',
        data: {amanha},
        dataType: "html",

        success:function(result){
            $("#listar").html(result);
        }
    });
}


function totalizar(){
	valor = $('#valor-baixar').val();
	desconto = $('#valor-desconto').val();
	juros = $('#valor-juros').val();
	multa = $('#valor-multa').val();

	valor = valor.replace(",", ".");
	desconto = desconto.replace(",", ".");
	juros = juros.replace(",", ".");
	multa = multa.replace(",", ".");

	subtotal = parseFloat(valor) + parseFloat(juros) + parseFloat(multa) - parseFloat(desconto);

	
	console.log(subtotal);

	$('#subtotal').val(subtotal);

}

function atualizalista(){

location. reload('#lista')
}

function relControle(){
		var pag = "<?=$pagina?>";
		$.ajax({
			url: pag + "/relControle",
			method: 'POST',
			data: $('#form').serialize(),
			dataType: "text",
			success:function(result){
				$("#listar").html(result);

			
			}

		});
	}


</script>
