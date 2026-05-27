<?php

use app\models\PainelOperacional;
use app\models\Permissoes;

if (!function_exists('h')) {
	function h($valor)
	{
		return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
	}
}

$painel = new PainelOperacional();
$indicadores = $painel->indicadores($_SESSION['id'] ?? 0);
$perfilOperacional = Permissoes::normalizarNivel($_SESSION['nivel'] ?? '');
$temAcessoPainel = in_array($perfilOperacional, ['Administrador', 'Financeiro'], true);

?>

<!-- Home operacional: usuarios sem perfil financeiro nao visualizam saldos, contas ou valores em dinheiro. -->
<div class="container-fluid app-page vendas-page my-3">
	<div class="vendas-hero mb-4">
		<div>
			<span class="eyebrow">Painel operacional</span>
			<h1>Bem-vindo, <?php echo h($nome_usuario ?? 'Usuario'); ?></h1>
			<p>Acompanhe suas atividades do dia sem expor informacoes financeiras sensiveis.</p>
		</div>
		<div class="vendas-hero-actions">
			<?php if ($temAcessoPainel) { ?>
			<!-- Retorno ao painel financeiro: administrador/financeiro nao ficam presos na home operacional. -->
			<a class="btn btn-light" href="?router=site/homePainel">
				<i class="bi bi-speedometer2"></i> Painel
			</a>
			<?php } ?>
			<a class="btn btn-light" href="?router=Vendas/vendas">
				<i class="bi bi-cart-check"></i> Nova venda
			</a>
			<a class="btn btn-outline-light" href="?router=Clientes/clientes">
				<i class="bi bi-person-plus"></i> Cliente
			</a>
		</div>
	</div>

	<div class="row g-3 mb-4">
		<div class="col-sm-6 col-xl-3">
			<div class="app-kpi-card">
				<span class="app-kpi-icon text-success"><i class="bi bi-receipt"></i></span>
				<small>Minhas vendas hoje</small>
				<strong><?php echo (int) $indicadores['vendasHoje']; ?></strong>
			</div>
		</div>
		<div class="col-sm-6 col-xl-3">
			<div class="app-kpi-card">
				<span class="app-kpi-icon text-primary"><i class="bi bi-people"></i></span>
				<small>Clientes ativos</small>
				<strong><?php echo (int) $indicadores['clientesAtivos']; ?></strong>
			</div>
		</div>
		<div class="col-sm-6 col-xl-3">
			<div class="app-kpi-card">
				<span class="app-kpi-icon text-warning"><i class="bi bi-box-seam"></i></span>
				<small>Produtos ativos</small>
				<strong><?php echo (int) $indicadores['produtosAtivos']; ?></strong>
			</div>
		</div>
		<div class="col-sm-6 col-xl-3">
			<div class="app-kpi-card">
				<span class="app-kpi-icon text-danger"><i class="bi bi-exclamation-triangle"></i></span>
				<small>Estoque baixo</small>
				<strong><?php echo (int) $indicadores['produtosBaixoEstoque']; ?></strong>
			</div>
		</div>
	</div>

	<div class="row g-3">
		<div class="col-lg-7">
			<section class="app-panel">
				<div class="app-panel-header">
					<div>
						<span class="eyebrow">Atividade</span>
						<h2>Ultimas vendas</h2>
					</div>
					<a class="btn btn-sm btn-outline-primary" href="?router=Vendas/vendas">Abrir PDV</a>
				</div>
				<div class="table-responsive">
					<table class="table table-hover align-middle mb-0">
						<thead>
							<tr>
								<th>Cupom</th>
								<th>Cliente</th>
								<th>Itens</th>
								<th>Data</th>
							</tr>
						</thead>
						<tbody>
							<?php if (count($indicadores['ultimasVendas']) === 0) { ?>
								<tr>
									<td colspan="4" class="text-muted">Nenhuma venda recente para exibir.</td>
								</tr>
							<?php } ?>
							<?php foreach ($indicadores['ultimasVendas'] as $venda) { ?>
								<tr>
									<td>#<?php echo (int) $venda['id_venda']; ?></td>
									<td><?php echo h($venda['cliente']); ?></td>
									<td><?php echo (int) $venda['quantidade']; ?></td>
									<td><?php echo h(implode('/', array_reverse(explode('-', $venda['dataCompra'])))); ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</section>
		</div>

		<div class="col-lg-5">
			<section class="app-panel">
				<div class="app-panel-header">
					<div>
						<span class="eyebrow">Avisos</span>
						<h2>Produtos com estoque baixo</h2>
					</div>
					<a class="btn btn-sm btn-outline-primary" href="?router=Prod/produtos/&estoque=sim">Ver estoque</a>
				</div>

				<?php if (count($indicadores['estoqueBaixo']) === 0) { ?>
					<p class="text-muted mb-0">Nenhum produto abaixo do limite operacional.</p>
				<?php } ?>

				<div class="list-group list-group-flush">
					<?php foreach ($indicadores['estoqueBaixo'] as $produto) { ?>
						<div class="list-group-item d-flex justify-content-between align-items-center px-0">
							<span><?php echo h($produto['nome']); ?></span>
							<span class="badge bg-danger"><?php echo (int) $produto['estoque']; ?> un.</span>
						</div>
					<?php } ?>
				</div>
			</section>

			<section class="app-panel mt-3">
				<span class="eyebrow">Perfil atual</span>
				<h2><?php echo h($perfilOperacional); ?></h2>
				<p class="text-muted mb-0"><?php echo h(Permissoes::descricaoPerfil($perfilOperacional)); ?></p>
			</section>
		</div>
	</div>
</div>
