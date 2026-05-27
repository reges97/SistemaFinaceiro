<?php

use app\controllers\ContasReceber;
@session_start();
@$nivel_usu = $_SESSION['nivel'];
//var_dump($nivel_usu);
$pagina = '?router=ContasReceber';
//VARIAVEIS DOS INPUTS
$campo1 = 'descricao';
$campo2 = 'Cliente';
$campo3 = 'Entrada';
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

?>


	

<div class="row my-9">

<div class="col-md-12 container-fluid mb-4 mx-4">
		<small>
		<div style="float:left; margin-right:35px">
		<a href="#" onclick="inserir()" type="button" class="btn btn-success btn-sm text-white">Nova Agenda</a>
		</div>
		</small>

	</div>

	
   <div class="row">

<div class="col-md-12 container-fluid mb-4 mx-4">

         
		 <div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Inicial" class="bi bi-calendar-date" ></i></small></span></div>
         <div style="float:left; margin-right:20px">
		 <small> <input type="date" class="form-control form-control-sm" name="data-inicial"  id="data-inicial" value="<?php echo date('Y-m-d') ?>" required></small>
         </div>
		

		

		 <div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Inicial" class="bi bi-calendar-date" ></i></small></span></div>
		 <div style="float:left; margin-right:10px">
		 <small><input type="date" class="form-control form-control-sm" name="data-final"  id="data-final" value="<?php echo date('Y-m-d') ?>" required></small>
		 </div>

		 <div style="float:left; margin-right:5px"><span><small><i title="Filtrar por Status" class="bi bi-search"></i></small></span></div>
		 <div style="float:left; margin-right:10px">
		 
			<select class="form-select form-select-sm" aria-label="Default select example" name="status-busca" id="status-busca">
				<option value="">Pendentes / Pagas</option>
				<option value="Pendente">Pendentes</option>
				<option value="Paga">Pagas</option>
				
			</select>

			
		</div>
		
	
</div>

	<div class="col-md-12 container-fluid mb-4 mx-4">
	<div class="col-md-12">
	<small class="mx-10">
	<div style="float:left; margin-right:10px"><a style="text-decoration: none" title="Contas à Pagar Vencidas" class="text-muted" href="#" onclick="listarContasVencidas('Vencidas')"><span>Vencidas</span></a> / </div>
	<div style="float:left; margin-right:10px"><a style="text-decoration: none" title="Contas à Pagar Hoje" class="text-muted" href="#" onclick="listarContasVencidas('Hoje')"><span>Hoje</span></a> / </div>
	<div style="float:left; margin-right:10px"><a style="text-decoration: none" title="Contas à Pagar Amanhã" class="text-muted" href="#" onclick="listarContasVencidas('Amanha')"><span>Amanhã</span></a> /</div>
     
	<div style="float:left; margin-right:10px"><a style="text-decoration: none" title="Gerar relatórios csv/pdf" class="text-muted" href="#"><span>Gerar relatórios:</span></a>  </div>
	<div style="float:left; margin-right:10px"><a title="Gera relatório excell" class="text-muted" 
	style="text-decoration: none" href="#" onclick="relatorio()"> 
	<span class="nav-icon"><img src="config/img/csv2.ico" width="30px"></span></a></div>
	
	<div style="float:left; margin-right:10px"><a title="Gera relatório excell" class="text-muted" 
	style="text-decoration: none" href="#" onclick="relatorio()"> 
	<span class="nav-icon"><img src="config/img/pdf2.ico" width="30px"></span></a></div>


     </small>
		</div>
       </div>
	
   
<div class="col-md-12 container-fluid mb-4 mx-4">
<div class="col-md-12">
<div style="float:right; margin-right:20px"><small><i class="bi bi-cash text-danger"></i> <span class="text-dark">Total: <span class="text-danger" id="total_itens"></span></span></small></div>

<small class="mx-4">
  <small>
  
  <div class="tabela bg-light col-md-12" id="listar" style="float:left; margin-right:10px; float:right; margin-left:1px"></div>

 </small>
 </small>
 


</div>
</div>
</div>




