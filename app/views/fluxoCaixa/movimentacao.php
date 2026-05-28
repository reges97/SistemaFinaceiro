<?php
$pagina = '?router=Mov';
$campo1 = 'tip';
$campo2 = 'movimento';
?>

<!-- Movimentacao: estrutura limpa evita que divs antigas escondam a tabela em alguns navegadores. -->
<section class="movimentacao-page mx-4 my-3">
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
			<select class="form-select form-select-sm" aria-label="Filtrar por tipo" name="status-busca" id="status-busca">
				<option value="">Entradas / Saidas</option>
				<option value="Entrada">Entradas</option>
				<option value="Saida">Saidas</option>
			</select>
		</div>
	</div>

	<div class="d-flex flex-wrap align-items-center gap-2 mb-3 small">
		<a class="btn btn-sm btn-outline-success" href="#" onclick="listarContasEntrada('Entradas'); return false;">Entradas</a>
		<!-- Filtro hoje: chama a funcao correta desta tela para recarregar movimentacoes do dia. -->
		<a class="btn btn-sm btn-outline-secondary" href="#" onclick="listarContasHoje('Hoje'); return false;">Hoje</a>
		<a class="btn btn-sm btn-outline-danger" href="#" onclick="listarContasSaida('Saidas'); return false;">Saidas</a>
		<a class="btn btn-sm btn-outline-primary" title="Gera relatorio Excel" href="#" onclick="relatorio(); return false;">
			<span class="nav-icon"><img src="config/img/csv2.ico" width="20px" alt=""></span> Gerar relatorio
		</a>
	</div>

	<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
		<div>
			<small><i class="bi bi-arrow-up-square-fill text-success"></i> <span class="text-dark">Entrada: <span class="text-success" id="total_entrada"></span></span></small>
		</div>
		<div>
			<small><i class="bi bi-arrow-down-square-fill text-danger"></i> <span class="text-dark">Saida: <span class="text-danger" id="total_saida"></span></span></small>
		</div>
	</div>

	<div class="tabela bg-light" id="listar">
		<?php
		// Movimentacao: renderiza a tabela no carregamento inicial para nao depender somente do AJAX.
		require __DIR__ . '/listarMov.php';
		?>
	</div>
</section>

<!-- Modal para gerar relatorios da movimentacao. -->
<div class="modal fade" id="modalForm2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal2"></span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="form2" method="post" action="?router=Mov/gerar" target="">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-4 col-sm-12">
							<div class="mb-3">
								<label for="dataInicial" class="form-label">Data Inicial</label>
								<input type="date" class="form-control" name="dataInicial" placeholder="<?php echo $campo1 ?>" id="dataInicial" required>
							</div>
						</div>
						<div class="col-md-4 col-sm-12">
							<div class="mb-3">
								<label for="dataFinal" class="form-label">Data Final</label>
								<input type="date" class="form-control" name="dataFinal" placeholder="<?php echo $campo2 ?>" id="dataFinal" required>
							</div>
						</div>

						<div class="col-md-4 col-sm-12">
							<div class="mb-3">
								<label for="tipo" class="form-label">Tipo</label>
								<select class="form-select form-select-sm" aria-label="Tipo do relatorio" name="tipo" id="tipo">
									<option value="">Entrada / Saidas</option>
									<option value="Entrada">Entradas</option>
									<option value="Saida">Saidas</option>
								</select>
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

<script type="text/javascript">var pag = "<?=$pagina?>"</script>

<script>
function aplicarTabelaMovimentacao(result){
	// Movimentacao: evita trocar a tabela por login/HTML inesperado quando a sessao expira ou a rota falha.
	if (typeof result !== 'string' || result.indexOf('<table') === -1) {
		$("#listar").html('<div class="alert alert-warning mb-0">Nao foi possivel carregar a tabela de movimentacao. Atualize a pagina e confirme se o banco de dados esta ativo.</div>');
		return;
	}

	$("#listar").html(result);
}

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
			aplicarTabelaMovimentacao(result);
		},
		error:function(){
			$("#listar").html('<div class="alert alert-danger mb-0">Falha ao consultar as movimentacoes.</div>');
		}
	});
}

function listarContasEntrada(entradas){
	$.ajax({
		url: pag + "/listar",
		method: 'POST',
		data: {entradas},
		dataType: "html",
		success:function(result){
			aplicarTabelaMovimentacao(result);
		},
		error:function(){
			$("#listar").html('<div class="alert alert-danger mb-0">Falha ao consultar as entradas.</div>');
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
			aplicarTabelaMovimentacao(result);
		},
		error:function(){
			$("#listar").html('<div class="alert alert-danger mb-0">Falha ao consultar as movimentacoes de hoje.</div>');
		}
	});
}

function listarContasSaida(saida){
	$.ajax({
		url: pag + "/listar",
		method: 'POST',
		data: {saida},
		dataType: "html",
		success:function(result){
			aplicarTabelaMovimentacao(result);
		},
		error:function(){
			$("#listar").html('<div class="alert alert-danger mb-0">Falha ao consultar as saidas.</div>');
		}
	});
}

function relatorio(){
	$('#mensagem').text('');
	$('#tituloModal2').text('Gerar Relatorio');
	var myModal = new bootstrap.Modal(document.getElementById('modalForm2'), {
		backdrop: 'static',
	});
	myModal.show();
}
</script>
