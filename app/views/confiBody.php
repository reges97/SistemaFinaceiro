<?php

use app\models\CrudUsuarios;
use app\models\Permissoes;


@session_start();
@$id_usuario= $_SESSION['id'];
@$nivel = $_SESSION['nivel'];
// Permissao do menu superior legado: usa a mesma regra central do menu lateral.
$nivel_permissao = Permissoes::normalizarNivel($nivel);
//RECUPERAR DADOS DO USUÁRIO
//$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
$recupera = new CrudUsuarios;
$res = $recupera->recuperaDados();

//$id_usuario = $res[0]['id'];
@$nome_usuario = $res[0]['nome'];
@$email_usuario = $res[0]['email'];
@$nivel_usuario = $res[0]['nivel'];
if (isset($res[0]['nivel'])) {
	$nivel_permissao = Permissoes::normalizarNivel($res[0]['nivel']);
	$_SESSION['nivel'] = $nivel_permissao;
}

if (Permissoes::emailAdministradorTotal($email_usuario ?? ($_SESSION['email'] ?? ''))) {
	$nivel_permissao = 'Administrador';
	$_SESSION['nivel'] = 'Administrador';
}

// Link inicial do menu superior legado: checagem direta evita administrador sem retorno ao homePainel.
$temAcessoPainel = in_array($nivel_permissao, ['Administrador', 'Financeiro'], true)
	|| Permissoes::emailAdministradorTotal($email_usuario ?? ($_SESSION['email'] ?? ''));
$menuInicioUrl = $temAcessoPainel ? '?router=site/homePainel' : '?router=Site/home';
$menuInicioTexto = $temAcessoPainel ? 'Painel' : 'Home';

//var_dump($res);

 
//MENUS DO PAINEL
$menu1 = 'home';
$menu2 = 'clientes';
$menu3 = 'niveis';
$menu4 = 'usuarios';
$menu5 = 'bancos';
$menu6 = 'bancarias';
$menu7 = 'cat_despesas';
$menu8 = 'despesas';
$menu9 = 'frequencias';
$menu10 = 'formas_pgtos';
$menu11 = 'produtos';
$menu12 = 'cat_produtos';
$menu13 = 'fornecedores';
$menu14 = 'estoques';
$menu15 = 'caixa';
$menu16 = 'contas_pagar';
$menu17 = 'contas_receber';
$menu18 = 'movimentacao';
$menu19 = 'saldo_contas';
$menu20 = 'relProdutos_class';
$menu21 = 'relSaldos_class';
$menu22 = 'vendas';
if(@$_GET['pag'] == ""){
	$pag = $menu1;
}else{
	$pag = $_GET['pag'];
}

@$nome_sistema = 'Sistema Financeiro';
@$usl_sistema = 'http://localhost/sistemaFinanciro/';
@$email_adm = getenv('ADMIN_EMAIL') ?: 'admin@sistema.local';
@$nome_admin = getenv('ADMIN_NAME') ?: 'Administrador';

?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $nome_sistema; ?></title>
    <link rel="shortcut icon" href="config/img/close.ico" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Reginaldo">
    
    <link href="config/img/logo.ico" rel="shortcut icon" type="image/x-icon">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="config/alertifyjs/css/alertify.css">
	

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<link rel="stylesheet" type="text/css" href="config/DataTables/datatables.min.css"/>
	<!-- Ajuste visual global versionado para evitar cache antigo nas telas legadas. -->
	<link rel="stylesheet" type="text/css" href="config/css/style.css?v=20260526"/>

	<script type="text/javascript" src="config/DataTables/datatables.min.js"></script>
	<script src="config/select2/js/select2.js"></script>
	<link rel="stylesheet" type="text/css" href="config/select2/css/select2.css">
   
</head>
<header class="section page-header rd-navbar-transparent-wrap">
<div class="rd-navbar-wrap">
</div>