</div>
</div>
</div>

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

					

					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#dados" type="button" role="tab" aria-controls="home" aria-selected="true">Conta</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#contas" type="button" role="tab" aria-controls="profile" aria-selected="false">Cliente</a>
						</li>
						
					</ul>
					
					<hr>

					<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade show active" id="dados" role="tabpanel" aria-labelledby="home-tab">

							<div class="row">
								<div class="col-md-4 col-sm-12">
									<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label">Descrição</label>
										<input type="text" class="form-control" name="<?php echo $campo1 ?>" placeholder="Descrição" id="<?php echo $campo1 ?>" required>
									</div>
								</div>

								<div class="col-md-4 col-sm-12">
									<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label">Tipo Entrada</label>
										<select class="form-select" aria-label="Default select example" name="<?php echo $campo3 ?>" id="<?php echo $campo3 ?>">
											<option value="Caixa">Caixa (Movimento)</option>
											
											<?php 
											$con = new ContasReceber;
											$pdo = $con->conectar();
											$query = $pdo->query("SELECT * FROM bancarias order by banco asc");
											$res = $query->fetchAll(PDO::FETCH_ASSOC);
											for($i=0; $i < @count($res); $i++){
												foreach ($res[$i] as $key => $value){	}
													$id_item = $res[$i]['id'];
												$nome_item = $res[$i]['banco'];
												?>
												<option value="<?php echo $nome_item ?>"><?php echo $nome_item ?></option>

											<?php } ?>


										</select>
									</div>
								</div>

								<div class="col-md-4 col-sm-12">
									<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label"><?php echo @$campo4 ?></label>
										<select class="form-select" aria-label="Default select example" name="<?php echo $campo4 ?>" id="<?php echo $campo4 ?>">
										<?php 
											$pdo = $con->conectar();
											$query = $pdo->query("SELECT * FROM formas_pgtos order by nome_fpg asc");
											$res = $query->fetchAll(PDO::FETCH_ASSOC);
											for($i=0; $i < @count($res); $i++){
												foreach ($res[$i] as $key => $value){	}
													$id_item = $res[$i]['id'];
												    $nome_pgto = $res[$i]['nome_fpg'];
												?>
												<option value="<?php echo $id_item ?>"><?php echo $nome_pgto ?></option>

											<?php } ?>
										</select>
									</div>
								</div>

								
							</div>

							<div class="row">
								<div class="col-md-4 col-sm-12">
									<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label">Plano de Conta</label>
										<select class="form-select" aria-label="Default select example" name="cat_despesas" id="cat_despesas">
																						
											<?php 
											$pdo = $con->conectar();
											$query = $pdo->query("SELECT * FROM cat_despesas order by nome asc");
											$res = $query->fetchAll(PDO::FETCH_ASSOC);
											for($i=0; $i < @count($res); $i++){
												foreach ($res[$i] as $key => $value){	}
													$id_item = $res[$i]['id'];
												$nome_item = $res[$i]['nome'];
												?>
												<option value="<?php echo $id_item ?>"><?php echo $nome_item ?></option>

											<?php } ?>


										</select>
									</div>
								</div>


								<div class="col-md-4 col-sm-12">
									<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label">Despesa</label>
										<div id="listar-despesas">

										</div>
										
									</div>
								</div>


								<div class="col-md-4 col-sm-12">
									<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label">Data Emissão</label>
										<input type="date" class="form-control" name="<?php echo $campo6 ?>"  id="<?php echo $campo6 ?>" value="<?php echo date('Y-m-d') ?>" required>
									</div>
								</div>

							</div>

							<div class="row">


<div class="col-md-4 col-sm-12">
		<div class="mb-3">
			<label for="exampleFormControlInput1" class="form-label">Desconto em %</label>
			<input type="text" class="form-control" name="<?php echo $campo14 ?>"  id="<?php echo $campo14 ?>" value="<?php echo $campo14 ?>" required>
		</div>
	</div>



	<div class="col-md-4 col-sm-12">
		<div class="mb-3">
			<label for="exampleFormControlInput1" class="form-label">Multa em %</label>
		<input  type="text" class="form-control" name="<?php echo $campo15 ?>"  id="<?php echo $campo15 ?>" value="<?php echo $campo15?>" required>
		</div>
	</div>

	
	<div class="col-md-4 col-sm-12">
		<div class="mb-3">
			<label for="exampleFormControlInput1" class="form-label">Juros %</label>
			<input type="text" class="form-control" name="<?php echo $campo16 ?>"  id="<?php echo $campo16 ?>" value="<?php echo $campo16?>" required>
		</div>
	</div>

