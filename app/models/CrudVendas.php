<?php

namespace app\models;

class CrudVendas extends Connection
{
       
    
    public function obterDadosProduto($idproduto){
		
        $pagina = 'produtos';
        $idproduto = filter_var($idproduto, FILTER_VALIDATE_INT);
        
        $pdo = $this->connect(); 
		if(!$idproduto){
			return [
				'descricao' => '',
				'estoque' => 0,
				'valor_venda' => 0,
				'foto' => ''
			];
		}

		// Consulta segura: produto vem do AJAX e precisa ser validado antes de retornar dados da venda.
		$sql=  $pdo->prepare("SELECT 
		descricao,
		estoque,
	    valor_venda,
		foto
		from $pagina
		where id = :idproduto
		LIMIT 1");
		$sql->bindValue(':idproduto', $idproduto, \PDO::PARAM_INT);
		$sql->execute();

		$ver = $sql->fetchAll(\PDO::FETCH_ASSOC);
		if(!isset($ver[0])){
			return [
				'descricao' => '',
				'estoque' => 0,
				'valor_venda' => 0,
				'foto' => ''
			];
		}

             $dados=array(
			
			'descricao' => $ver[0]['descricao'],
			'estoque' => $ver[0]['estoque'],
			'valor_venda' => $ver[0]['valor_venda'],
			'foto' => $ver[0]['foto']
			
					);	
        
       
		return $dados;
	}

    public function conectar()
    {
    
        $pdo = $this->connect();
    
        return $pdo;
    }

	public function adcionarProd()
	{
	
	// Usa helper central para evitar iniciar sessao duplicada no carrinho temporario.
	$this->ensureSession();
	

	// Leitura robusta do POST: fallback evita bloquear venda quando filter_input nao recebe o payload.
	$idclienteRaw = filter_input(INPUT_POST, 'clienteVenda', FILTER_VALIDATE_INT);
	$idprodutoRaw = filter_input(INPUT_POST, 'produtoVenda', FILTER_VALIDATE_INT);
	$descricaoRaw = filter_input(INPUT_POST, 'descricaoV', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$quantVRaw = filter_input(INPUT_POST, 'quantV', FILTER_VALIDATE_INT);

	$idcliente = $idclienteRaw !== null && $idclienteRaw !== false ? (int) $idclienteRaw : (int) ($_POST['clienteVenda'] ?? 0);
	$idproduto = $idprodutoRaw !== null && $idprodutoRaw !== false ? (int) $idprodutoRaw : (int) ($_POST['produtoVenda'] ?? 0);
	$descricao = $descricaoRaw !== null ? $descricaoRaw : htmlspecialchars((string) ($_POST['descricaoV'] ?? ''), ENT_QUOTES, 'UTF-8');
	$quantV = $quantVRaw !== null && $quantVRaw !== false ? (int) $quantVRaw : (int) ($_POST['quantV'] ?? 0);

	$pdo = $this->connect();

	// Validacao de negocio: venda precisa de produto valido e quantidade positiva.
	if(!$idproduto || $quantV <= 0){
		return false;
	}

	$ncliente = 'Consumidor';
	if($idcliente && $idcliente > 0){
		// Consulta preparada: cliente vem do formulario da venda.
		$sql=$pdo->prepare("SELECT nome
				from clientes 
				where id = :id
				LIMIT 1");
		$sql->bindValue(':id', $idcliente, \PDO::PARAM_INT);
		$sql->execute();
		$result = $sql->fetchAll(\PDO::FETCH_ASSOC);
		$ncliente=$result[0]['nome'] ?? 'Consumidor';
	} else {
		$idcliente = 0;
	}

	// Consulta preparada: produto vem do formulario da venda.
	$sql= $pdo->prepare("SELECT nome, descricao, estoque, valor_venda
			from produtos
			where id = :id
			LIMIT 1");
	$sql->bindValue(':id', $idproduto, \PDO::PARAM_INT);
	$sql->execute();
	$result= $sql->fetch(\PDO::FETCH_ASSOC);

	if(!$result){
		return false;
	}

	$nomeproduto = $result['nome'];
	$estoque = (int) $result['estoque'];
	$preco = (float) $result['valor_venda'];
	$descricao = $descricao ?: $result['descricao'];

	if($quantV > $estoque){
		return false;
	}

	// Carrinho estruturado: evita erro por separador "||" e soma produto repetido em vez de duplicar linha.
	if(!isset($_SESSION['tabelaComprasTemp']) || !is_array($_SESSION['tabelaComprasTemp'])){
		$_SESSION['tabelaComprasTemp'] = [];
	}

	foreach($_SESSION['tabelaComprasTemp'] as $indice => $itemAtual){
		$itemAtual = $this->normalizarItemCarrinho($itemAtual);
		if((int) $itemAtual['id_produto'] === $idproduto){
			$novaQuantidade = (int) $itemAtual['quantidade_vendida'] + $quantV;
			if($novaQuantidade > $estoque){
				return false;
			}

			$itemAtual['quantidade_vendida'] = $novaQuantidade;
			$itemAtual['total'] = $novaQuantidade * $preco;
			$itemAtual['estoque'] = $estoque;
			$_SESSION['tabelaComprasTemp'][$indice] = $itemAtual;
			return $itemAtual;
		}
	}

	$produto = [
		'id_produto' => $idproduto,
		'nome_produto' => $nomeproduto,
		'descricao' => $descricao,
		'preco' => $preco,
		'nome_cliente' => $ncliente,
		'estoque' => $estoque,
		'quantidade_vendida' => $quantV,
		'total' => $quantV * $preco,
		'id_cliente' => $idcliente
	];

	$_SESSION['tabelaComprasTemp'][]=$produto;

/*
	//ATUALIZAÇÃO DO ESTOQUE - FEITO NO FINAL DO CURSO
	$quantNova = $quantidade - $quantV;
	$sqlU = "UPDATE produtos SET quantidade = '$quantNova' where id_produto = '$idproduto' ";
		mysqli_query($conexao,$sqlU);*/

		//var_dump($produto);
		return $produto;

	}

	public function criar(){

		//session_start();
		$pdo = $this->connect(); 
		
		$data=date('Y-m-d');
		$dados=$_SESSION['tabelaComprasTemp'];
		$idusuario=$_SESSION['id'];
		$r=0;

		// Normalizacao final: agrupa produto repetido no carrinho antes de baixar estoque.
		$itensAgrupados = [];
		foreach($dados as $itemCarrinho){
			$item = $this->normalizarItemCarrinho($itemCarrinho);
			$idProduto = (int) $item['id_produto'];

			if(!$idProduto || (int) $item['quantidade_vendida'] <= 0){
				return [
					'erro' => true,
					'mensagem' => 'Carrinho contem produto ou quantidade invalida.'
				];
			}

			if(!isset($itensAgrupados[$idProduto])){
				$itensAgrupados[$idProduto] = $item;
				continue;
			}

			$itensAgrupados[$idProduto]['quantidade_vendida'] += (int) $item['quantidade_vendida'];
			$itensAgrupados[$idProduto]['total'] = $itensAgrupados[$idProduto]['quantidade_vendida'] * (float) $itensAgrupados[$idProduto]['preco'];
		}

		$qtdItens = count($itensAgrupados);

		$pdo = $this->connect();
		// Transacao garante que venda e estoque sejam atualizados juntos.
		try {
		$pdo->beginTransaction();
		// Numero da venda gerado dentro da transacao para reduzir risco de duplicidade.
		$idvenda= self::criarComprovante($pdo);
		foreach ($itensAgrupados as $d) {
			// Conferencia com bloqueio: informa exatamente qual produto nao tem estoque suficiente.
			$consultaEstoque = $pdo->prepare("SELECT nome, estoque FROM produtos WHERE id = :id_produto LIMIT 1 FOR UPDATE");
			$consultaEstoque->bindValue(':id_produto', $d['id_produto'], \PDO::PARAM_INT);
			$consultaEstoque->execute();
			$produtoEstoque = $consultaEstoque->fetch(\PDO::FETCH_ASSOC);

			if(!$produtoEstoque || (int) $produtoEstoque['estoque'] < (int) $d['quantidade_vendida']){
				$pdo->rollBack();
				return [
					'erro' => true,
					'mensagem' => 'Estoque insuficiente para ' . ($produtoEstoque['nome'] ?? $d['nome_produto']) . '. Disponivel: ' . (int) ($produtoEstoque['estoque'] ?? 0) . ', solicitado: ' . (int) $d['quantidade_vendida'] . '.'
				];
			}

			$baixaEstoque = $pdo->prepare("UPDATE produtos SET estoque = estoque - :quantidade WHERE id = :id_produto");
			$baixaEstoque->bindValue(':quantidade', $d['quantidade_vendida'], \PDO::PARAM_INT);
			$baixaEstoque->bindValue(':id_produto', $d['id_produto'], \PDO::PARAM_INT);
			$baixaEstoque->execute();

			$sql= $pdo->prepare("INSERT into vendas (id_venda,
										id_cliente,
										id_produto,
										id_usuario,
										preco,
										quantidade,
										total_venda,
										dataCompra)
							values (:id_venda,
									:id_cliente,
									:id_produto,
									:id_usuario,
									:preco,
									:quantidade,
									:total_venda,
									:data_compra)");
			$sql->bindValue(':id_venda', $idvenda);
			$sql->bindValue(':id_cliente', $d['id_cliente']);
			$sql->bindValue(':id_produto', $d['id_produto']);
			$sql->bindValue(':id_usuario', $idusuario);
			$sql->bindValue(':preco', $d['preco']);
			$sql->bindValue(':quantidade', $d['quantidade_vendida']);
			$sql->bindValue(':total_venda', $d['total']);
			$sql->bindValue(':data_compra', $data);
			$sql->execute();
 $r = $itensAgrupados;

		

		}

		// Integracao financeira: toda venda finalizada gera uma conta a receber pendente.
		$totalVenda = 0;
		$idClienteVenda = 0;
		foreach($itensAgrupados as $itemReceber){
			$totalVenda += (float) $itemReceber['total'];
			if((int) $itemReceber['id_cliente'] > 0){
				$idClienteVenda = (int) $itemReceber['id_cliente'];
			}
		}

		$this->gerarContaReceberDaVenda($pdo, $idvenda, $idClienteVenda, $idusuario, $totalVenda, $data);

		$pdo->commit();
		} catch (\Throwable $erro) {
			if ($pdo->inTransaction()) {
				$pdo->rollBack();
			}
			error_log($erro->getMessage());
			return false;
		}
		// Retorno estruturado: permite ao frontend abrir o cupom da venda finalizada.
		return [
			'id_venda' => $idvenda,
			'itens' => $qtdItens
		];
	}

	public function criarComprovante($pdo = null){
		$pdo = $pdo ?: $this->connect();
		

		// Busca bloqueada quando estiver em transacao: evita dois usuarios pegarem o mesmo numero ao mesmo tempo.
		$sql=$pdo->query("SELECT id_venda from vendas order by id_venda desc LIMIT 1 FOR UPDATE");

		$resul= $sql->fetchAll(\PDO::FETCH_ASSOC);
		$id=$resul[0]['id_venda'] ?? 0;

		if($id=="" or $id==null or $id==0){
			return 1;
		}else{
			return $id + 1;
		}
	}

	public function listarVendas()
	{
		$pdo = $this->connect();

		// Historico da venda: agrupa itens pelo comprovante para exibir lista resumida no frontend.
		$sql = $pdo->prepare("SELECT V.id_venda,
				MIN(V.dataCompra) AS dataCompra,
				COALESCE(MIN(C.nome), 'Consumidor') AS cliente,
				SUM(V.quantidade) AS itens,
				SUM(V.total_venda) AS total_venda
			FROM vendas V
			LEFT JOIN clientes C ON C.id = V.id_cliente
			GROUP BY V.id_venda
			ORDER BY V.id_venda DESC
			LIMIT 100");
		$sql->execute();

		return $sql->fetchAll(\PDO::FETCH_ASSOC);
	}

	protected function gerarContaReceberDaVenda(\PDO $pdo, $idVenda, $idCliente, $idUsuario, $totalVenda, $dataVenda)
	{
		if($totalVenda <= 0){
			throw new \Exception('Total da venda invalido para gerar contas a receber.');
		}

		// Antiduplicidade: uma venda so pode gerar uma conta a receber.
		$stmtExiste = $pdo->prepare("SELECT id FROM contas_receber WHERE venda_id = :venda_id LIMIT 1");
		$stmtExiste->bindValue(':venda_id', $idVenda, \PDO::PARAM_INT);
		$stmtExiste->execute();
		if($stmtExiste->fetchColumn()){
			return;
		}

		$idsFinanceiros = $this->obterPlanoContaVenda($pdo);
		$idFormaPagamento = $this->obterFormaPagamentoPadraoVenda($pdo);

		// Lancamento automatico: fica pendente para baixa posterior em caixa/banco.
		$stmt = $pdo->prepare("INSERT INTO contas_receber
			SET descricao = :descricao,
				cliente = :cliente,
				entrada = :entrada,
				documento = :documento,
				plano_conta = :plano_conta,
				despesas = :despesas,
				data_emissao = :data_emissao,
				vencimento = :vencimento,
				frequencia = :frequencia,
				valor = :valor,
				usuario_lanc = :usuario_lanc,
				status = 'Pendente',
				juros = '0',
				multa = '0',
				desconto = '0',
				jurosporc = '0',
				multaporc = '0',
				descontoporc = '0',
				venda_id = :venda_id");
		$stmt->bindValue(':descricao', 'Venda #' . $idVenda);
		$stmt->bindValue(':cliente', $idCliente, \PDO::PARAM_INT);
		$stmt->bindValue(':entrada', 'Caixa');
		$stmt->bindValue(':documento', $idFormaPagamento, \PDO::PARAM_INT);
		$stmt->bindValue(':plano_conta', $idsFinanceiros['plano_conta'], \PDO::PARAM_INT);
		$stmt->bindValue(':despesas', $idsFinanceiros['categoria'], \PDO::PARAM_INT);
		$stmt->bindValue(':data_emissao', $dataVenda);
		$stmt->bindValue(':vencimento', $dataVenda);
		$stmt->bindValue(':frequencia', 'Uma Vez');
		$stmt->bindValue(':valor', $totalVenda);
		$stmt->bindValue(':usuario_lanc', $idUsuario, \PDO::PARAM_INT);
		$stmt->bindValue(':venda_id', $idVenda, \PDO::PARAM_INT);
		$stmt->execute();
	}

	protected function obterPlanoContaVenda(\PDO $pdo)
	{
		// Plano financeiro garantido: cria categoria/plano de vendas se ainda nao existir.
		$stmtCategoria = $pdo->prepare("SELECT id FROM cat_despesas WHERE nome = :nome LIMIT 1");
		$stmtCategoria->bindValue(':nome', 'Receitas operacionais');
		$stmtCategoria->execute();
		$idCategoria = $stmtCategoria->fetchColumn();

		if(!$idCategoria){
			$stmtCategoria = $pdo->prepare("INSERT INTO cat_despesas SET nome = :nome, grupo = :grupo");
			$stmtCategoria->bindValue(':nome', 'Receitas operacionais');
			$stmtCategoria->bindValue(':grupo', '7.0');
			$stmtCategoria->execute();
			$idCategoria = $pdo->lastInsertId();
		}

		$stmtPlano = $pdo->prepare("SELECT id FROM despesas WHERE nome_desp = :nome AND cat_despesa = :categoria LIMIT 1");
		$stmtPlano->bindValue(':nome', 'Vendas de Produtos');
		$stmtPlano->bindValue(':categoria', $idCategoria, \PDO::PARAM_INT);
		$stmtPlano->execute();
		$idPlano = $stmtPlano->fetchColumn();

		if(!$idPlano){
			$stmtPlano = $pdo->prepare("INSERT INTO despesas SET nome_desp = :nome, cat_despesa = :categoria, subgrupo = :subgrupo");
			$stmtPlano->bindValue(':nome', 'Vendas de Produtos');
			$stmtPlano->bindValue(':categoria', $idCategoria, \PDO::PARAM_INT);
			$stmtPlano->bindValue(':subgrupo', '7.0.1');
			$stmtPlano->execute();
			$idPlano = $pdo->lastInsertId();
		}

		return [
			'categoria' => (int) $idCategoria,
			'plano_conta' => (int) $idPlano
		];
	}

	protected function obterFormaPagamentoPadraoVenda(\PDO $pdo)
	{
		// Forma padrao para o recebivel gerado automaticamente; pode ser alterada na baixa.
		$stmt = $pdo->prepare("SELECT id FROM formas_pgtos WHERE nome_fpg = :nome LIMIT 1");
		$stmt->bindValue(':nome', 'Dinheiro');
		$stmt->execute();
		$idForma = $stmt->fetchColumn();

		if(!$idForma){
			$stmt = $pdo->prepare("INSERT INTO formas_pgtos SET nome_fpg = :nome");
			$stmt->bindValue(':nome', 'Dinheiro');
			$stmt->execute();
			$idForma = $pdo->lastInsertId();
		}

		return (int) $idForma;
	}

	public function obterCupomVenda($idVenda)
	{
		$idVenda = filter_var($idVenda, FILTER_VALIDATE_INT);
		if(!$idVenda){
			return [
				'cabecalho' => null,
				'itens' => []
			];
		}

		$pdo = $this->connect();

		// Cupom nao fiscal: consulta os itens da venda para impressao/reimpressao interna.
		$sql = $pdo->prepare("SELECT V.id_venda, V.dataCompra, V.preco, V.quantidade, V.total_venda,
				P.codigo, P.nome AS produto, P.descricao AS produto_descricao,
				COALESCE(C.nome, 'Consumidor') AS cliente, C.doc, C.telefone,
				U.nome_usu AS usuario
			FROM vendas V
			INNER JOIN produtos P ON P.id = V.id_produto
			LEFT JOIN clientes C ON C.id = V.id_cliente
			LEFT JOIN usuarios U ON U.id = V.id_usuario
			WHERE V.id_venda = :id_venda
			ORDER BY V.id_produto ASC");
		$sql->bindValue(':id_venda', $idVenda, \PDO::PARAM_INT);
		$sql->execute();
		$itens = $sql->fetchAll(\PDO::FETCH_ASSOC);

		if(count($itens) === 0){
			return [
				'cabecalho' => null,
				'itens' => []
			];
		}

		$total = 0;
		$quantidade = 0;
		foreach($itens as $item){
			$total += (float) $item['total_venda'];
			$quantidade += (int) $item['quantidade'];
		}

		return [
			'cabecalho' => [
				'id_venda' => $idVenda,
				'data' => $itens[0]['dataCompra'],
				'cliente' => $itens[0]['cliente'],
				'documento' => $itens[0]['doc'],
				'telefone' => $itens[0]['telefone'],
				'usuario' => $itens[0]['usuario'],
				'total' => $total,
				'quantidade' => $quantidade
			],
			'itens' => $itens
		];
	}

	protected function normalizarItemCarrinho($item)
	{
		if(is_array($item)){
			return $item;
		}

		// Compatibilidade: converte carrinhos antigos gravados como string separada por "||".
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


}
