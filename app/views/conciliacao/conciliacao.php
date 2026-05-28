<?php

use app\controllers\Conci;

// Sessao protegida: evita aviso quando o menu ou roteador ja iniciou a sessao.
if (session_status() !== PHP_SESSION_ACTIVE) {
	@session_start();
}
@$nivel_usu = $_SESSION['nivel'];
$id_usuario = $_SESSION['id'];
//var_dump($nivel_usu);
$pagina = '?router=Conci';
//VARIAVEIS DOS INPUTS
$campo1 = 'Data';
$campo2 = 'Descricao';
$campo3 = 'Documento';
$campo4 = 'Observacao';
$campo5 = 'Valor';
$campo6 = 'Tipo';
$campo7 = 'Conta';
$campo8 = 'SaldoExterno';

//include_once 'recorrente.php'

 ?>


<div class="row my-3">
	<div class="col-md-9 container-fluid mb-4 mx-4">
		
		<div style="float:left; margin-right:35px">
			<a href="#" onclick="inserir()" type="button" class="btn btn-dark btn-sm">Nova Conta</a>
		</div>

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
				<option value="">Credito/Debito</option>
				<option value="Credito">Crédito</option>
				<option value="Debito">Debito</option>
				
			</select>
		</div>
				
		<small class="mx-4">
			<a title="Conciliação Credito" class="text-muted" href="#" onclick="listarCredito('Credito')"><span>Crédito</span></a> / 
			<a title="Conciliação Debito" class="text-muted" href="#" onclick="listarDebito('Debito')"><span>Débito</span></a> / 
			<a title="Contas à Pagar Hoje" class="text-muted" href="#" onclick="listarContasHoje('Hoje')"><span>Hoje</span></a>
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




<!-- Modal -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal">Inserir Registro</span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="form" method="post">
				<div class="modal-body">
		
					<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade show active" id="dados" role="tabpanel" aria-labelledby="home-tab">

							<div class="row">
								<div class="col-md-4 col-sm-12">
									<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label"><?php echo $campo1 ?></label>
										<input type="date" class="form-control" name="<?php echo $campo1 ?>" placeholder="Descrição" id="<?php echo $campo1 ?>" value="<?php echo date('Y-m-d') ?>"required>
									</div>
								</div>

                                
                                <div class="col-md-4 col-sm-12">
									<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label"><?php echo $campo2 ?></label>
										<input type="text" class="form-control" name="<?php echo $campo2 ?>"  id="<?php echo $campo2 ?>"  required>
									</div>
								</div>

                                
                                <div class="col-md-4 col-sm-12">
									<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label"><?php echo $campo7 ?></label>
										<select class="form-select" aria-label="Default select example" name="<?php echo $campo7 ?>" id="<?php echo $campo7 ?>" value="<?php echo $campo7 ?>" >
										<?php 
											$con = new Conci;
											$pdo = $con->conectar();
											$query = $pdo->query("SELECT * FROM bancarias order by banco asc");
											$res = $query->fetchAll(PDO::FETCH_ASSOC);
											for($i=0; $i < @count($res); $i++){
												foreach ($res[$i] as $key => $value){	}
													$id_banco = $res[$i]['id'];
												    $banco = $res[$i]['banco'];
												?>
												<option value="<?php echo $id_banco ?>"><?php echo $banco ?></option>

											<?php } ?>


										</select>
									</div>
								</div>
							</div>
			
							<div class="row">
								<div class="col-md-4 col-sm-12">
									<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label"><?php echo $campo3 ?></label>
										<input type="text" class="form-control" name="<?php echo $campo3 ?>"  id="<?php echo $campo3 ?>"  required>
									</div>
								</div>
						
								<div class="col-md-4 col-sm-12">
									<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label"><?php echo $campo4 ?></label>
										<input type="text" class="form-control" name="<?php echo $campo4 ?>"  id="<?php echo $campo4?>" value="<?php echo $campo4?>" required>
									</div>
								</div>

                                <div class="col-md-4 col-sm-12">
									<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label"><?php echo $campo5?></label>
										<input type="text" class="form-control" name="<?php echo $campo5 ?>"  id="<?php echo $campo5 ?>"  required>
									</div>
								</div>
							</div>
                           	<div class="row">
                            <div class="col-md-4 col-sm-12">
									<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label"><?php echo $campo6 ?></label>
										<select class="form-select" aria-label="Default select example" name="<?php echo $campo6 ?>" id="<?php echo $campo6 ?>">
										<option value="" >Selecione</option>				
                                        <option value="Debito" >Debito</option>
											<option value="Credito"  >Credito</option>
											</select>
									</div>
								</div>
								
								
							<div class="col-md-4 col-sm-12">
									<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label"><?php echo $campo8?></label>
										<input type="text" class="form-control" name="<?php echo $campo8 ?>"  id="<?php echo $campo8 ?>"  required>
									</div>
								</div>
							</div>
							</div>	
							
						</div>					

					<small id="mensagem" class="d-block text-center"></small>
					<input type="text" class="form-control" name="id"  id="id">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar">Fechar</button>
					<button type="submit" class="btn btn-primary" >Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>


</div>
<!-- Modal -->
<div class="modal fade" id="modalExcluir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal">Excluir Registro</span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="form-excluir" method="post">
				<div class="modal-body">

					Deseja Realmente excluir este Registro: <span id="nome-excluido"></span>?

					<small id="mensagem-excluir" class="d-block text-center"></small>

					<input type="hidden" class="form-control" name="id-excluir"  id="id-excluir">


				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-excluir">Fechar</button>
					<button type="submit" class="btn btn-danger">Excluir</button>
				</div>
			</form>
		</div>
	</div>
</div>



<!-- Modal -->
<div class="modal fade" id="modalconci" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal">Inserir Registro</span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="formconci" method="post">
				<div class="modal-body">

					<div class="mb-3">
					Deseja Realmente conciliar este Registro: <span id="conciliacao"></span>?
							
				</div>				

					<!-- Mensagem propria da conciliacao para nao conflitar com o modal de cadastro. -->
					<small id="mensagem-conci" class="d-block text-center"></small>

					<input type="text" class="form-control" name="id_conci"  id="id_conci">


				
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar">Fechar</button>
					<button type="submit" class="btn btn-primary">Salvar</button>
				</div>
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

function listarCredito(credito){
    $.ajax({
        url: pag + "/listar",
        method: 'POST',
        data: {credito},
        dataType: "html",

        success:function(result){
            $("#listar").html(result);
        }
    });
}


function listarDebito(debito){
    $.ajax({
        url: pag + "/listar",
        method: 'POST',
        data: {debito},
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

location. reload('#lista')
}



$("#formconci").submit(function () {
        event.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: pag + "/conciliar",
            type: 'POST',
            data: formData,
    
            success: function (mensagem) {
                // Retorno flexivel: aceita conciliacao normal ou com aviso de divergencia.
                $('#mensagem-conci').text('');
                $('#mensagem-conci').removeClass()
                if (mensagem.trim().indexOf("Conciliacao efetuada") === 0) {
                    //$('#btn-fechar-baixar').click();
                    listar();
                    limparCampos();
                    $('#mensagem-conci').addClass(mensagem.indexOf('divergencia') > -1 ? 'text-warning' : 'text-success')
                    $('#mensagem-conci').text(mensagem)
                } else {
    
                    $('#mensagem-conci').addClass('text-danger')
                    $('#mensagem-conci').text(mensagem)
                }
                console.log(mensagem);
    
            },
    
            cache: false,
            contentType: false,
            processData: false,
    
        });
    
    });
	

</script>