</div>



							<div class="row">

								<div class="col-md-4 col-sm-12">
									<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label"><?php echo @$campo7 ?></label>
										<input type="date" class="form-control" name="<?php echo $campo7 ?>"  id="<?php echo $campo7 ?>" value="<?php echo date('Y-m-d') ?>" required>
									</div>
								</div>

								<div class="col-md-4 col-sm-12">
									<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label">Frequência</label>
										<select class="form-select" aria-label="Default select example" name="<?php echo $campo8 ?>" id="<?php echo $campo8 ?>">
																						
											<?php
											 
											 $pdo = $con->conectar();
											$query = $pdo->query("SELECT * FROM frequencias order by id asc");
											$res = $query->fetchAll(PDO::FETCH_ASSOC);
											for($i=0; $i < @count($res); $i++){
												foreach ($res[$i] as $key => $value){	}
													$id_item = $res[$i]['id'];
												$nome_item = $res[$i]['nome'];
												?>
												<option value="<?php echo $nome_item ?>"><?php echo $nome_item ?></option>

											<?php } ?>


										</select>
									</div>
								</div>


								<div class="col-md-4 col-sm-12">
									<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label">Valor da Conta</label>
										<input type="text" class="form-control" name="<?php echo $campo9 ?>"  id="<?php echo $campo9 ?>" placeholder="Valor da Conta" required>
										
									</div>
								</div>

								<!-- Avisos financeiros: opcoes gravadas na conta para vencimento e recebimento. -->
								<div class="col-12">
									<div class="row g-3 border-top pt-3 mt-1">
										<div class="col-md-3">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="aviso_vencimento" id="aviso_vencimento" checked>
												<label class="form-check-label" for="aviso_vencimento">Avisar vencimento</label>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="aviso_baixa" id="aviso_baixa">
												<label class="form-check-label" for="aviso_baixa">Avisar recebimento</label>
											</div>
										</div>
										<div class="col-md-3">
											<label class="form-label">Forma de aviso</label>
											<select class="form-select" name="aviso_forma" id="aviso_forma">
												<option value="email">E-mail</option>
												<option value="whatsapp">WhatsApp</option>
												<option value="ambos">Ambos</option>
											</select>
										</div>
										<div class="col-md-3">
											<label class="form-label">Dias antes</label>
											<input type="number" class="form-control" name="aviso_dias" id="aviso_dias" value="2" min="0">
										</div>
									</div>
								</div>


								

							</div>

							
						</div>

						<div class="tab-pane fade" id="contas" role="tabpanel" aria-labelledby="profile-tab">

								<div class="row mb-4">
									<div class="col-md-1">
										<input type="text" class="form-control" name="<?php echo $campo2 ?>"  id="id-cliente" placeholder="Id do Cliente" readonly>
									</div>

									<div class="col-md-3">
										<input type="text" class="form-control" name="nome-cliente"  id="nome-cliente" placeholder="Nome do Cliente" readonly>
									</div>
								</div>

								<small>
								<div class="tabela bg-light" id="listar-clientes">

								</div>
								</small>

						</div>
						
					</div>

					

					<small><div id="mensagem" align="center"></div></small>

					<input type="text" class="form-control" name="id"  id="id">


				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar">Fechar</button>
					<button  type="submit" class="btn btn-primary">Salvar</button>
				</div>
			</form>
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

					<?php //require_once("verificar_adm.php"); ?>

					<small><div id="mensagem-excluir" align="center"></div></small>

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
<div class="modal fade" id="modalDados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Conta <span id="campo1"></span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			
				<div class="modal-body">
					<small>
					
					
						<span><b><?php echo $campo2 ?>:</b> <span id="campo2"></span></span>
						<span class="mx-4"><b>Saída</b> <span id="campo3" ></span>
						</span>	
						<hr style="margin:6px;">

						<span><b><?php echo $campo4 ?>:</b> <span id="campo4"></span></span>
						<span class="mx-4"><b>Plano de Conta:</b> <span id="campo5" ></span>
						</span>	
						<hr style="margin:6px;">

						
						<span><b>Data Emissão:</b> <span id="campo6"></span></span>
						<span class="mx-4"><b>Vencimento:</b> <span id="campo7" ></span>
						</span>	
						<hr style="margin:6px;">

						<span><b>Frequência:</b> <span id="campo8"></span></span>
						<span class="mx-4"><b><?php echo $campo9 ?>:</b> R$ <span id="campo9" ></span>
						</span>	
						<hr style="margin:6px;">

						<span><b>Usuário Lanc:</b> <span id="campo10"></span></span>
						<span class="mx-4"><b>Usuário Baixa:</b> <span id="campo11" ></span>
						</span>	
						<hr style="margin:6px;">

						<span><b>Status Conta:</b> <span id="campo13"></span></span>
								
					</small>
		
				</div>					
           </div>	

		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalParcelar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal">Parcelar Conta</span> - <span id="descricao-parcelar"></span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="form-parcelar" method="post">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-3">
							<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label">Valor</label>
										<input type="text" class="form-control" name="valor-parcelar"  id="valor-parcelar"  readonly>
									</div>
						</div>

						<div class="col-md-3">
							<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label">Parcelas</label>
										<input type="number" class="form-control" name="qtd-parcelar"  id="qtd-parcelar"  required>
									</div>
						</div>

						<div class="col-md-6">
							<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label">Frequência das Parcelas</label>
										<select class="form-select" aria-label="Default select example" name="frequencia-parcelar" id="frequencia-parcelar">

											<?php 
											$con = new ContasReceber;
											$pdo = $con->conectar();
											$query = $pdo->query("SELECT * FROM frequencias order by id asc");
											$res = $query->fetchAll(PDO::FETCH_ASSOC);
											for($i=0; $i < @count($res); $i++){
												foreach ($res[$i] as $key => $value){	}
													$id_item = $res[$i]['id'];
												$nome_item = $res[$i]['nome'];

												if($nome_item != 'Uma Vez' and $nome_item != 'Única'){
												
												?>
												<option <?php if($nome_item == 'Mensal'){ ?> selected <?php } ?> value="<?php echo $nome_item ?>"><?php echo $nome_item ?></option>

											<?php } } ?>


										</select>
									</div>
						</div>
					</div>	

				


					<small><div id="mensagem-parcelar" align="center"></div></small>

					<input type="hidden" class="form-control" name="id-parcelar"  id="id-parcelar">


				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-parcelar">Fechar</button>
					<button type="submit" class="btn btn-primary">Parcelar</button>
				</div>
			</form>
		</div>
	</div>
