<?php

@session_start();
$_SESSION['entrada'] = @$_GET['entrada'];
$pagina = '?router=Saldo';

?>

<!-- Saldos: filtros e tabela em estrutura Bootstrap valida, sem floats e divs dentro de small. -->
<section class="saldo-page mx-4 my-3">
	<div class="row g-3 align-items-end mb-3">
		<div class="col-md-3 col-sm-6">
			<label class="form-label small text-muted" for="data-inicial"><i class="bi bi-calendar-date"></i> Data inicial</label>
			<input type="date" class="form-control form-control-sm" name="data-inicial" id="data-inicial" value="<?php echo date('Y-m-d') ?>" required>
		</div>

		<div class="col-md-3 col-sm-6">
			<label class="form-label small text-muted" for="data-final"><i class="bi bi-calendar-date"></i> Data final</label>
			<input type="date" class="form-control form-control-sm" name="data-final" id="data-final" value="<?php echo date('Y-m-d') ?>" required>
		</div>

		<div class="col-md-3 col-sm-6">
			<label class="form-label small text-muted" for="status-busca"><i class="bi bi-search"></i> Tipo</label>
			<select class="form-select form-select-sm" aria-label="Filtrar por entrada ou saida" name="status-busca" id="status-busca">
				<option value="">Entrada/Saida</option>
				<option value="Saida">Saida</option>
				<option value="Entrada">Entradas</option>
			</select>
		</div>

		<div class="col-md-3 col-sm-6">
			<label class="form-label small text-muted" for="listarTipoBanco"><i class="bi bi-bank"></i> Banco</label>
			<select class="form-select form-select-sm" aria-label="Filtrar por banco" id="listarTipoBanco"></select>
		</div>
	</div>

	<div class="d-flex flex-wrap gap-3 mb-3">
		<small><i class="bi bi-arrow-up-square-fill text-success"></i> <span class="text-dark">Total Debito: <span class="text-success" id="total_entrada"></span></span></small>
		<small><i class="bi bi-arrow-down-square-fill text-danger"></i> <span class="text-dark">Total Credito: <span class="text-danger" id="total_saida"></span></span></small>
	</div>

	<div class="tabela bg-light" id="listar"></div>
</section>

<script type="text/javascript">var pag = "<?=$pagina?>"</script>
<script src="config/js/ajax.js"></script>

<script>
$(document).ready(function() {
	listarTipoBanco();
});

$('#data-inicial').change(function(){
	var dataInicial = $('#data-inicial').val();
	var dataFinal = $('#data-final').val();
	var tipo = $('#status-busca').val();
	var banco = $('#listarTipoBanco').val();
	var alterou_data = 'Sim';
	listarBusca(dataInicial, dataFinal, tipo, banco, alterou_data);
});

$('#data-final').change(function(){
	var dataInicial = $('#data-inicial').val();
	var dataFinal = $('#data-final').val();
	var tipo = $('#status-busca').val();
	var banco = $('#listarTipoBanco').val();
	var alterou_data = 'Sim';
	listarBusca(dataInicial, dataFinal, tipo, banco, alterou_data);
});

$('#status-busca').change(function(){
	var dataInicial = $('#data-inicial').val();
	var dataFinal = $('#data-final').val();
	var tipo = $('#status-busca').val();
	var banco = $('#listarTipoBanco').val();
	listarBusca(dataInicial, dataFinal, tipo, banco);
});

$('#listarTipoBanco').change(function(){
	var dataInicial = $('#data-inicial').val();
	var dataFinal = $('#data-final').val();
	var tipo = $('#status-busca').val();
	var banco = $('#listarTipoBanco').val();
	listarBusca(dataInicial, dataFinal, tipo, banco);
});

function listarBusca(dataInicial, dataFinal, tipo, banco, alterou_data){
	$.ajax({
		url: pag + "/listar",
		method: 'POST',
		data: {dataInicial, dataFinal, tipo, banco, alterou_data},
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

function listarSaida(saida){
	$.ajax({
		url: pag + "/listar",
		method: 'POST',
		data: {saida},
		dataType: "html",
		success:function(result){
			$("#listar").html(result);
		}
	});
}

function listarTipoBanco(){
	$.ajax({
		url: pag + "/listarTipoBanco",
		method: 'POST',
		data: $('#form').serialize(),
		dataType: "text",
		success:function(result){
			$("#listarTipoBanco").html(result);
		}
	});
}
</script>