<body>
<div class="row my-6">
	<div class="col-md-12 container-fluid mb-8 mx-8">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<div class="container-fluid">
			<a class="navbar-brand" href="#"><img src="config/img/logomarca.png" width="80px"></a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<!-- Link inicial por permissao: Administrador/Financeiro acessam homePainel pelo item Painel. -->
					<li class="nav-item">
						<a class="nav-link active" aria-current="page" href="<?php echo $menuInicioUrl; ?>">
							<img src="config/img/home.png" width="30px"> <?php echo $menuInicioTexto; ?>
						</a>
					</li>
					
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						Cadastros <img src="config/img/cadastro2.png" width="20px">
						</a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
							<li><a class="dropdown-item" href="?router=Clientes/<?php echo $menu2 ?>"><img src="config/img/clientes.ico" width="20px"> Clientes</a></li>
							<li><a class="dropdown-item" href="?router=User/<?php echo $menu4 ?>"><img src="config/img/user.ico" width="20px"> Usuários</a></li>
							<li><a class="dropdown-item" href="?router=Bancos/<?php echo $menu5 ?>"><img src="config/img/bank.ico" width="20px"> Bancos</a></li>
							<li><a class="dropdown-item" href="?router=Adm/<?php echo $menu3 ?>"><img src="config/img/levels.ico" width="20px"> Níveis de Usuários</a></li>
							<li><a class="dropdown-item" href="?router=Banca/<?php echo $menu6 ?>"><img src="config/img/contas.ico" width="20px"> Contas Bancárias</a></li>
							<li><a class="dropdown-item" href="?router=CatDespesas/<?php echo $menu7 ?>"><img src="config/img/categoria.ico" width="20px"> Categoria Despesas</a></li>
							<li><a class="dropdown-item" href="?router=Despesas/<?php echo $menu8 ?>"><img src="config/img/despesa.ico" width="20px"> Despesas</a></li>
							<li><a class="dropdown-item" href="?router=Freq/<?php echo $menu9 ?>"><img src="config/img/frequencia.ico" width="20px"> Frequências</a></li>
							<li><a class="dropdown-item" href="?router=FormPgtos/<?php echo $menu10 ?>"><img src="config/img/formas.ico" width="20px"> Formas PGTO</a></li>
						</ul>
					</li>
					
					
					<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					Produtos <img src="config/img/caixas.png" width="20px">
						</a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
							<li><a class="dropdown-item" href="?router=Prod/<?php echo $menu11 ?>"><img src="config/img/caixas.png" width="20px"> Produtos</a></li>
							<li><a class="dropdown-item" href="?router=CatProd/<?php echo $menu12 ?>"><img src="config/img/categProd.ico" width="20px"> Categorias</a></li>
							<li><a class="dropdown-item" href="?router=Forne/<?php echo $menu13 ?>"><img src="config/img/fornecedor.ico" width="20px"> Fornecedores</a></li>
							<li><a class="dropdown-item" href="?router=Prod/<?php echo $menu11 ?>/&estoque=sim"><img src="config/img/estoqueBaixo.ico" width="20px"><img src="config/img/estoqueBaixo.ico" width="20px"> Estoque Baixo</a></li>
							
						</ul>
					</li>


					<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					Agendas <img src="config/img/agenda.png" width="20px">
						</a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
							<li><a class="dropdown-item" href="?router=ContasPagar/<?php echo $menu16 ?>"><img src="config/img/pagar2.ico" width="20px"> Contas à Pagar</a></li>
							<li><a class="dropdown-item" href="?router=ContasReceber/<?php echo $menu17 ?>"><img src="config/img/receber2.ico" width="20px"> Contas à Receber</a></li>
							
										
						</ul>
					</li>
					<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					Movimentação <img src="config/img/contabilidade.png" width="20px">
						</a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
							<li><a class="dropdown-item" href="?router=Mov/<?php echo $menu18 ?>"><img src="config/img/mov.ico" width="20px"> Movimentação</a></li>
							<li><a class="dropdown-item" href="?router=Caixa/<?php echo $menu15 ?>"><img src="config/img/caixa.ico" width="20px"> Caixa</a></li>
							<li><a class="dropdown-item" href="?router=Saldo/<?php echo $menu19 ?>"><img src="config/img/saldo.ico" width="20px"> Contas bancarias</a></li>
							<li><a class="dropdown-item" href="?router=Prod/<?php echo $menu20 ?>"><img src="config/img/relatorio.ico" width="20px"> Relatório Produtos</a></li>
							<li><a class="dropdown-item" href="?router=Saldo/<?php echo $menu21 ?>"><img src="config/img/relatorio.ico" width="20px"> Relatório Saldo</a></li>
							
						</ul>
					</li>

					<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					Vendas <img src="config/img/vendas.ico" width="20px">
						</a>
						<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
							<li><a class="dropdown-item" href="?router=vendas/<?php echo $menu22 ?>"><img src="config/img/vendas.ico" width="20px"> Vendas</a></li>
						
							
										
						</ul>
					</li>

				</ul>



				
				<div class="d-flex mr-4">
					
						<ul class="navbar-nav">
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								<?php echo $nome_usuario;?><img class="img-profile rounded-circle" src="config/img/user.jpg" width="30px" height="30px"></a>
							<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
								<li><a class="dropdown-item"  href="?router=Site/home/&listar=<?php echo $_SESSION['id']?>" 
								data-bs-toggle="modal" data-bs-target="#modalPerfil">Editar Dados</a></li>

								<li><hr class="dropdown-divider"></li>
								<li><a class="dropdown-item" href="?router=Site/logout">Sair</a></li>
							</ul>
						</li>
					</ul>
					
				</div>
			</div>
		</div>
	</nav>
	
	


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

					<small id="mensagem-perfil" class="d-block text-center"></small>

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
                    window.location = "?router=Site/home";
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





</header>
</body>
</html>
