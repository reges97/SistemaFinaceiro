
<?php
// Rodape/versionamento: centraliza o texto "Powered by" para controlar a versao a cada alteracao.
require_once __DIR__ . '/../../config/version.php';
?>

<!-- Rodape padronizado para acompanhar o novo layout interno. -->
<footer class="footer app-footer py-3 mt-auto">
<div class="container-fluid px-4">
<div class="d-flex align-items-center justify-content-between small">
<div class="text-muted">Copyright &copy; Your Website 2023</div>
<div class="ms-auto"><?php echo htmlspecialchars(SISTEMA_POWERED_BY, ENT_QUOTES, 'UTF-8'); ?></div>
</main>
</div>
</footer>



</div>



</script>



</body >


</html>


  <!-- Modal -->
  <div class="modal fade" id="modalPerfil" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="rd-navbar-wrap">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Editar Dados</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="form-perfil" method="post">
				<div class="modal-body">

					<div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label">Nome</label>
						<input type="text" class="form-control" name="nome-usuario" placeholder="Nome" value="<?php echo $nome_usuario; ?>">
					</div>

					<div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label">Email</label>
						<input type="email" class="form-control" name="email-usuario" placeholder="Email" value="<?php echo $email_usuario ?>">
					</div>

					<div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label">Senha</label>
						<input type="password" class="form-control" name="senha-usuario" placeholder="Nova senha" autocomplete="new-password">
						<small class="text-muted">Deixe em branco para manter a senha atual.</small>
					</div>

					<small><div id="mensagem-perfil" align="center"></div></small>

					<input type="hidden" class="form-control" name="id-usuario"  value="<?php echo $id_usuario ?>">


				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-perfil">Fechar</button>
					<button type="submit" class="btn btn-primary">Salvar</button>
				</div>
			</form>
		</div>
	</div>
</div>

</div>
	</div>



<!-- Mascaras JS -->
<script type="text/javascript" src="config/js/mascaras.js"></script>
<script src="config/js/funcoes.js"></script>
<script src="config/alertifyjs/alertify.js"></script>
<script src="config/bootstrap/js/bootstrap.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 




<!-- CoreUI and necessary plugins-->
<script src="config/vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
<script src="config/vendors/simplebar/js/simplebar.min.js"></script>
<!-- Plugins and scripts required by this view-->
<!--<script src="config/vendors/chart.js/js/chart.min.js"></script>
<script src="config/vendors/@coreui/chartjs/js/coreui-chartjs.js"></script>
<script src="config/vendors/@coreui/utils/js/coreui-utils.js"></script>
<script src="config/js2/main.js"></script>-->
<script>
</script>


<!-- Ajax para inserir ou editar dados -->
<script type="text/javascript">
	$("#form-perfil").submit(function () {
		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "?router=Site/editar",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#mensagem-perfil').removeClass()
				if (mensagem.trim() == "Salvo com Sucesso") {
                    $('#nome').val('');
                    $('#cpf').val('');
                    $('#btn-fechar-perfil').click();
                    // Redirecionamento por permissao: administrador/financeiro retornam ao homePainel.
                    window.location = "<?php echo (function_exists('menuPode') && menuPode('Site', 'homePainel')) ? '?router=Site/homePainel' : '?router=Site/home'; ?>";
                } else {
                	$('#mensagem-perfil').addClass('text-danger')
                }

                $('#mensagem-perfil').text(mensagem)
            },

            cache: false,
            contentType: false,
            processData: false,
            
        });

	});

	
</script>
