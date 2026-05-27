<?php

@session_start();
//@$nivel_usu = $_SESSION['nivel'];
//$id_usuario = $_SESSION['id'];
$_SESSION['entrada'] = @$_GET['entrada'];
//var_dump($nivel_usu);
$pagina = '?router=Saldo';

?>

<div class="row my-3">
       
   <div class="col-md-12 container-fluid mb-4 mx-4">
	

		<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Inicial" class="bi bi-calendar-date"></i></small></span></div>
		<div style="float:left; margin-right:20px">
			<input type="date" class="form-control form-control-sm" name="data-inicial"  id="data-inicial" value="<?php echo date('Y-m-d') ?>" required>
		</div>

		<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Final" class="bi bi-calendar-date"></i></small></span></div>
		<div style="float:left; margin-right:40px">
			<input type="date" class="form-control form-control-sm" name="data-final"  id="data-final" value="<?php echo date('Y-m-d') ?>" required>
		</div>

		<div class="mb-3">
	    <div style="float:left; margin-right:10px"><span><small><i title="Filtrar por Entrada ou Saída" class="bi bi-search"></i></small></span></div>
		<div style="float:left; margin-right:10px">
		  		<select class="form-select form-select-sm" aria-label="Default select example" name="status-busca" id="status-busca">
					<option value="">Entrada/Saída</option>
					<option value="Saida">Saída</option>
					<option value="Entrada">Entradas</option>
					
			</select>
		</div>

		<small><div style="float:left; margin-right:10px"><span><small><i title="Filtrar por Entrada ou Saída" class="bi bi-search"></i></small></span></div>
		<div style="float:left; margin-right:10px">
		<select class="form-select" aria-label="Default select example"  id="listarTipoBanco">
		
		</select>	
	</div></small>

	</div>
</div>
</div>

<div class="container-fluid mb-4 mx-4">
<div class="row my-12">
		
<small><i class="bi bi-arrow-up-square-fill text-success"></i> <span class="text-dark"> Total Debito: <span class="text-success" id="total_entrada"></span></span>
<i class="bi bi-arrow-down-square-fill text-danger"></i> <span class="text-dark"> Total Credito: <span class="text-danger" id="total_saida"></span></span></small>
</div>
		
	
</div>
</div>


<div class="container-fluid mb-4 mx-4">
	<small class = "max-4">
<small>
	<div class="tabela bg-light" id="listar">
	</div>
	</small>
</small>
</div>
</div>

	

<script type="text/javascript">var pag = "<?=$pagina?>"</script>
<script src="config/js/ajax.js"></script>



<script>

$(document).ready(function() {
	listarTipoBanco();
  
} );
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
			console.log(tipo);
			listarBusca(dataInicial, dataFinal, tipo, banco) ;
		});

		$('#listarTipoBanco').change(function(){
			var dataInicial = $('#data-inicial').val();
			var dataFinal = $('#data-final').val();
            var tipo = $('#status-busca').val();
			var banco = $('#listarTipoBanco').val();
			console.log(banco);
			listarBusca(dataInicial, dataFinal, tipo,  banco) ;
			
		});

	
		
	
function listarBusca(dataInicial, dataFinal, tipo, banco,  alterou_data){
    $.ajax({
        url: pag + "/listar",
        method: 'POST',
        data: {dataInicial, dataFinal, tipo, banco, alterou_data,},
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

			console.log(hoje);
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

			console.log(saida);
        }
    });
}

function listarTipoBanco(){
		var pag = "<?=$pagina?>";
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