<?php
use app\controllers\Vendas;

$con = new Vendas;
$pdo = $con->conectar();

// Dados carregados uma vez para montar selects do PDV sem consultas repetidas.
$clientes = $pdo->query("SELECT id, nome FROM clientes ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
$produtos = $pdo->query("SELECT id, nome FROM produtos ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- PDV redesenhado: formulario e carrinho lado a lado para acelerar a venda. -->
<div class="sales-grid">
	<form id="frmVendasProdutos" class="sales-panel sales-form-panel">
		<div class="sales-panel-header">
			<div>
				<h3>Itens da venda</h3>
				<span>Selecione cliente, produto e quantidade.</span>
			</div>
			<i class="bi bi-bag-check"></i>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="mb-3">
					<label class="form-label">Cliente</label>
					<select class="form-select input-sm" id="clienteVenda" name="clienteVenda">
						<option value="0">Consumidor sem cadastro</option>
						<?php foreach ($clientes as $cliente) { ?>
							<option value="<?php echo $cliente['id'] ?>"><?php echo htmlspecialchars($cliente['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="col-md-6">
				<div class="mb-3">
					<label class="form-label">Produto</label>
					<select class="form-select input-sm" id="produtoVenda" name="produtoVenda">
						<option value="">Selecionar produto</option>
						<?php foreach ($produtos as $produto) { ?>
							<option value="<?php echo $produto['id'] ?>"><?php echo htmlspecialchars($produto['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="mb-3">
					<label class="form-label">Descricao</label>
					<textarea readonly id="descricaoV" name="descricaoV" class="form-control input-sm" rows="2"></textarea>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-4">
				<div class="mb-3">
					<label class="form-label">Estoque</label>
					<input readonly type="number" class="form-control input-sm" id="quantidadeV" name="quantidadeV">
				</div>
			</div>

			<div class="col-md-4">
				<div class="mb-3">
					<label class="form-label">Preco</label>
					<input readonly type="text" class="form-control input-sm" id="precoV" name="precoV">
				</div>
			</div>

			<div class="col-md-4">
				<div class="mb-3">
					<label class="form-label">Quantidade</label>
					<input type="number" min="1" step="1" class="form-control input-sm" id="quantV" name="quantV" value="1">
				</div>
			</div>
		</div>

		<div class="sales-product-preview" id="produtoResumo">
			<span class="badge bg-secondary">Produto nao selecionado</span>
			<small>Escolha um produto para ver estoque e preco.</small>
		</div>

		<div class="sales-form-actions">
			<button type="button" class="btn btn-primary btn-sm" id="btnAddVenda">
				<i class="bi bi-cart-plus"></i> Adicionar
			</button>
			<button type="button" class="btn btn-outline-danger btn-sm" id="btnLimparVendas">
				<i class="bi bi-cart-x"></i> Limpar venda
			</button>
		</div>
	</form>

	<div class="sales-panel sales-cart-panel">
		<div id="tabelaVendasTempLoad"></div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#tabelaVendasTempLoad').load("?router=Vendas/tabelaVendasTemp");

		$('#produtoVenda').change(function(){
			const idProduto = $('#produtoVenda').val();
			if(!idProduto){
				limparProdutoSelecionado();
				return;
			}

			$.ajax({
				type:"POST",
				data:{idproduto: idProduto},
				url:"?router=Vendas/obterProduto",
				dataType:"json",
				success:function(r){
					// Produto selecionado: preenche campos somente com retorno JSON valido.
					const dado = r || {};
					$('#descricaoV').val(dado.descricao || '');
					$('#quantidadeV').val(dado.estoque || 0);
					$('#precoV').val(dado.valor_venda || 0);
					atualizarResumoProduto(dado);
				},
				error:function(){
					// Falha visivel: evita deixar estoque vazio parecendo estoque zerado.
					limparProdutoSelecionado();
					alertify.error("Nao foi possivel carregar o estoque do produto.");
				}
			});
		});

		$('#btnAddVenda').click(function(){
			const produto = $('#produtoVenda').val();
			const estoque = parseInt($('#quantidadeV').val() || 0, 10);
			const quantidade = parseInt($('#quantV').val() || 0, 10);

			// Validacao de tela: evita enviar item vazio, zerado ou acima do estoque.
			if(!produto){
				alertify.alert("Selecione um produto.");
				return false;
			}

			if(quantidade <= 0){
				alertify.alert("Informe uma quantidade maior que zero.");
				return false;
			}

			if(estoque <= 0){
				alertify.alert("Produto sem estoque disponivel no sistema.");
				return false;
			}

			if(quantidade > estoque){
				alertify.alert("Quantidade inexistente em estoque.");
				$('#quantV').val(1);
				return false;
			}

			$.ajax({
				type:"POST",
				data:$('#frmVendasProdutos').serialize(),
				url:"?router=Vendas/adicionarProdutoTemp",
				success:function(r){
					if($.trim(r) === '1'){
						$('#tabelaVendasTempLoad').load("?router=Vendas/tabelaVendasTemp");
						$('#quantV').val(1);
						alertify.success("Produto adicionado.");
					}else{
						alertify.error("Nao foi possivel adicionar o produto.");
					}
				}
			});
		});

		$('#btnLimparVendas').click(function(){
			$.ajax({
				url:"?router=Vendas/limparTemp",
				success:function(){
					$('#tabelaVendasTempLoad').load("?router=Vendas/tabelaVendasTemp");
					alertify.success("Venda limpa.");
				}
			});
		});

		$('#clienteVenda').select2({ width: '100%' });
		$('#produtoVenda').select2({ width: '100%' });
	});

	function limparProdutoSelecionado(){
		$('#descricaoV').val('');
		$('#quantidadeV').val('');
		$('#precoV').val('');
		$('#produtoResumo').html('<span class="badge bg-secondary">Produto nao selecionado</span><small>Escolha um produto para ver estoque e preco.</small>');
	}

	function atualizarResumoProduto(dado){
		const estoque = parseInt(dado.estoque || 0, 10);
		const classe = estoque <= 0 ? 'bg-danger' : (estoque <= 5 ? 'bg-warning text-dark' : 'bg-success');
		const texto = estoque <= 0 ? 'Sem estoque' : (estoque <= 5 ? 'Baixo estoque' : 'Em estoque');
		$('#produtoResumo').html('<span class="badge ' + classe + '">' + texto + '</span><small>Disponivel: ' + estoque + ' unidade(s)</small>');
	}

	function fecharP(index){
		$.ajax({
			type:"POST",
			data:{ind: index},
			url:"?router=Vendas/fecharProduto",
			success:function(r){
				$('#tabelaVendasTempLoad').load("?router=Vendas/tabelaVendasTemp");
				if($.trim(r) === '1'){
					alertify.success("Produto removido.");
				}else{
					alertify.error("Nao foi possivel remover o produto.");
				}
			}
		});
	}

	function criarVenda(){
		$.ajax({
			url:"?router=Vendas/criarVenda",
			success:function(r){
				const retorno = $.trim(r);
				if(retorno.indexOf('OK|') === 0){
					const idVenda = retorno.split('|')[1];
					$('#tabelaVendasTempLoad').load("?router=Vendas/tabelaVendasTemp");
					$('#frmVendasProdutos')[0].reset();
					limparProdutoSelecionado();
					alertify.success("Venda criada com sucesso.");
					// Cupom nao fiscal: abre o comprovante da venda recem-finalizada.
					window.open('?router=Vendas/cupomVenda/' + idVenda, '_blank');
				}else if(retorno === '0'){
					alertify.alert("Nao possui itens na venda.");
				}else{
					alertify.error(r || "Venda nao efetuada.");
				}
			}
		});
	}
</script>
