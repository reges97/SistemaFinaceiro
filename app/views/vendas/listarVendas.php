<?php
use app\models\CrudVendas;

// Historico usa o model diretamente para nao chamar novamente a action do controller.
$lista = new CrudVendas;
$vendas = $lista->listarVendas();
?>

<!-- Historico de vendas: substitui caminho antigo inexistente por listagem via MVC. -->
<div class="sales-panel">
	<div class="sales-panel-header">
		<div>
			<h3>Historico de vendas</h3>
			<span>Ultimas 100 vendas finalizadas.</span>
		</div>
		<i class="bi bi-receipt"></i>
	</div>

	<?php if(count($vendas) === 0) { ?>
		<div class="sales-empty-cart">
			<i class="bi bi-receipt"></i>
			<strong>Nenhuma venda encontrada</strong>
			<span>As vendas finalizadas aparecerao aqui.</span>
		</div>
	<?php } else { ?>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Venda</th>
						<th>Data</th>
						<th>Cliente</th>
						<th>Itens</th>
						<th>Total</th>
						<th>Cupom</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($vendas as $venda) {
						$data = $venda['dataCompra'] ? implode('/', array_reverse(explode('-', $venda['dataCompra']))) : '';
					?>
						<tr>
							<td>#<?php echo (int) $venda['id_venda']; ?></td>
							<td><?php echo $data; ?></td>
							<td><?php echo htmlspecialchars($venda['cliente'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></td>
							<td><?php echo (int) $venda['itens']; ?></td>
							<td><strong>R$ <?php echo number_format((float) $venda['total_venda'], 2, ',', '.'); ?></strong></td>
							<td>
								<!-- Reimpressao do cupom nao fiscal pelo historico de vendas. -->
								<a class="btn btn-outline-secondary btn-sm" target="_blank" href="?router=Vendas/cupomVenda/<?php echo (int) $venda['id_venda']; ?>" title="Imprimir cupom">
									<i class="bi bi-printer"></i>
								</a>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	<?php } ?>
</div>
