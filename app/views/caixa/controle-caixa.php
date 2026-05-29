<?php
@session_start();
$pagina = '?router=ControleCaixa';
$campo1 = 'Data Inicial';
$campo2 = 'Data Final';
?>

<!-- Controle de caixa: filtros em estrutura valida e tabela carregada no primeiro acesso. -->
<section class="controle-caixa-page mx-4 my-3">
	<div class="row g-3 align-items-end mb-3">
		<div class="col-md-3 col-sm-6">
			<label for="data-inicial" class="form-label small text-muted"><i class="bi bi-calendar-date"></i> Data inicial</label>
			<input type="date" class="form-control form-control-sm" name="data-inicial" id="data-inicial" value="<?php echo date('Y-m-d') ?>" required>
		</div>

		<div class="col-md-3 col-sm-6">
			<label for="data-final" class="form-label small text-muted"><i class="bi bi-calendar-date"></i> Data final</label>
			<input type="date" class="form-control form-control-sm" name="data-final" id="data-final" value="<?php echo date('Y-m-d') ?>" required>
		</div>

		<div class="col-md-3 col-sm-6">
			<label for="status-busca" class="form-label small text-muted"><i class="bi bi-search"></i> Tipo</label>
			<select class="form-select form-select-sm" aria-label="Filtrar por tipo" name="status-busca" id="status-busca">
				<option value="">Entrada / Saida</option>
				<option value="Entrada">Entrada</option>
				<option value="Saida">Saida</option>
			</select>
		</div>
	</div>

	<div class="d-flex flex-wrap align-items-center gap-2 mb-3 small">
		<a class="btn btn-sm btn-outline-danger" href="#" onclick="listarContasVencidas('Vencidas'); return false;">Vencidas</a>
		<a class="btn btn-sm btn-outline-secondary" href="#" onclick="listarContasVencidas('Hoje'); return false;">Hoje</a>
		<a class="btn btn-sm btn-outline-secondary" href="#" onclick="listarContasVencidas('Amanha'); return false;">Amanha</a>
		<a class="btn btn-sm btn-outline-primary" href="#" onclick="relatorio(); return false;">Relatorio</a>
	</div>

	<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
		<small><i class="bi bi-cash text-danger"></i> <span class="text-dark">Total: <span class="text-danger" id="total_itens"></span></span></small>
	</div>

	<div class="tabela bg-light" id="listar">
		<?php
		// Controle de caixa: renderiza a tabela inicial sem depender apenas do AJAX generico.
		require __DIR__ . '/listarControleCaixa.php';
		?>
	</div>
</section>

<!-- Modal de relatorio do controle de caixa. -->
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
								<label for="dataInicial" class="form-label"><?php echo $campo1 ?></label>
								<input type="date" class="form-control" name="dataInicial" placeholder="<?php echo $campo1 ?>" id="dataInicial" required>
							</div>
						</div>
						<div class="col-md-4 col-sm-12">
							<div class="mb-3">
								<label for="dataFinal" class="form-label"><?php echo $campo2 ?></label>
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

<script type="text/javascript">var pag = "<?=$pagina?>"; var rotaControleCaixaListar = "?router=ControleCaixa/listar";</script>

<script>
function aplicarControleCaixa(result){
	// Controle de caixa: evita substituir a tabela por login ou HTML inesperado quando a sessao expirar.
	if (typeof result !== 'string' || result.indexOf('<table') === -1) {
		$("#listar").html('<div class="alert alert-warning mb-0">Nao foi possivel carregar o controle de caixa. Atualize a pagina e confirme se o banco esta ativo.</div>');
		return;
	}

	$("#listar").html(result);
}

function erroControleCaixa(xhr, mensagemPadrao){
	// Controle de caixa: mostra resposta real quando o AJAX falhar para facilitar correcao sem tela muda.
	var detalhe = xhr && xhr.responseText ? xhr.responseText : '';
	if (detalhe.indexOf('<table') !== -1) {
		aplicarControleCaixa(detalhe);
		return;
	}

	if (detalhe.indexOf('form-signin') !== -1 || detalhe.indexOf('Entrar') !== -1) {
		$("#listar").html('<div class="alert alert-warning mb-0">Sua sessao expirou. Entre novamente no sistema e tente o filtro.</div>');
		return;
	}

	$("#listar").html('<div class="alert alert-danger mb-0">' + mensagemPadrao + '</div>');
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
	listarBusca(dataInicial, dataFinal, status, 'Sim');
});

function listarBusca(dataInicial, dataFinal, status, alterou_data){
	$.ajax({
		url: rotaControleCaixaListar,
		method: 'POST',
		data: {dataInicial, dataFinal, status, alterou_data},
		dataType: "html",
		success:function(result){
			aplicarControleCaixa(result);
		},
		error:function(xhr){
			erroControleCaixa(xhr, 'Falha ao consultar o controle de caixa.');
		}
	});
}

function listarContasVencidas(vencidas){
	$.ajax({
		url: rotaControleCaixaListar,
		method: 'POST',
		data: {vencidas},
		dataType: "html",
		success:function(result){
			aplicarControleCaixa(result);
		},
		error:function(xhr){
			erroControleCaixa(xhr, 'Falha ao consultar o filtro informado.');
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
