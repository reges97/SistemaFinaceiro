<?php 
use app\models\CrudMenu;
use app\models\CrudUsuarios;
use app\models\Permissoes;

$pagina = '?router=Acessos';
//VARIAVEIS DOS INPUTS
$campo1 = 'Nome';
$campo2 = 'Menu';
$campo3 = 'Nivel';
$campo4 = 'Acesso';
$campo5 = 'Sub_menu';




?>
<div class="row my-3">
	<div class="col-md-12 container-fluid mb-4 mx-4">
<div class="col-md-12 my-3">
	<a href="#" onclick="inserir()" type="button" class="btn btn-dark btn-sm">Novo Acesso</a>
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
						<label for="exampleFormControlInput1" class="form-label">Nome </label>
						<select class="form-select" aria-label="Default select example" name="<?php echo $campo1 ?>" id="<?php echo $campo1 ?>">
							<?php 
							$selecionar = new CrudUsuarios;
                              $res = $selecionar->listarUsu();
							for($i=0; $i < @count($res); $i++){
								foreach ($res[$i] as $key => $value){	}
								$usu_id = $res[$i]['id'];
								$nome_usu = $res[$i]['nome_usu'];
								?>
								<option value="<?php echo $usu_id ?>"><?php echo $nome_usu ?></option>

							<?php } ?>


						</select>
                </div>

                <div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label">Menu </label>
						<select class="form-select" aria-label="Default select example" name="<?php echo $campo2 ?>" id="<?php echo $campo2 ?>">
							<?php 
							$selecionar = new CrudMenu;
                              $res = $selecionar->listaMenu();
							for($i=0; $i < @count($res); $i++){
								foreach ($res[$i] as $key => $value){	}
								$menu_id = $res[$i]['id_menu'];
								$menu = $res[$i]['menu'];
								?>
								<option value="<?php echo $menu_id?>"><?php echo $menu ?></option>

							<?php } ?>


						</select>
                </div>
                <div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label">Nível </label>
						<select class="form-select" aria-label="Default select example" name="<?php echo $campo3 ?>" id="<?php echo $campo3 ?>">
							<?php
							// Perfis padronizados: permissoes manuais seguem os mesmos niveis do cadastro de usuario.
							foreach(Permissoes::perfisDisponiveis() as $nivel){
								?>
								<option value="<?php echo $nivel ?>"><?php echo $nivel ?></option>

							<?php } ?>


						</select>
                </div>

				<!-- Submenu desativado: tabela de submenu nao existe em algumas instalacoes e quebrava a tela de permissoes. -->
				<input type="hidden" name="<?php echo $campo5 ?>" id="<?php echo $campo5 ?>" value="">


					
					<div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label">Acesso </label>
						<select class="form-select" aria-label="Default select example" name="<?php echo $campo4 ?>" id="<?php echo $campo4 ?>">
                          <option value='Nao'>Não</option>
				           <option value='Sim'>Sim</option>
								


						</select>
					</div>	

					<small id="mensagem" class="d-block text-center"></small>

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


<script type="text/javascript">var pag ="<?=$pagina?>" </script>
<script src="config/js/ajax.js"></script>
