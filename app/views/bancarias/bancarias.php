<?php 
use app\controllers\Banca;

$pagina = '?router=Banca';


//VARIAVEIS DOS INPUTS
$campo1 = 'Banco';
$campo2 = 'Agencia';
$campo3 = 'Conta';
$campo4 = 'Tipo';
$campo5 = 'Pessoa';
$campo6 = 'Doc';
$campo7 = 'Saldo';
$campo8 = 'Saldo_ini';

?>
<div class="row my-3">
	<div class="col-md-12 container-fluid mb-4 mx-4">
<div class="col-md-12 my-3">
	<a href="#" onclick="inserir()" type="button" class="btn btn-primary btn-sm">Nova Conta Bancária</a>
	<small class="mx-4"><a style="text-decoration:none" href="?router=Banca/gerarExcel"><span class="nav-icon"><img src="config/img/excel.ico" width="30px"></span></a></small>
</div>





<small>
	<div class="tabela bg-light" id="listar">

	</div>
</small>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal">Inserir Registro</span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="form" method="post">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-4 col-sm-12">
							<div class="mb-3">
								<label for="exampleFormControlInput1" class="form-label"><?php echo $campo1 ?> </label>
								<select class="form-select" aria-label="Default select example" name="<?php echo $campo1 ?>" id="<?php echo $campo1 ?>">
									<?php 
                                     $seleciona = new Banca;
                                     $res = $seleciona->selecaoBanco();
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
								<label for="exampleFormControlInput1" class="form-label"><?php echo $campo2 ?></label>
								<input type="text" class="form-control" name="<?php echo $campo2 ?>" placeholder="<?php echo $campo2 ?>" id="<?php echo $campo2 ?>" required>
							</div>
						</div>
					

				
						<div class="col-md-4 col-sm-12">
							<div class="mb-3">
								<label for="exampleFormControlInput1" class="form-label"><?php echo $campo3 ?></label>
								<input type="text" class="form-control" name="<?php echo $campo3 ?>" placeholder="<?php echo $campo3 ?>" id="<?php echo $campo3 ?>" required>
							</div>
						</div>
						<div class="col-md-4 col-sm-12">
							<div class="mb-3">
								<label for="exampleFormControlInput1" class="form-label"><?php echo $campo4 ?></label>
								<select class="form-select" aria-label="Default select example" name="<?php echo $campo4 ?>" id="<?php echo $campo4 ?>">
									<option value="Corrente">Corrente</option>
									<option value="Poupança">Poupança</option>
								</select>
							</div>
						</div>
					

				

					
						<div class="col-md-4 col-sm-12">
							<div class="mb-3">
								<label for="exampleFormControlInput1" class="form-label"><?php echo $campo5 ?></label>
								<select class="form-select" aria-label="Default select example" name="<?php echo $campo5 ?>" id="<?php echo $campo5 ?>">
									<option value="Física">Física</option>
									<option value="Jurídica">Jurídica</option>

								</select>
							</div>
						</div>
						<div class="col-md-4 col-sm-12">
							<div class="mb-3">
								<label for="exampleFormControlInput1" class="form-label">CPF / CNPJ</label>
								<input type="text" class="form-control" name="<?php echo $campo6 ?>" id="<?php echo $campo6 ?>" required>
							</div>
						</div>
					
						<div class="col-md-4 col-sm-12">
							<div class="mb-3">
								<label for="exampleFormControlInput1" class="form-label">Saldo Inicial</label>
								<input type="text" class="form-control" name="<?php echo $campo8?>" placeholder="<?php echo $campo8 ?>" id="<?php echo $campo8?>" required>
							</div>
						
					</div>
					
						
					
					<div class="col-md-4 col-sm-12">
							<div class="mb-3">
								<label for="exampleFormControlInput1" class="form-label">Saldo Atual</label>
								<input type="text" class="form-control" name="<?php echo $campo7?>" placeholder="<?php echo $campo7 ?>" id="<?php echo $campo7?>" required>
							</div>
						
					</div>
					

					

					<small id="mensagem" class="d-block text-center"></small>

					<input type="hidden" class="form-control" name="id"  id="id">
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar">Fechar</button>
					<button type="submit" class="btn btn-primary">Salvar</button>
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


<script type="text/javascript">var pag = "<?=$pagina?>"</script>
<script src="config/js/ajax.js"></script>




<script>
	$(document).ready(function() {
		$('#<?=$campo6?>').mask('000.000.000-00');
		$('#<?=$campo6?>').attr('placeholder','CPF');

		$('#<?=$campo5?>').change(function(){
			if($(this).val() == 'Física'){
				$('#<?=$campo6?>').mask('000.000.000-00');
				$('#<?=$campo6?>').attr('placeholder','CPF');
			}else{
				$('#<?=$campo6?>').mask('00.000.000/0000-00');
				$('#<?=$campo6?>').attr('placeholder','CNPJ');
			}
		});
	});
</script>