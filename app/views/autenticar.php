<?php
use app\models\CrudUsuarios;
use app\models\Permissoes;

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_cache_expire(120);
    session_set_cookie_params(0, '', '', !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', true);
    session_start();
}

$login = new CrudUsuarios;
$dados = $login->logar();

if (count($dados) > 0) {
    session_regenerate_id(true);

    $_SESSION['id'] = $dados[0]['id'];
    $_SESSION['nome_usu'] = $dados[0]['nome_usu'];
    $_SESSION['email'] = $dados[0]['email'];
    // Perfil normalizado no login: aliases antigos ja entram com a permissao correta.
    $_SESSION['nivel'] = Permissoes::normalizarNivel($dados[0]['nivel']);

    // Administrador principal: garante menu e rotas completas para o e-mail do proprietario.
    if (Permissoes::emailAdministradorTotal($dados[0]['email'])) {
        $_SESSION['nivel'] = 'Administrador';
    }

    if ($_SESSION['nivel'] === 'Administrador') {
        header('Location: ?router=Site/homePainel');
        exit();
    }

    if ($_SESSION['nivel'] === 'Financeiro') {
        // Perfil Financeiro: direciona login para o painel financeiro separado do administrativo.
        header('Location: ?router=Site/painelFinanceiro');
        exit();
    }

    header('Location: ?router=Site/home');
    exit();
}

echo "<script language='javascript'>window.alert('Dados Incorretos!'); window.location='?router=Site/login';</script>";
exit();
