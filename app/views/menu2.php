
<?php


use app\models\CrudAcessos;
use app\models\CrudUsuarios;
use app\models\Permissoes;

@session_start();
@$id_usuario= $_SESSION['id'];
@$nivel = $_SESSION['nivel'];
// Perfil efetivo inicial: sera sincronizado novamente apos recuperar o usuario do banco.
$nivel_permissao = Permissoes::normalizarNivel($nivel);

if (!function_exists('menuPode')) {
	function menuPode($controller, $method = '')
	{
		global $nivel_permissao, $id_usuario;
		return Permissoes::canAccessUser($nivel_permissao, $controller, $method, $id_usuario);
	}
}

if (!function_exists('menuTemGrupo')) {
	function menuTemGrupo(array $rotas)
	{
		foreach ($rotas as $rota) {
			if (menuPode($rota[0], $rota[1] ?? '')) {
				return true;
			}
		}
		return false;
	}
}
//RECUPERAR DADOS DO USUÁRIO
//$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
$recupera = new CrudUsuarios;
$res = $recupera->recuperaDados();

//$id_usuario = $res[0]['id'];
@$nome_usuario =  $res[0]['nome_usu'];
@$email_usuario = $res[0]['email'];
@$nivel_usuario = $res[0]['nivel'];
// Sincronizacao do menu: evita menu limitado quando a sessao antiga ainda guarda outro nivel.
if (isset($res[0]['nivel'])) {
	$nivel_permissao = Permissoes::normalizarNivel($res[0]['nivel']);
	$_SESSION['nivel'] = $nivel_permissao;
}

// Administrador principal: esse e-mail sempre visualiza o menu completo.
if (Permissoes::emailAdministradorTotal($email_usuario ?? ($_SESSION['email'] ?? ''))) {
	$nivel_permissao = 'Administrador';
	$_SESSION['nivel'] = 'Administrador';
}

// Link inicial do menu: checagem direta evita que o administrador fique preso na home operacional.
$temAcessoPainel = in_array($nivel_permissao, ['Administrador', 'Financeiro'], true)
	|| Permissoes::emailAdministradorTotal($email_usuario ?? ($_SESSION['email'] ?? ''));
$menuInicioUrl = $temAcessoPainel ? '?router=site/homePainel' : '?router=Site/home';
$menuInicioTexto = $temAcessoPainel ? 'Painel' : 'Home';
// Permissoes manuais por menu_id: garante que Agendas apareca para usuario com acesso a pagar/receber.
$menusPermitidosUsuario = Permissoes::menusPermitidosUsuario($id_usuario);
$ehAdministradorConfiguracao = $nivel_permissao === 'Administrador'
	|| Permissoes::emailAdministradorTotal($email_usuario ?? ($_SESSION['email'] ?? ''));
$podeContasPagar = menuPode('ContasPagar') || in_array(12, $menusPermitidosUsuario, true);
$podeContasReceber = menuPode('ContasReceber') || in_array(13, $menusPermitidosUsuario, true);
$podeLancaDespesas = menuPode('LancaDespesas') || in_array(14, $menusPermitidosUsuario, true);
$mostrarAgenda = $podeContasPagar || $podeContasReceber || $podeLancaDespesas;
// Configuracao: administrador sempre ve; permissoes manuais liberam submenus especificos.
$podeUsuarios = $ehAdministradorConfiguracao || menuPode('User') || in_array(23, $menusPermitidosUsuario, true);
$podeNiveis = $ehAdministradorConfiguracao || menuPode('Adm') || in_array(24, $menusPermitidosUsuario, true);
$podeAcessos = $ehAdministradorConfiguracao || menuPode('Acessos') || in_array(25, $menusPermitidosUsuario, true);
$podeConfigEmail = $ehAdministradorConfiguracao || menuPode('Configuracoes', 'email') || in_array(29, $menusPermitidosUsuario, true);
$podeConfigWhatsapp = $ehAdministradorConfiguracao || menuPode('Configuracoes', 'whatsapp') || in_array(30, $menusPermitidosUsuario, true);
// Layouts de e-mail: permissao propria para personalizar os modelos de avisos financeiros.
$podeLayoutEmails = $ehAdministradorConfiguracao || menuPode('Configuracoes', 'layoutEmails') || in_array(31, $menusPermitidosUsuario, true);
$mostrarConfiguracao = $podeUsuarios || $podeNiveis || $podeAcessos || $podeConfigEmail || $podeConfigWhatsapp || $podeLayoutEmails;

