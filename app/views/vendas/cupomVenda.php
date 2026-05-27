<?php
use app\models\CrudVendas;

$idVenda = $idVenda ?? null;
$model = new CrudVendas;
$cupom = $model->obterCupomVenda($idVenda);
$cabecalho = $cupom['cabecalho'];
$itens = $cupom['itens'];

$nomeSistema = 'Sistema Financeiro';
$data = $cabecalho && $cabecalho['data'] ? implode('/', array_reverse(explode('-', $cabecalho['data']))) : '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cupom nao fiscal</title>
	<style>
		/* Cupom nao fiscal: layout termico simples para impressao em 80mm ou A4. */
		:root {
			--cupom-texto: #111827;
			--cupom-muted: #6b7280;
			--cupom-border: #d1d5db;
		}

		body {
			margin: 0;
			background: #f3f4f6;
			color: var(--cupom-texto);
			font-family: Arial, Helvetica, sans-serif;
			font-size: 13px;
		}

		.cupom-page {
			width: 320px;
			margin: 24px auto;
			padding: 18px;
			background: #ffffff;
			border: 1px solid var(--cupom-border);
			box-shadow: 0 18px 45px rgba(15, 23, 42, 0.14);
		}

		.cupom-center {
			text-align: center;
		}

		.cupom-title {
			margin: 0 0 4px;
			font-size: 17px;
			font-weight: 800;
			text-transform: uppercase;
		}

		.cupom-subtitle {
			margin: 0;
			color: var(--cupom-muted);
			font-size: 11px;
			font-weight: 700;
			text-transform: uppercase;
		}

		.cupom-alert {
			margin: 12px 0;
			padding: 8px;
			border: 1px dashed #9ca3af;
			font-size: 11px;
			font-weight: 700;
			text-align: center;
			text-transform: uppercase;
		}

		.cupom-line {
			margin: 10px 0;
			border-top: 1px dashed var(--cupom-border);
		}

		.cupom-info {
			display: grid;
			gap: 3px;
			margin-bottom: 8px;
		}

		.cupom-info span {
			display: flex;
			justify-content: space-between;
			gap: 10px;
		}

		.cupom-info strong {
			white-space: nowrap;
		}

		.cupom-table {
			width: 100%;
			border-collapse: collapse;
		}

		.cupom-table th,
		.cupom-table td {
			padding: 5px 0;
			border-bottom: 1px dashed #e5e7eb;
			vertical-align: top;
		}

		.cupom-table th {
			color: var(--cupom-muted);
			font-size: 10px;
			text-align: left;
			text-transform: uppercase;
		}

		.cupom-table .right {
			text-align: right;
		}

		.cupom-produto {
			font-weight: 700;
		}

		.cupom-total {
			display: flex;
			align-items: center;
			justify-content: space-between;
			margin-top: 12px;
			font-size: 17px;
			font-weight: 800;
		}

		.cupom-footer {
			margin-top: 14px;
			color: var(--cupom-muted);
			font-size: 11px;
			text-align: center;
		}

		.cupom-actions {
			display: flex;
			justify-content: center;
			gap: 8px;
			margin: 18px auto 0;
		}

		.cupom-actions button {
			padding: 8px 12px;
			border: 0;
			border-radius: 6px;
			background: #0f766e;
			color: #ffffff;
			cursor: pointer;
			font-weight: 700;
		}

		.cupom-actions button.secondary {
			background: #475569;
		}

		@media print {
			body {
				background: #ffffff;
			}

			.cupom-page {
				width: 80mm;
				margin: 0;
				padding: 0;
				border: 0;
				box-shadow: none;
			}

			.cupom-actions {
				display: none;
			}
		}
	</style>
</head>
<body>
	<div class="cupom-page">
		<?php if(!$cabecalho) { ?>
			<div class="cupom-center">
				<h1 class="cupom-title">Venda nao encontrada</h1>
				<p class="cupom-subtitle">Verifique o numero da venda.</p>
			</div>
		<?php } else { ?>
			<div class="cupom-center">
				<h1 class="cupom-title"><?php echo htmlspecialchars($nomeSistema, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></h1>
				<p class="cupom-subtitle">Cupom nao fiscal</p>
			</div>

			<div class="cupom-alert">Nao e documento fiscal</div>

			<div class="cupom-info">
				<span><strong>Venda:</strong> #<?php echo (int) $cabecalho['id_venda']; ?></span>
				<span><strong>Data:</strong> <?php echo $data; ?></span>
				<span><strong>Cliente:</strong> <?php echo htmlspecialchars($cabecalho['cliente'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></span>
				<?php if(!empty($cabecalho['documento'])) { ?>
					<span><strong>Documento:</strong> <?php echo htmlspecialchars($cabecalho['documento'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></span>
				<?php } ?>
				<?php if(!empty($cabecalho['usuario'])) { ?>
					<span><strong>Operador:</strong> <?php echo htmlspecialchars($cabecalho['usuario'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></span>
				<?php } ?>
			</div>

			<div class="cupom-line"></div>

			<table class="cupom-table">
				<thead>
					<tr>
						<th>Item</th>
						<th class="right">Qtd</th>
						<th class="right">Total</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($itens as $item) { ?>
						<tr>
							<td>
								<div class="cupom-produto"><?php echo htmlspecialchars($item['produto'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></div>
								<small>R$ <?php echo number_format((float) $item['preco'], 2, ',', '.'); ?> un.</small>
							</td>
							<td class="right"><?php echo (int) $item['quantidade']; ?></td>
							<td class="right">R$ <?php echo number_format((float) $item['total_venda'], 2, ',', '.'); ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>

			<div class="cupom-total">
				<span>Total</span>
				<span>R$ <?php echo number_format((float) $cabecalho['total'], 2, ',', '.'); ?></span>
			</div>

			<div class="cupom-footer">
				Obrigado pela preferencia.<br>
				Este comprovante e para controle interno.
			</div>
		<?php } ?>
	</div>

	<div class="cupom-actions">
		<button type="button" onclick="window.print()">Imprimir</button>
		<button type="button" class="secondary" onclick="window.close()">Fechar</button>
	</div>
</body>
</html>
