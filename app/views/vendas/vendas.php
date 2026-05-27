<?php
$pagina = '?router=Vendas';
?>

<!-- Tela de vendas profissional: usa o layout principal existente sem recriar html/body. -->
<section class="sales-shell">
	<div class="sales-header">
		<div>
			<h2>Venda de Produtos</h2>
			<p>Monte o carrinho, revise os itens e finalize a venda com baixa de estoque.</p>
		</div>
		<div class="sales-actions" role="group" aria-label="Alternar tela de vendas">
			<button type="button" class="btn btn-primary btn-sm" id="vendaProdutosBtn">
				<i class="bi bi-cart-plus"></i> Nova Venda
			</button>
			<button type="button" class="btn btn-outline-secondary btn-sm" id="vendasFeitasBtn">
				<i class="bi bi-receipt"></i> Historico
			</button>
		</div>
	</div>

	<div class="sales-content">
		<div id="vendaProdutos"></div>
		<div id="vendasFeitas" class="d-none"></div>
	</div>
</section>

<script type="text/javascript">
	$(document).ready(function(){
		// Carregamento inicial: abre direto na venda para reduzir cliques no atendimento.
		abrirNovaVenda();

		$('#vendaProdutosBtn').click(function(){
			abrirNovaVenda();
		});

		$('#vendasFeitasBtn').click(function(){
			abrirHistoricoVendas();
		});
	});

	function abrirNovaVenda(){
		$('#vendasFeitas').addClass('d-none').empty();
		$('#vendaProdutos').removeClass('d-none').load('<?=$pagina?>/vendasprodutos');
		$('#vendaProdutosBtn').removeClass('btn-outline-secondary').addClass('btn-primary');
		$('#vendasFeitasBtn').removeClass('btn-primary').addClass('btn-outline-secondary');
	}

	function abrirHistoricoVendas(){
		// Historico via roteador corrige o caminho antigo que apontava para arquivo inexistente.
		$('#vendaProdutos').addClass('d-none');
		$('#vendasFeitas').removeClass('d-none').load('<?=$pagina?>/listarVendas');
		$('#vendasFeitasBtn').removeClass('btn-outline-secondary').addClass('btn-primary');
		$('#vendaProdutosBtn').removeClass('btn-primary').addClass('btn-outline-secondary');
	}
</script>