</div>







<!-- Modal -->
<div class="modal fade" id="modalBaixar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal">Baixar Conta</span> - <span id="descricao-baixar"></span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="form-baixar" method="post">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-6">
							<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label">Valor <small class="text-muted">(Total ou Parcial)</small></label>
										<input onkeyup="totalizar()" type="text" class="form-control" name="valor-baixar"  id="valor-baixar" required>
									</div>
						</div>

						
						<div class="col-md-6">
							<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label">Local Entrada</label>
										<input  type="text" class="form-control" aria-lab name="entrada-baixar" id="entrada-baixar" readonly>
											
									</div>
						</div>
						
					</div>	


					<div class="row">
						<div class="col-md-6">
							<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label">Desconto em R$</label>
										<input onkeyup="totalizar()" type="text" class="form-control" name="valor-desconto"  id="valor-desconto" placeholder="Ex 15.00" value="0" >
									</div>
						</div>

						<div class="col-md-6">
							<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label">Multa em R$</label>
										<input onkeyup="totalizar()" type="text" class="form-control" name="valor-multa"  id="valor-multa" placeholder="Ex 15.00" value="0">
									</div>
						</div>
									
					</div>


					<div class="row">
						<div class="col-md-6">
							<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label">Júros em R$</label>
										<input onkeyup="totalizar()" type="text" class="form-control" name="valor-juros"  id="valor-juros" placeholder="Ex 0.15" value="0">
									</div>
						</div>

						<div class="col-md-6">
							<div class="mb-3">
										<label for="exampleFormControlInput1" class="form-label">SubTotal</label>
										<input type="text" class="form-control" name="subtotal"  id="subtotal" readonly>
									</div>	
						</div>
					</div>
				
				


					<small><div id="mensagem-baixar" align="center"></div></small>

					<input type="hidden" class="form-control" name="id-baixar"  id="id-baixar">


				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-baixar">Fechar</button>
					<button type="submit" class="btn btn-success">Baixar</button>
				</div>
			</form>
		</div>
	</div>
</div>






<!-- Modal -->
<div class="modal fade" id="modalResiduos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal">Resíduos da Conta</span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			
				<div class="modal-body">

					<small><div id="listar_residuos"></div></small>

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
			<form id="form2" method="post" action="?router=ContasReceber/gerar "target="">
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
						<option value="">Pendentes / Pagas</option>
						<option value="Pendente">Pendentes</option>
						<option value="Paga">Pagas</option>
				
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
<script src="config/js/ajax.js"></script>

<script>
$(document).ready(function() {
		var cat = $('#cat_despesas').val();
		listarDespesas(cat);
		listarClientes();
		$('#cat_despesas').change(function(){
			var cat = $(this).val();
			listarDespesas(cat);
		});


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


	function listarClientes(){
		var pag = "<?=$pagina?>";
		$.ajax({
			url: pag + "/listar_clientes",
			method: 'POST',
			data: $('#form').serialize(),
			dataType: "html",

			success:function(result){
				$("#listar-clientes").html(result);
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

	
	console.log(subtotal)

	$('#subtotal').val(subtotal);

}


function relatorio(){
    
    $('#mensagem').text('');
    $('#tituloModal2').text('Gerar Relatório');
    var myModal = new bootstrap.Modal(document.getElementById('modalForm2'), {
        backdrop: 'static',
    });
    myModal.show();
    
}




</script>
