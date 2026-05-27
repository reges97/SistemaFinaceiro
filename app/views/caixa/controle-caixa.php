<?php 
use app\controllers\ContasPagar;
@session_start();
@$nivel_usu = $_SESSION['nivel'];
$id_usuario = $_SESSION['id'];
//var_dump($nivel_usu);
$pagina = '?router=ControleCaixa';
//VARIAVEIS DOS INPUTS
$campo1 = 'Data Inicial';
$campo2 = 'Data Final';


//include_once 'recorrente.php'

 ?>

<div class="row my-3">
<div class="row">
	
    <div class="col-md-12 container-fluid mb-4 mx-4">
		
		

		<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Inicial" class="bi bi-calendar-date"></i></small></span></div>
		<div style="float:left; margin-right:20px">
		<small><label for="exampleFormControlInput1" class="form-label">Data Inicial:</label></small>	
			<input type="date" class="form-control form-control-sm" name="data-inicial"  id="data-inicial" value="<?php echo date('Y-m-d') ?>" required>
		</div>

		<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Final" class="bi bi-calendar-date"></i></small></span></div>
		<div style="float:left; margin-right:40px">
		<small><label for="exampleFormControlInput1" class="form-label">Data Inicial:</label></small>	
			<input type="date" class="form-control form-control-sm" name="data-final"  id="data-final" value="<?php echo date('Y-m-d') ?>" required>
		</div>


		<div style="float:left; margin-right:10px"><span><small><i title="Filtrar por Status" class="bi bi-search"></i></small></span></div>
		<div style="float:left; margin-right:10px">
		<small><label for="exampleFormControlInput1" class="form-label">Tipo:</label></small>	
			<select class="form-select form-select-sm" aria-label="Default select example" name="status-busca" id="status-busca">
				<option value="">Entrada / Saída</option>
				<option value="Entrada">Entrada</option>
				<option value="Saida">Saída</option>
				
			</select>
		</div>

	</div>
		<div class="col-md-12 container-fluid mb-4 mx-4">
	<div class="col-md-12">
	<small class="mx-4">
		
	<div style="float:left; margin-right:10px"><a title="Contas à Pagar Vencidas" class="text-muted" href="#" onclick="listarContasVencidas('Vencidas')"><span>Vencidas</span></a> / </div>
	<div style="float:left; margin-right:10px">	<a title="Contas à Pagar Hoje" class="text-muted" href="#" onclick="listarContasVencidas('Hoje')"><span>Hoje</span></a> / </div>
	<div style="float:left; margin-right:10px">	<a title="Contas à Pagar Amanhã" class="text-muted" href="#" onclick="listarContasVencidas('Amanha')"><span>Amanhã</span></a> /</div>
	<div style="float:left; margin-right:10px"> <a title="Contas à Pagar Amanhã" class="text-muted" href="#" onclick="relatorio()"><span>Relatório</span></a></div>
			</small>
		</div>
       </div>

	<div class="col-md-12 container-fluid mb-4 mx-4">
   <div class="col-md-12">
		<small><i class="bi bi-cash text-danger"></i> <span class="text-dark">Total: <span class="text-danger" id="total_itens"></span></span></small>
	</div>
</div>
</div>

<div class="container-fluid mb-4 mx-4">

<small>
	<div class="tabela bg-light" id="listar">

	</div>
</small>
</div>

</div>

<!-- Modal -->
<div class="modal fade" id="modalForm2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal2"></span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="form2" method="post" action="?router=ControleCaixa/relCaixa_class" target="_blank">
				<div class="modal-body">
				<div class="row">
				<div class="col-md-4 col-sm-12">
					<div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label"><?php echo $campo1 ?></label>
						<input type="date" class="form-control" name="dataInicial" placeholder="<?php echo $campo1 ?>" id="dataInicial" required>
					</div>
					</div>	
					<div class="col-md-4 col-sm-12">
					<div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label"><?php echo $campo2 ?></label>
						<input type="date" class="form-control" name="dataFinal" placeholder="<?php echo $campo2 ?>" id="dataFinal" required>
					</div>
					</div>
					
					<small><div id="mensagem" align="center"></div></small>

				
				</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar">Fechar</button>
					<button type="submit" class="btn btn-primary">Gerar</button>
				</div>
			</form>
		</div>
	</div>
</div>


<script type="text/javascript">var pag = "<?=$pagina?>"</script>
<script src="config/js/ajax.js"></script>


<script>
	
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
        url: pag + "/listar",
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
        url: pag + "/listar",
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
        url: pag + "/listar",
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
        url: pag + "/listar",
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


function relatorio(){
    
    $('#mensagem').text('');
    $('#tituloModal2').text('Gerar Relatório');
    var myModal = new bootstrap.Modal(document.getElementById('modalForm2'), {
        backdrop: 'static',
    });
    myModal.show();
    limparCampos();
}



	


</script>
