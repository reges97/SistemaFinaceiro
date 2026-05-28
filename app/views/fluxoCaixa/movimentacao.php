<?php
$pagina = '?router=Mov';
$campo1 = 'tip';
$campo2 = 'movimento';
$campo3 = 'valor';
$campo4 = 'tesoureiro';
$campo5 = 'data';
$campo6 = 'id_movimento';


?>

<div class="row my-3">
	<div class="col-md-12 container-fluid mb-4 mx-4">
	

		<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Inicial" class="bi bi-calendar-date"></i></small></span></div>
		<div style="float:left; margin-right:10px">
			<input type="date" class="form-control form-control-sm" name="data-inicial"  id="data-inicial" value="<?php echo date('Y-m-d') ?>" required>
		</div>

		<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Final" class="bi bi-calendar-date"></i></small></span></div>
		<div style="float:left; margin-right:10px">
			<input type="date" class="form-control form-control-sm" name="data-final"  id="data-final" value="<?php echo date('Y-m-d') ?>" required>
		</div>

		
		<div style="float:left; margin-right:10px"><span><small><i title="Filtrar por Status" class="bi bi-search"></i></small></span></div>
		<div style="float:left; margin-right:10px">
			<select class="form-select form-select-sm" aria-label="Default select example" name="status-busca" id="status-busca">
				<option value="">Entradas / Saídas</option>
				<option value="Entrada">Entradas</option>
				<option value="Saida">Saídas</option>
				</select>
		</div>
	</div>
		
		<div class="col-md-12 container-fluid mb-4 mx-4">
		<div class="col-md-12">
		<small class="mx-8">
		<div style="float:left; margin-right:10px"><a style="text-decoration: none" title="Contas à Pagar Vencidas" class="text-muted" href="#" onclick="listarContasEntrada('Entradas')"><span>Entradas</span></a> /</div> 
		<!-- Filtro hoje: chama a funcao correta desta tela para recarregar movimentacoes do dia. -->
		<div style="float:left; margin-right:10px"><a style="text-decoration: none" title="Movimentações de hoje" class="text-muted" href="#" onclick="listarContasHoje('Hoje')"><span>Hoje</span></a> / </div>
		<div style="float:left; margin-right:10px"><a style="text-decoration: none" title="Contas à Pagar Amanhã" class="text-muted" href="#" onclick="listarContasSaida('Saidas')"><span>Saida</span> /</a></div>

		<div style="float:left; margin-right:10px"><a style="text-decoration: none" title="Gerar relatórios csv/pdf" class="text-muted" href="#"><span>Gerar relatórios:</span></a>  </div>
		<div style="float:left; margin-right:10px"><a title="Gera relatório excell" class="text-muted" href="#" onclick="relatorio()"> <span class="nav-icon"><img src="config/img/csv2.ico" width="30px"></span></a></div>
	</small>
	</div>
	</div>
	</div>

	<div class="col-md-12 container-fluid mb-4 mx-4">
   <div class="col-md-12">
	
	<div  style="float:left; margin-left:5px">
	<small><i class="bi bi-arrow-up-square-fill text-success"></i> <span class="text-dark">Entrada: <span class="text-success" id="total_entrada"></span></span></small>
	</div>
	<div  style="float:right; margin-right:100px">
	<small><i class="bi bi-arrow-down-square-fill text-danger"></i> <span class="text-dark">Saida: <span class="text-danger" id="total_saida"></span></span></small>
	</div>
	
	</div>
	
			
</div>
</div>

<small class="mx-4" >
<small>
	<div class="tabela bg-light" id="listar">
		<?php
		// Movimentacao: renderiza a tabela no carregamento inicial para nao depender somente do AJAX.
		require __DIR__ . '/listarMov.php';
		?>
	</div>
</small>
</small>

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
			<form id="form2" method="post" action="?router=Mov/gerar "target="">
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


					<div class="col-md-4 col-sm-12">
					<div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label">Tipo</label>
						<select class="form-select form-select-sm" aria-label="Default select example" name="tipo" id="tipo">
						<option value="">Entrada / Saídas</option>
						<option value="Entrada">Entradas</option>
						<option value="Saida">Saídas</option>
				
						</select>
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
    $('#tituloModal2').text('Gerar Relatório');
    var myModal = new bootstrap.Modal(document.getElementById('modalForm2'), {
        backdrop: 'static',
    });
    myModal.show();
    
}



function atualizalista(){

location. reload('#lista')
}







</script>