$acesso = new CrudAcessos;
$res2 = $acesso->listarUsu();

@$acesso_usuario = $res2[0]['nome_usu'];
@$acesso_nivel = $res2[0]['nivel_aces'];
@$acesso_menu = $res2[0]['menu'];
@$acesso_status = $res2[0]['acesso'];
@$acesso_ativo = $res2[0]['ativo'];

//var_dump($acesso_usuario);
//var_dump($acesso_nivel);
//var_dump($acesso_menu);
//var_dump(@$acesso_status);
//var_dump(@$acesso_ativo);



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
$menu23 = 'controle_caixa';
$menu24 = 'conciliacao';
$menu25 = 'fluxoCaixa';
$menu26 = 'acessos';
$menu27 = 'lanca_depesas';
$menu28 = 'fluxoDiario';

if(@$_GET['pag'] == ""){
	$pag = $menu1;
}else{
	@$pag = $_GET['pag'];
}



@$nome_sistema = 'Sistema Financeiro';
@$usl_sistema = 'http://localhost/sistemaFinanciro/';
@$email_adm = getenv('ADMIN_EMAIL') ?: 'admin@sistema.local';
@$nome_admin = getenv('ADMIN_NAME') ?: 'Administrador';

?>


<!DOCTYPE html><!--
* CoreUI - Free Bootstrap Admin Template
* @version v4.2.2
* @link https://coreui.io/product/free-bootstrap-admin-template/
* Copyright (c) 2023 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://github.com/coreui/coreui-free-bootstrap-admin-template/blob/main/LICENSE)
--><!-- Breadcrumb-->
<html lang="br">
  <head>
    <base href="">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">

    <link rel="apple-touch-icon" sizes="57x57" href="config/assets/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="config/assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="config/assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="config/assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="config/assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="config/assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="config/assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="config/assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="config/assets/favicon/apple-icon-180x180.png">
    
    <link rel="manifest" href="config/assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="config/assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <title><?php echo $nome_sistema; ?></title>
    <link rel="shortcut icon" href="config/img/open.ico" type="image/x-icon">

   
    
  <link href="config/img/favicon.ico" rel="shortcut icon" type="image/x-icon">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="config/alertifyjs/css/alertify.css">
	

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<link rel="stylesheet" type="text/css" href="config/DataTables/datatables.min.css"/>

	<script type="text/javascript" src="config/DataTables/datatables.min.js"></script>
	<script src="config/select2/js/select2.js"></script>
	<link rel="stylesheet" type="text/css" href="config/select2/css/select2.css">
   

    
   
    <!-- Vendors styles-->
    <link rel="stylesheet" href="config/vendors/simplebar/css/simplebar.css">
    <link rel="stylesheet" href="config/css2/vendors/simplebar.css">
    <!-- Main styles for this application-->
    <link href="config/css2/style.css" rel="stylesheet">
    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <link href="config/css2/examples.css" rel="stylesheet">
	<!-- Ajuste visual global: carregado por ultimo para padronizar telas, tabelas e modais do sistema. -->
	<!-- Versionamento visual 1.1: cache atualizado para carregar logo e icones profissionais. -->
	<link rel="stylesheet" type="text/css" href="config/css/style.css?v=20260527"/>
	<style>
		/* Menu por perfil: oculta visualmente itens que o roteador tambem bloqueia no backend. */
		<?php if($nivel_permissao === 'Administrador') { ?>
		/* Administrador total: impede que filtros visuais deixem o menu incompleto. */
		.sidebar-nav .nav-group,
		.sidebar-nav .nav-item {
			display: block !important;
		}
		<?php } else { ?>
		<?php if(!menuPode('Bancos')) { ?>.sidebar-nav li:has(> a[href*="router=Bancos/"]) { display: none; }<?php } ?>
		<?php if(!menuPode('Banca')) { ?>.sidebar-nav li:has(> a[href*="router=Banca/"]) { display: none; }<?php } ?>
		<?php if(!menuPode('CatDespesas')) { ?>.sidebar-nav li:has(> a[href*="router=CatDespesas/"]) { display: none; }<?php } ?>
		<?php if(!menuPode('Despesas')) { ?>.sidebar-nav li:has(> a[href*="router=Despesas/"]) { display: none; }<?php } ?>
		<?php if(!menuPode('Freq')) { ?>.sidebar-nav li:has(> a[href*="router=Freq/"]) { display: none; }<?php } ?>
		<?php if(!menuPode('FormPgtos')) { ?>.sidebar-nav li:has(> a[href*="router=FormPgtos/"]) { display: none; }<?php } ?>
		<?php if(!menuPode('CatProd')) { ?>.sidebar-nav li:has(> a[href*="router=CatProd/"]) { display: none; }<?php } ?>
		<?php if(!menuPode('Forne')) { ?>.sidebar-nav li:has(> a[href*="router=Forne/"]) { display: none; }<?php } ?>
		<?php if(!$podeContasPagar) { ?>.sidebar-nav li:has(> a[href*="router=ContasPagar/"]) { display: none; }<?php } ?>
		<?php if(!$podeContasReceber) { ?>.sidebar-nav li:has(> a[href*="router=ContasReceber/"]) { display: none; }<?php } ?>
		<?php if(!$podeLancaDespesas) { ?>.sidebar-nav li:has(> a[href*="router=LancaDespesas/"]) { display: none; }<?php } ?>
		/* Agenda e seus itens agora sao renderizados por PHP; CSS fica apenas para acabamento. */
		<?php if(!menuTemGrupo([['Mov'], ['Caixa'], ['Saldo'], ['ControleCaixa'], ['Fluxo'], ['Conci'], ['Diario']])) { ?>.sidebar-nav .nav-group:has(a[href*="router=Mov/"]) { display: none; }<?php } ?>
		<?php if(!menuPode('Vendas')) { ?>.sidebar-nav .nav-group:has(a[href*="router=Vendas/"]) { display: none; }<?php } ?>
		<?php if(!menuPode('Saldo', 'relSaldos_class')) { ?>.sidebar-nav li:has(> a[href*="router=Saldo/"]) { display: none; }<?php } ?>
		<?php if(!$mostrarConfiguracao) { ?>.sidebar-nav .menu-configuracao { display: none; }<?php } ?>
		<?php } ?>
	</style>
    
  </head>


  

    <!-- Layout profissional: remove margens laterais do body e deixa o conteudo centralizado pelo wrapper. -->
    <body class="app-body" >
    <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
      <div class="sidebar-brand d-none d-md-flex">
        
        <!-- Identidade visual 1.1: usa o novo logo profissional do sistema. -->
        <a class="navbar-brand text-center" href="#"><img class="app-sidebar-logo" src="config/img/logo-sistema-financeiro-v2.png" alt="Sistema Financeiro">
        </a>
      </div>
      
      <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
      
        <!-- Link inicial por permissao: Administrador/Financeiro acessam homePainel pelo item Painel. -->
        <li class="nav-item"><a class="nav-link" href="<?php echo $menuInicioUrl; ?>">
        <span class="nav-icon"><i class="bi bi-speedometer2"></i></span> <?php echo $menuInicioTexto; ?> </a></li>
            <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
            
            <span class="nav-icon"><i class="bi bi-folder2-open"></i></span> Cadastros</a>
                   
          
          
            <ul class="nav-group-items">
            
            <li class="nav-item"><a class="nav-link" href="?router=Clientes/<?php echo $menu2 ?>"><span class="nav-icon"><i class="bi bi-people"></i></span> Clientes </a></li>
           
            
            <li class="nav-item"><a class="nav-link" href="?router=Bancos/<?php echo $menu5 ?>"><span class="nav-icon"><i class="bi bi-bank"></i></span> Bancos</a></li>
            
            
            <li class="nav-item"><a class="nav-link" href="?router=Banca/<?php echo $menu6 ?>"><span class="nav-icon"><i class="bi bi-credit-card-2-front"></i></span> Contas Bancarias</a></li>
           
           
            <li class="nav-item"><a class="nav-link" href="?router=CatDespesas/<?php echo $menu7 ?>"><span class="nav-icon"><i class="bi bi-tags"></i></span> Categoria Despesas</a></li>
          
            
            <li class="nav-item"><a class="nav-link" href="?router=Despesas/<?php echo $menu8 ?>"><span class="nav-icon"><i class="bi bi-diagram-3"></i></span>Plano de contas</a></li>
           
           
              <li class="nav-item"><a class="nav-link" href="?router=Freq/<?php echo $menu9 ?>"><span class="nav-icon"><img src="config/img/frequencia.ico" width="20px"> </span> Frequência</a></li>
             
              
              <li class="nav-item"><a class="nav-link" href="?router=FormPgtos/<?php echo $menu10 ?>"><span class="nav-icon"><i class="bi bi-wallet2"></i></span> Formas PGTO</a></li>
              
      
            
          </ul>
        </li>
        <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
        <span class="nav-icon"><i class="bi bi-box-seam"></i></span>Produtos </a>
          <ul class="nav-group-items">
          
            <li class="nav-item"><a class="nav-link" href="?router=Prod/<?php echo $menu11 ?>"><span class="nav-icon"><i class="bi bi-box"></i></span> Produtos</a></li>
          
        
            <li class="nav-item"><a class="nav-link" href="?router=CatProd/<?php echo $menu12 ?>"><span class="nav-icon"><i class="bi bi-grid"></i></span> Categorias Produtos</a></li>
         
           
     
              <li class="nav-item"><a class="nav-link" href="?router=Forne/<?php echo $menu13 ?>"><span class="nav-icon"><i class="bi bi-truck"></i></span> Fornecedores</a></li>
          
         
              <li class="nav-item"><a class="nav-link" href="?router=Prod/<?php echo $menu11 ?>/&estoque=sim"><span class="nav-icon"><i class="bi bi-exclamation-triangle"></i></span> Estoque Baixo</a></li>
          
            </ul>
        </li>
         <?php if($mostrarAgenda) { ?>
         <!-- menu-agendas-v20260527: grupo renderizado por permissao manual/perfil. -->
         <li class="nav-group menu-agendas"><a class="nav-link nav-group-toggle" href="#">
         <span class="nav-icon"><i class="bi bi-calendar2-week"></i></span>Agendas </a>
          <ul class="nav-group-items">
          
          <?php if($podeContasPagar) { ?><li class="nav-item"><a class="nav-link" href="?router=ContasPagar/<?php echo $menu16 ?>"><span class="nav-icon"><img src="config/img/pag.ico" width="20x"></span> Contas à Pagar</a></li><?php } ?>
          
         
          <?php if($podeContasReceber) { ?><li class="nav-item"><a class="nav-link" href="?router=ContasReceber/<?php echo $menu17 ?>"><span class="nav-icon"><img src="config/img/rec.ico" width="20x"></span> Contas a Receber</a></li><?php } ?>
        
       
          <?php if($podeLancaDespesas) { ?><li class="nav-item"><a class="nav-link" href="?router=LancaDespesas/<?php echo $menu27 ?>"><span class="nav-icon"><img src="config/img/despesas.ico" width="20x"></span> Lançamento Despesas</a></li><?php } ?>
        
           
          </ul>
        </li>
        <?php } ?>
        <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
        <span class="nav-icon">  <img src="config/img/mov5.ico" width="20px"></span> Movimentação</a>
          <ul class="nav-group-items">
        
            <li class="nav-item"><a class="nav-link" href="?router=Mov/<?php echo $menu18 ?>"><span class="nav-icon"><img src="config/img/mov6.ico" width="20px"></span>Movimentação</a></li>
                       
            <li class="nav-item"><a class="nav-link" href="?router=Caixa/<?php echo $menu15 ?>"><span class="nav-icon"><img src="config/img/caixa2.ico" width="20px"></span> Caixa</a></li>
         
         
            <li class="nav-item"><a class="nav-link" href="?router=Saldo/<?php echo $menu19 ?>"><span class="nav-icon"><img src="config/img/contas3.ico" width="20px"></span> Controle de contas</a></li>
            
           
            <li class="nav-item"><a class="nav-link" href="?router=ControleCaixa/<?php echo $menu23 ?>"><span class="nav-icon"><img src="config/img/controlecaixa.ico" width="20px"></span> Controle de Caixa</a></li>
            
           
            <li class="nav-item"><a class="nav-link" href="?router=Fluxo/<?php echo $menu25 ?>"><span class="nav-icon"><img src="config/img/fluxo.ico" width="20px"></span>Fluxo de conta</a></li>
            
           
            <li class="nav-item"><a class="nav-link" href="?router=Conci/<?php echo $menu24 ?>"><span class="nav-icon"><img src="config/img/conci.ico" width="20px"></span> Conciliação </a></li>
            
            <li class="nav-item"><a class="nav-link" href="?router=Diario/<?php echo $menu28 ?>"><span class="nav-icon"><img src="config/img/money.ico" width="20px"></span>Fluxo de Caixa Diario </a></li>
       

          </ul>
        </li>
        
        
        <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
        <span class="nav-icon">  <img src="config/img/vendas4.png" width="20px"></span> Vendas</a>
          <ul class="nav-group-items">
         
          <li class="nav-item"><a class="nav-link" href="?router=Vendas/<?php echo $menu22 ?>"><span class="nav-icon"><img src="config/img/vendas3.ico" width="20px"></span>Vendas</a></li>
                   
                 
          </ul>
        </li>

        <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
         <span class="nav-icon"><img src="config/img/report.ico" width="20px"></span>Relatórios </a>
          <ul class="nav-group-items">
         
          <li class="nav-item"><a class="nav-link" href="?router=Prod/<?php echo $menu20 ?>" target="_blank"><span class="nav-icon"><img src="config/img/relatorio.ico" width="20px"></span> Relatório Produtos </a></li>
          
 
          <li class="nav-item"><a class="nav-link" href="?router=Saldo/<?php echo $menu21 ?>" target="_blank"><span class="nav-icon"><img src="config/img/relatorio.ico" width="20px"></span> Relatório Saldo </a></li>
           

            
           
          </ul>
        </li>

        <?php if($mostrarConfiguracao) { ?>
        <!-- Grupo de configuracao: renderiza somente os submenus liberados para o usuario. -->
        <li class="nav-group menu-configuracao"><a class="nav-link nav-group-toggle" href="#">
         <span class="nav-icon"><i class="bi bi-sliders"></i></span>Configuração </a>
          <ul class="nav-group-items">
        
          <?php if($podeUsuarios) { ?>
          <li class="nav-item"><a class="nav-link" href="?router=User/<?php echo $menu4 ?>"><span class="nav-icon"><i class="bi bi-person-badge"></i></span> Usuários</a></li>
          <?php } ?>
          
         
          <?php if($podeNiveis) { ?>
          <li class="nav-item"><a class="nav-link" href="?router=Adm/<?php echo $menu3 ?>"><span class="nav-icon"><img class = "text-danger" src="config/img/niveis1.ico" width="20px"></span> Níveis de Usuários</a></li>
          <?php } ?>
          
         
            
          <?php if($podeAcessos) { ?>
          <li class="nav-item"><a class="nav-link" href="?router=Acessos/<?php echo $menu26 ?>"><span class="nav-icon"><i class="bi bi-shield-lock"></i></span> Acessos</a></li>
          <?php } ?>
          <?php if($podeConfigEmail) { ?>
          <!-- Configuracao de avisos: submenu SMTP criado para controlar envio de e-mail. -->
          <li class="nav-item"><a class="nav-link" href="?router=Configuracoes/email"><span class="nav-icon"><i class="bi bi-envelope"></i></span> Configuracao de E-mail</a></li>
          <?php } ?>

          <?php if($podeConfigWhatsapp) { ?>
          <!-- Configuracao de avisos: submenu WhatsApp criado para controlar API de mensagens. -->
          <li class="nav-item"><a class="nav-link" href="?router=Configuracoes/whatsapp"><span class="nav-icon"><i class="bi bi-chat-dots"></i></span> Configuracao de WhatsApp</a></li>
          <?php } ?>

          <?php if($podeLayoutEmails) { ?>
          <!-- Layouts de e-mail: submenu para editar modelos usados nos avisos financeiros. -->
          <li class="nav-item"><a class="nav-link" href="?router=Configuracoes/layoutEmails"><span class="nav-icon"><i class="bi bi-layout-text-window-reverse"></i></span> Layout de E-mails</a></li>
          <?php } ?>
          
          </ul>
        </li>
        <?php } ?>
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button> 
       

       
       
       
      
    </div>
    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
      <header class="header header-sticky mb-4">
        <div class="container-fluid">
          <button class="header-toggler px-md-0 me-md-3" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
            <svg class="icon icon-lg">
              <use xlink:href="config/vendors/@coreui/icons/svg/free.svg#cil-menu"></use>
            </svg>
           
          </button><a class="header-brand d-md-none" href="#">
        
              </a>
            <?php if($temAcessoPainel) { ?>
            <!-- Atalho de cabecalho: garante retorno ao homePainel mesmo com menu lateral recolhido. -->
            <a class="btn btn-sm btn-outline-primary me-3" href="?router=site/homePainel">
              <i class="bi bi-speedometer2"></i> Painel
            </a>
            <?php } ?>
            <!-- Marca responsiva: evita estouro horizontal no cabecalho em telas menores. -->
            <!-- Identidade visual 1.1: marca do cabecalho usa o mesmo logo profissional do menu. -->
            <div class="text-dark text-center app-header-brand"><h1><img src="config/img/logo-sistema-financeiro-v2.png" 
            alt="Sistema Financeiro"></h1></div>
         
         
          <ul class="header-nav ms-3">
            <li class="nav-item dropdown"><a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <?php echo @$nome_usuario;?>  <div class="avatar avatar-md"><img class="avatar-img" src="config/img/user.jpg" alt="Usuario"></div>
              </a>
              <div class="dropdown-menu dropdown-menu-end pt-0">
                <div class="dropdown-header bg-light py-2">
                  
                 
                <div class="dropdown-header bg-light py-2">
                  <div class="fw-semibold">Ferramentas</div>
                </div><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalPerfil" href="?router=Site/home/&listar=<?php echo $_SESSION['id']?>" >
                  <svg class="icon me-2 ">
                   </svg> 
                   <i class="bi bi-pen"><span> Editar Dados</span></a></i>
                     <a class="dropdown-item" href="?router=Site/logout">
                     <svg class="icon me-2">
                     </svg><i><span><img src="config/img/sair.ico" 
                     width="20px"> Sair</span></a></i>
              </div>
            </li>
          </ul>
          
             
      
       
          <!-- /.row-->
          
           
            
         
          
            <!-- /.col-->
</div>



      </header>
            <!-- /.col-->
            
            <!-- /.col-->
          
    <!-- Area principal padronizada para dar respiro e consistencia entre todas as telas internas. -->
    <main class="app-main container-fluid">







    
    
