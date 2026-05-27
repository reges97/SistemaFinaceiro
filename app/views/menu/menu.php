<?php


use app\models\CrudAcessos;
use app\models\CrudMenu;
use app\models\CrudSubMenu;
use app\models\CrudUsuarios;
use app\models\Permissoes;

@session_start();
@$id_usuario= $_SESSION['id'];
@$nivel = $_SESSION['nivel'];
// Permissao do menu legado: usa a mesma regra central do menu principal.
$nivel_permissao = Permissoes::normalizarNivel($nivel);
//RECUPERAR DADOS DO USUÁRIO
//$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
$recupera = new CrudUsuarios;
$res = $recupera->recuperaDados();

//$id_usuario = $res[0]['id'];
@$nome_usuario =  $res[0]['nome_usu'];
@$email_usuario = $res[0]['email'];
@$nivel_usuario = $res[0]['nivel'];
// Sincronizacao do menu legado: garante que o painel apareca para administrador/financeiro.
if (isset($res[0]['nivel'])) {
	$nivel_permissao = Permissoes::normalizarNivel($res[0]['nivel']);
	$_SESSION['nivel'] = $nivel_permissao;
}

if (Permissoes::emailAdministradorTotal($email_usuario ?? ($_SESSION['email'] ?? ''))) {
	$nivel_permissao = 'Administrador';
	$_SESSION['nivel'] = 'Administrador';
}

// Link inicial do menu legado: checagem direta evita administrador sem retorno ao homePainel.
$temAcessoPainel = in_array($nivel_permissao, ['Administrador', 'Financeiro'], true)
	|| Permissoes::emailAdministradorTotal($email_usuario ?? ($_SESSION['email'] ?? ''));
$menuInicioUrl = $temAcessoPainel ? '?router=site/homePainel' : '?router=Site/home';
$menuInicioTexto = $temAcessoPainel ? 'Painel' : 'Home';

$acesso = new CrudAcessos;
$res2 = $acesso->listarUsu();

$acesso_usuario = $res2[0]['nome_usu'];
$acesso_nivel = $res2[0]['nivel_aces'];
$acesso_menu = $res2[0]['menu'];
@$acesso_status = $res2[0]['acesso'];
@$acesso_ativo = $res2[0]['ativo'];

//var_dump($acesso_usuario);
//var_dump($acesso_nivel);
//var_dump($acesso_menu);
//var_dump(@$acesso_status);
//var_dump(@$acesso_ativo);



$menu1 = 'home';

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
	<!-- Ajuste visual global: carregado por ultimo para padronizar telas do menu legado. -->
	<link rel="stylesheet" type="text/css" href="config/css/style.css?v=20260526"/>
    
  </head>


  

    <!-- Layout profissional: remove margens laterais antigas que geravam rolagem horizontal. -->
    <body class="app-body" >
    <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
      <div class="sidebar-brand d-none d-md-flex">
        
        <a class="navbar-brand text-center" href="#"><img src="config/img/rascunhologo3.png" width="100px">
        </a>
      </div>

      
      
      <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="">
      
        <!-- Link inicial por permissao: Administrador/Financeiro acessam homePainel pelo item Painel. -->
        <li class="nav-item"><a class="nav-link" href="<?php echo $menuInicioUrl; ?>">
        <span class="nav-icon"><img src="config/img/home4.png" width="20px">
        </span> <?php echo $menuInicioTexto; ?> </a></li>
           
       
        <?php
        $lista = new CrudMenu;
				$dados = $lista->listaMenu();
				for ($i = 0; $i < count($dados); $i++) {
					foreach ($dados as $key => $value) {
					     
          
          }
          
					// Compatibilidade do menu legado: a tabela de submenu pode nao existir na base atual.
					$menu = $dados[$i]['menu'] ?? 'Menu';
          $submenu = $dados[$i]['nome_sub_menu'] ?? $menu;
          $submenu2 = $dados[$i]['nome_sub_menu'] ?? $menu;
          $id = $dados[$i]['id_menu'] ?? '';
         				
				?>


         <li class="nav-group"><a class="nav-link nav-group-toggle" href="#">
        <span class="nav-icon"><img src="config/img/cadastro2.png" width="20px">
        </span><?php echo $menu?> </a>
                    
        <?php }?>
       
    <ul class="nav-group-items">
  
            
            <li class="nav-item"><a class="nav-link" href="?router=Clientes/<?php echo $id ?>">
            <span class="nav-icon"><img src="config/img/clientes.ico" 
            width="20px">
           </span> <?php echo @$submenu?></a></li>
                
          </ul>
          <?php  ?>  
           
          
        </li>  
        
        
      </ul>
     

      
           
           
           
           
            
                   
          
          
     
           
        
        
        

        
        
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
            <div class="text-dark text-center"><h1><img src="config/img/rascunhologo11.png" 
            width="600px"></h1></div>
         
         
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
          
    <div calss ='container-fluid'>
  
