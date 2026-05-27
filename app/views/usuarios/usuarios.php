<?php 
use app\models\CrudUsuarios;
use app\models\Permissoes;

$pagina = '?router=User';
//VARIAVEIS DOS INPUTS
$campo1 = 'Nome';
$campo2 = 'Email';
$campo3 = 'Senha';
$campo4 = 'Nivel';


?>
<div class="row my-3">
	<div class="col-md-12 container-fluid mb-4 mx-4">
<div class="col-md-12 my-3">
	<a href="#" onclick="inserir()" type="button" class="btn btn-dark btn-sm">Novo Usuário</a>
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

					<div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label"><?php echo $campo1 ?></label>
						<input type="text" class="form-control" name="<?php echo $campo1 ?>" placeholder="<?php echo $campo1 ?>" id="<?php echo $campo1 ?>" required>
					</div>

					<div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label"><?php echo $campo2 ?></label>
						<input type="email" class="form-control" name="<?php echo $campo2 ?>" placeholder="<?php echo $campo2 ?>" id="<?php echo $campo2 ?>" required>
					</div>

					<div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label"><?php echo $campo3 ?></label>
						<input type="password" class="form-control" name="<?php echo $campo3 ?>" placeholder="Informe a senha" id="<?php echo $campo3 ?>" autocomplete="new-password">
						<small class="text-muted">Ao editar, deixe em branco para manter a senha atual.</small>
					</div>

					
					<div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label">Nível </label>
						<select class="form-select" aria-label="Default select example" name="<?php echo $campo4 ?>" id="<?php echo $campo4 ?>">
							<?php
							// Perfis padronizados: remove confusao entre Adm/Administrador e Comum/Usuario.
							foreach(Permissoes::perfisDisponiveis() as $nome_item){
								?>
								<option value="<?php echo $nome_item ?>"><?php echo $nome_item ?> - <?php echo Permissoes::descricaoPerfil($nome_item); ?></option>

							<?php } ?>


						</select>
					</div>	

					<small><div id="mensagem" align="center"></div></small>

					<input type="hidden" class="form-control" name="id"  id="id">


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


<script type="text/javascript">var pag ="<?=$pagina?>" </script>
<script src="config/js/ajax.js"></script>

