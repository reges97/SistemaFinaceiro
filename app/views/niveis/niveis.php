<?php 

$pagina ='?router=Adm';




?>

<div class="container-fluid mb-4 mx-4">
<div class="col-md-12 my-3">
	<a href="#" onclick="inserir()" type="button" class="btn btn-dark btn-sm">Novo Nível</a>
</div>

<!-- Niveis: tabela em bloco proprio para evitar div dentro de small. -->
<div class="tabela bg-light" id="listar"></div>

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
						<label for="exampleFormControlInput1" class="form-label">Nível</label>
						<input type="text" class="form-control" name="nivel" placeholder="Nível do Usuário" id="nivel" required>
					</div>

					<small id="mensagem" class="d-block text-center"></small>

					<input type="hidden" class="form-control" name="id"  id="id">


				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar">Fechar</button>
					<button type="submit" class="btn btn-primary" id="salvar" name="salvar">Salvar</button>
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

					<hr><small id="mensagem-excluir" class="d-block"></small>

					<input type="hidden" class="form-control" name="id-excluir"  id="id-excluir">


				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-excluir">Fechar</button>
					<button type="b" class="btn btn-danger" >Excluir</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">var pag ="<?=$pagina?>" </script>
<script src="config/js/ajax.js"></script>
