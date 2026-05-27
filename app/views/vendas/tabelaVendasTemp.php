<?php
	// Sessao segura: no AJAX inicia normalmente; em validacoes com saida previa evita warning.
	if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
		session_start();
	}

	$itens = $_SESSION['tabelaComprasTemp'] ?? [];
	$total = 0;
	$cliente = 'Consumidor';

	// Compatibilidade: aceita carrinho novo em array e carrinho antigo em string.
	function normalizarItemVendaTemp($item) {
		if (is_array($item)) {
			return $item;
		}

		$d = explode("||", (string) $item);
		return [
			'id_produto' => (int) ($d[0] ?? 0),
			'nome_produto' => $d[1] ?? '',
			'descricao' => $d[2] ?? '',
			'preco' => (float) ($d[3] ?? 0),
			'nome_cliente' => $d[4] ?? 'Consumidor',
			'estoque' => (int) ($d[5] ?? 0),
			'quantidade_vendida' => (int) ($d[6] ?? 0),
			'total' => (float) ($d[7] ?? 0),
			'id_cliente' => (int) ($d[8] ?? 0)
		];
	}
?>

<!-- Carrinho redesenhado: total e acoes ficam claros antes de finalizar a venda. -->
<div class="sales-cart-header">
	<div>
		<h3><i class="bi bi-cart-check"></i> Carrinho</h3>
		<span id="nomeclienteVenda">Cliente: <?php echo $cliente; ?></span>
	</div>
</div>

<?php if(count($itens) === 0) { ?>
	<div class="sales-empty-cart">
		<i class="bi bi-cart"></i>
		<strong>Nenhum produto adicionado</strong>
		<span>Selecione um produto e clique em adicionar para iniciar a venda.</span>
	</div>
<?php } else { ?>
	<div class="table-responsive">
		<table class="table table-hover sales-cart-table">
			<thead>
				<tr>
					<th>Produto</th>
					<th>Preco</th>
					<th>Qtd</th>
					<th>Subtotal</th>
					<th>Remover</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($itens as $i => $item) {
					$item = normalizarItemVendaTemp($item);
					$subtotal = (float) $item['preco'] * (int) $item['quantidade_vendida'];
					$total += $subtotal;
					$cliente = $item['nome_cliente'] ?: 'Consumidor';
				?>
				<tr>
					<td>
						<strong><?php echo htmlspecialchars($item['nome_produto'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></strong>
						<small class="d-block text-muted"><?php echo htmlspecialchars($item['descricao'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></small>
					</td>
					<td>R$ <?php echo number_format((float) $item['preco'], 2, ',', '.'); ?></td>
					<td><?php echo (int) $item['quantidade_vendida']; ?></td>
					<td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
					<td>
						<button type="button" class="btn btn-outline-danger btn-sm" onclick="fecharP('<?php echo $i; ?>')" title="Remover produto">
							<i class="bi bi-trash"></i>
						</button>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>

	<div class="sales-total-bar">
		<div>
			<span>Total da venda</span>
			<strong>R$ <?php echo number_format($total, 2, ',', '.'); ?></strong>
		</div>
		<button type="button" class="btn btn-success" onclick="criarVenda()">
			<i class="bi bi-check2-circle"></i> Finalizar Venda
		</button>
	</div>
<?php } ?>

<script type="text/javascript">
	$(document).ready(function(){
		// Atualiza o nome do cliente depois que a tabela foi renderizada pelo AJAX.
		$('#nomeclienteVenda').text("Cliente: <?php echo htmlspecialchars($cliente, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>");
	});
</script>
