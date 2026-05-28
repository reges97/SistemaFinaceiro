<?php
$pagina = '?router=Fluxo';
$campo1 = 'tip';
$campo2 = 'movimento';
$campo3 = 'valor';
$campo4 = 'tesoureiro';
$campo5 = 'data';
$campo6 = 'id_movimento';



?>
<div class="row my-3">

<div class="row">

	<div class="col-md-12 container-fluid mb-4 mx-4">
		

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
			<select class="form-select form-select-sm" aria-label="Default select example" name="tipo" id="tipo">
				<option value="">Entradas/Saídas</option>
				<option value="Entrada">Entrada</option>
				<option value="Saida">Saída</option>
				</select>
		</div>	
		
	</div>

	<div class="col-md-9 container-fluid mb-4 mx-4">
	<div class="col-md-12">
		<small class="mx-4">
		<div style="float:left; margin-right:10px"><a title="Contas à Pagar Vencidas" class="text-muted" href="#" onclick="listarContasEntrada('Entradas')"><span>Entradas</span></a> / </div>
		<div style="float:left; margin-right:10px"><a title="Contas à Pagar Hoje" class="text-muted" href="#" onclick="listarContasVencidas('Hoje')"><span>Hoje</span></a> / </div>
		<div style="float:left; margin-right:10px"><a title="Contas à Pagar Amanhã" class="text-muted" href="#" onclick="listarContasSaida('Saidas')"><span>Saida</span></a> / </div>
		<div style="float:left; margin-right:10px"><a title="Contas à Pagar Amanhã" class="text-muted" href="#" onclick="relatorio()"><span>Relatório</span></a> / </div>
		<div style="float:left; margin-right:10px">	<a title="Contas à Pagar Amanhã" class="text-muted" href="#" onclick="relatorioPdf()"><span>Relatório PDF</span></a>
		</small>

	</div>
	</div>

	</div>
	

	<div class="col-md-12 container-fluid mb-4 mx-4">
	<div class="col-md-12">
	
   <div style="float:left; margin-left:5px">
      <small><i class="bi bi-arrow-up-square-fill text-success"></i> <span class="text-dark">Entrada: <span class="text-success" id="total_entrada"></span></span></small>
   </div>
   <div style="float:right; margin-right:50px"> 
	  <small><i class="bi bi-arrow-down-square-fill text-danger"></i> <span class="text-dark">Saida: <span class="text-danger" id="total_saida"></span></span></small>
	</div>
	

	<small class="mx-2">
	<small>
	<div class="tabela bg-light" id="listar"></div>
    </small>
</small>
	
	</div>
	</div>
	
</div>
</div>
</div>


<!-- chama  Modal para gerar relatórios par ao excel  -->
<div class="modal fade" id="modalForm2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal2"></span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="form2" method="post" action="?router=Fluxo/gerar "target="">
				<div class="modal-body">
				<div class="row">
				<div class="col-md-4 col-sm-12">
					<div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label">Data Inicial</label>
						<input type="date" class="form-control" name="dataInicial" placeholder="<?php echo $campo1 ?>" id="dataInicial" required>
					</div>
					</div>	
					<div class="col-md-4 col-sm-12">
					<div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label">Data Final</label>
						<input type="date" class="form-control" name="dataFinal" placeholder="<?php echo $campo2 ?>" id="dataFinal" required>
					</div>
					</div>
					
					<small id="mensagem" class="d-block text-center"></small>

				
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



<div class="modal fade" id="modalForm3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal3"></span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="form2" method="post" action="?router=Fluxo/relfluxo_class  "target="_blank">
				<div class="modal-body">
				<div class="row">
				<div class="col-md-4 col-sm-12">
					<div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label">Data Inicial</label>
						<input type="date" class="form-control" name="dataInicial" placeholder="<?php echo $campo1 ?>" id="dataInicial" required>
					</div>
					</div>	
					<div class="col-md-4 col-sm-12">
					<div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label">Data Final</label>
						<input type="date" class="form-control" name="dataFinal" placeholder="<?php echo $campo2 ?>" id="dataFinal" required>
					</div>
					</div>
					
					<small id="mensagem" class="d-block text-center"></small>

				
				</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar">Fechar</button>
					<button type="submit" class="btn btn-primary"  target="_blank">Gerar</button>
				</div>
			</form>
		</div>
	</div>
</div>


<script type="text/javascript">var pag = "<?=$pagina?>"</script>
<script src="config/js/ajax.js"></script>

<script>
	$(document).ready(function() {
		var cat = $('#cat_despesas').val();
		listarDespesas(cat);
		listarForne();
		$('#cat_despesas').change(function(){
			var cat = $(this).val();
			listarDespesas(cat);
		});


		$('#data-inicial').change(function(){
			var dataInicial = $('#data-inicial').val();
			var dataFinal = $('#data-final').val();
			var tipo = $('#tipo').val();
			var alterou_data = 'Sim';
			listarBusca(dataInicial, dataFinal, tipo, alterou_data);
		});

		$('#data-final').change(function(){
			var dataInicial = $('#data-inicial').val();
			var dataFinal = $('#data-final').val();
			var tipo = $('#tipo').val();
			var alterou_data = 'Sim';
			listarBusca(dataInicial, dataFinal, tipo, alterou_data);
		});

		$('#tipo').change(function(){
			var dataInicial = $('#data-inicial').val();
			var dataFinal = $('#data-final').val();
			var tipo = $('#tipo').val();
			var alterou_data = 'Sim';
			listarBusca(dataInicial, dataFinal, tipo, alterou_data );
		});


});


	function listarDespesas(cat, despesa){
		var pag = "<?=$pagina?>";
		$.ajax({
			url: pag + "/listar_despesas",
			method: 'POST',
			data: {cat, despesa},
			dataType: "text",

			success:function(result){
				$("#listar-despesas").html(result);
			}

		});
	}


	function listarForne(){
		var pag = "<?=$pagina?>";
		$.ajax({
			url: pag + "/listar_forne",
			method: 'POST',
			data: $('#form').serialize(),
			dataType: "html",

			success:function(result){
				$("#listar-forne").html(result);
			}
		});
	}




function listarBusca(dataInicial, dataFinal, tipo, alterou_data){
    $.ajax({
        url: pag + "/listar",
        method: 'POST',
        data: {dataInicial, dataFinal, tipo, alterou_data},
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


function ativa(ativar){
    $.ajax({
        url: pag + "/ativar",
        method: 'POST',
        data: {ativar},
        dataType: "html",

        success:function(result){
            $("#listar").html(result);
			//location. reload('#lista')
			console.log(ativar)
			listar();
        }
    });
}

function atualizalista(){

location. reload('#listar')
}


function relatorio(){
    
    $('#mensagem').text('');
    $('#tituloModal2').text('Gerar Relatório');
    var myModal = new bootstrap.Modal(document.getElementById('modalForm2'), {
        backdrop: 'static',
    });
    myModal.show();
    
}

function relatorioPdf(){
    
    $('#mensagem').text('');
    $('#tituloModal2').text('Gerar Relatório PDF');
    var myModal = new bootstrap.Modal(document.getElementById('modalForm3'), {
        backdrop: 'static',
    });
    myModal.show();
   
}




</script>

