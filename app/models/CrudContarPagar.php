<?php

namespace app\models;

class CrudContarPagar extends Connection
{
    public function inserir()
    {
       
      @session_start();


      $pagina = 'contas_pagar';
      //VARIAVEIS DOS INPUTS
      $campo1 = 'descricao';
      $campo2 = 'Cliente';
      $campo3 = 'Saida';
      $campo4 = 'Documento';
      $campo5 = 'plano_conta';
      $campo6 = 'data_emissao';
      $campo7 = 'Vencimento';
      $campo8 = 'Frequencia';
      $campo9 = 'Valor';
	  $campo14 = 'Desconto';
	  $campo15 = 'Multa';
	  $campo16 = 'Juros';
      $campo17 = 'cat_despesas';
@$cp10 = @$_SESSION['id'];


$cp1 = filter_input(INPUT_POST, $campo1, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp2 = filter_input(INPUT_POST, $campo2, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp3 = filter_input(INPUT_POST, $campo3, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp4 = filter_input(INPUT_POST, $campo4, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp5 = filter_input(INPUT_POST, $campo5, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp6 = filter_input(INPUT_POST, $campo6, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp7 = filter_input(INPUT_POST, $campo7, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp8 = filter_input(INPUT_POST, $campo8, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp9 = filter_input(INPUT_POST, $campo9, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp14 = filter_input(INPUT_POST, $campo14, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp15 = filter_input(INPUT_POST, $campo15, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp16 = filter_input(INPUT_POST, $campo16, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp17 = filter_input(INPUT_POST, $campo17, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp9 = str_replace(',', '.', $cp9);
// Avisos financeiros: campos opcionais controlam vencimento, baixa e canal de notificacao.
$avisoVencimento = isset($_POST['aviso_vencimento']) ? 1 : 0;
$avisoBaixa = isset($_POST['aviso_baixa']) ? 1 : 0;
$avisoForma = in_array(($_POST['aviso_forma'] ?? 'email'), ['email', 'whatsapp', 'ambos'], true) ? $_POST['aviso_forma'] : 'email';
$avisoDias = max(0, (int) ($_POST['aviso_dias'] ?? 2));

//$cp5 = $cp17 .filter_input(INPUT_POST, 'cat_despesas', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$pdo = $this->connect();

//RECUPERAR O CAIXA QUE ESTÁ ABERTO (CASO TENHA ALGUM)
$query2 = $pdo->query("SELECT * FROM caixa WHERE status = 'Aberto'");
$res2 = $query2->fetchAll(\PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$cp12 = $res2[0]['id'];
}else{
	$cp12 = 0;
}

if($cp9 == ""){
	echo 'Preencha o Valor';
	exit();
}

$id = @$_POST['id'];


if($id == ""){

$query = $pdo->prepare("INSERT INTO $pagina set descricao = :campo1, cliente = :campo2, saida = :campo3, documento = :campo4, plano_conta = :campo5,
despesas = :campo17, data_emissao = :campo6, vencimento = :campo7, frequencia = :campo8, valor = :campo9, usuario_lanc = :campo10, status = 'Pendente',
data_recor = curDate(), juros = '0', multa = '0', desconto = '0',  jurosporc = :campo16, multaporc = :campo15, descontoporc = :campo14,
aviso_vencimento = :aviso_vencimento, aviso_baixa = :aviso_baixa, aviso_forma = :aviso_forma, aviso_dias = :aviso_dias");

	
}else{

	
		$query = $pdo->prepare("UPDATE $pagina set descricao = :campo1, cliente = :campo2, saida = :campo3, documento = :campo4, plano_conta = :campo5, 
		despesas = :campo17, data_emissao = :campo6, vencimento = :campo7, frequencia = :campo8, valor = :campo9, usuario_lanc = :campo10, 
		jurosporc = :campo16, multaporc = :campo15, descontoporc = :campo14,
		aviso_vencimento = :aviso_vencimento, aviso_baixa = :aviso_baixa, aviso_forma = :aviso_forma, aviso_dias = :aviso_dias WHERE id = '$id'");
				
}

$query->bindValue(":campo1", "$cp1");
$query->bindValue(":campo2", "$cp2");
$query->bindValue(":campo3", "$cp3");
$query->bindValue(":campo4", "$cp4");
$query->bindValue(":campo5", "$cp5");
$query->bindValue(":campo17", "$cp17");
$query->bindValue(":campo6", "$cp6");
$query->bindValue(":campo7", "$cp7");
$query->bindValue(":campo8", "$cp8");
$query->bindValue(":campo9", "$cp9");
$query->bindValue(":campo10", "$cp10");
$query->bindValue(":campo16", "$cp16");
$query->bindValue(":campo15", "$cp15");
$query->bindValue(":campo14", "$cp14");
$query->bindValue(":aviso_vencimento", $avisoVencimento, \PDO::PARAM_INT);
$query->bindValue(":aviso_baixa", $avisoBaixa, \PDO::PARAM_INT);
$query->bindValue(":aviso_forma", $avisoForma);
$query->bindValue(":aviso_dias", $avisoDias, \PDO::PARAM_INT);


$query->execute();


echo 'Salvo com Sucesso';

return $query;

    }

    public function excluir()
{
    @session_start();
    $nivel_usu = $_SESSION['nivel'];

    $pagina = 'contas_pagar';
    
    $id = filter_input(INPUT_POST, 'id-excluir', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $usuario_adm = filter_input(INPUT_POST, 'usuario_adm', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $senha_adm = filter_input(INPUT_POST, 'senha_adm', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    $pdo = $this->connect();



    // Valida senha administrativa pelo helper central, compatibilizando hash novo e legado.
    $senha_admin_valida = $this->validarSenhaAdministrador($usuario_adm, $senha_adm);
    
    if($senha_admin_valida || $nivel_usu == 'Administrador'){
    $pdo->query("DELETE from $pagina where id = '$id'");
    echo 'Excluído com Sucesso';
    }else{
        echo 'Dados Incorretos ou o usuário não é um Administrador!';
    }
     return $pdo;
}

public function fecharCaixa()
{
    @session_start();

    $pagina = 'contas_pagar';
 
    $id = filter_input(INPUT_POST, 'id-fechar', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id_usuario = filter_input(INPUT_POST, 'id-usuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $pdo = $this->connect();
    
    $pdo->query("UPDATE $pagina SET data_fec = curDate(), status = 'Fechado', usuario_fec = '$id_usuario' where id = '$id'");
    echo 'Fechado com Sucesso';
    return $pdo;

}

public function listaForn()
{
    $pagina = 'fornecedores';
    $pdo = $this->connect();
    $query = $pdo->query("SELECT * from $pagina where ativo = 'Sim' order by id desc ");
    $res = $query->fetchAll(\PDO::FETCH_ASSOC);
return $res;

}


public function listarDespe()
{

   
   
//VARIAVEIS DOS INPUTS

$campo5 = 'plano_conta';


$nome_cat = filter_input(INPUT_POST, 'cat', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$despesa =  filter_input(INPUT_POST, 'despesa', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$pdo = $this->connect();
$query = $pdo->query("SELECT * FROM cat_despesas where nome = '$nome_cat'");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
$id_cat = $res[0]['id'];

echo '<select class="form-select" aria-label="Default select example" name="'.$campo5.'" id="'.$campo5.'">';
$pdo = $this->conectar();
$query = $pdo->query("SELECT * FROM despesas where cat_despesa = '$id_cat' order by id asc");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){	}
		$id_item = $res[$i]['id'];
	$nome_item = $res[$i]['nome'];

	if($despesa == $nome_item){
		$selec = 'selected';
	}else{
		$selec = '';
	}
	
	echo '<option '.$selec.' value="'.$nome_item.'">'.$nome_item.'</option>';

} 

echo '</select>';

return $res;

}

public function listarPagar()

{

    $pagina = 'contas_pagar';
//VARIAVEIS DOS INPUTS


// Usa helper central para evitar iniciar sessao duplicada em rotinas AJAX.
$this->ensureSession();

$dataInicial = filter_input(INPUT_POST, 'dataInicial', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$dataFinal =  filter_input(INPUT_POST, 'dataFinal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$status = '%'.filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS).'%';
$alterou_data = filter_input(INPUT_POST, 'alterou_data', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$vencidas =  filter_input(INPUT_POST, 'vencidas', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$hoje =  filter_input(INPUT_POST, 'hoje', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$amanha = filter_input(INPUT_POST, 'amanha', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$data_hoje = date('Y-m-d');
$data_amanha = date('Y/m/d', strtotime("+1 days",strtotime($data_hoje)));
 //var_dump($dataInicial);
$pdo = $this->connect();

if($alterou_data == 'Sim'){
	if($dataInicial != "" || $dataFinal != ""){
	$query = $pdo->query("SELECT P.id, P.descricao, P.cliente, saida, 
	P.documento, P.plano_conta, P.despesas, 
	P.data_emissao, P.vencimento, 
	P.frequencia, P.valor, P.usuario_lanc, 
	P.usuario_baixa, P.status, P.data_recor, 
	P.juros, P.multa, P.desconto, P.subtotal, P.data_baixa, P.jurosporc,
    P.multaporc, P.descontoporc, P.aviso_vencimento, P.aviso_baixa, P.aviso_forma, P.aviso_dias, CD.nome, D.nome_desp FROM contas_pagar As P 
	INNER JOIN despesas AS D ON D.id = P.plano_conta
    INNER JOIN cat_despesas AS CD ON CD.id = P.despesas
	WHERE (vencimento >= '$dataInicial' AND vencimento <= '$dataFinal') AND status LIKE '$status'  order by P.id ");
	}
	
}else if($status != '%%' and $alterou_data == ''){
	$query = $pdo->query("SELECT P.id, P.descricao, P.cliente, saida, 
	P.documento, P.plano_conta, P.despesas, 
	P.data_emissao, P.vencimento, 
	P.frequencia, P.valor, P.usuario_lanc, 
	P.usuario_baixa, P.status, P.data_recor, 
	P.juros, P.multa, P.desconto, P.subtotal, P.data_baixa, P.jurosporc,
    P.multaporc, P.descontoporc, P.aviso_vencimento, P.aviso_baixa, P.aviso_forma, P.aviso_dias, CD.nome, D.nome_desp FROM contas_pagar As P 
	INNER JOIN despesas AS D ON D.id = P.plano_conta
    INNER JOIN cat_despesas AS CD ON CD.id = P.despesas
	 where status LIKE '$status'  order by vencimento ");
}

else if($vencidas == 'Vencidas'){
	$query = $pdo->query("SELECT P.id, P.descricao, P.cliente, saida, 
	P.documento, P.plano_conta, P.despesas, 
	P.data_emissao, P.vencimento, 
	P.frequencia, P.valor, P.usuario_lanc, 
	P.usuario_baixa, P.status, P.data_recor, 
	P.juros, P.multa, P.desconto, P.subtotal, P.data_baixa, P.jurosporc,
    P.multaporc, P.descontoporc, P.aviso_vencimento, P.aviso_baixa, P.aviso_forma, P.aviso_dias, CD.nome, D.nome_desp FROM contas_pagar As P 
	INNER JOIN despesas AS D ON D.id = P.plano_conta
    INNER JOIN cat_despesas AS CD ON CD.id = P.despesas
	 where vencimento < curDate() AND status = 'Pendente' order by vencimento  ");
}

else if($vencidas == 'Hoje'){
	$query = $pdo->query("SELECT P.id, P.descricao, P.cliente, saida, 
	P.documento, P.plano_conta, P.despesas, 
	P.data_emissao, P.vencimento, 
	P.frequencia, P.valor, P.usuario_lanc, 
	P.usuario_baixa, P.status, P.data_recor, 
	P.juros, P.multa, P.desconto, P.subtotal, P.data_baixa, P.jurosporc,
    P.multaporc, P.descontoporc, P.aviso_vencimento, P.aviso_baixa, P.aviso_forma, P.aviso_dias, CD.nome, D.nome_desp FROM contas_pagar As P 
	INNER JOIN despesas AS D ON D.id = P.plano_conta
    INNER JOIN cat_despesas AS CD ON CD.id = P.despesas
	WHERE vencimento = curDate()  order by vencimento  ");
}

else if($vencidas == 'Amanha'){
	$query = $pdo->query("SELECT P.id, P.descricao, P.cliente, saida, 
	P.documento, P.plano_conta, P.despesas, 
	P.data_emissao, P.vencimento, 
	P.frequencia, P.valor, P.usuario_lanc, 
	P.usuario_baixa, P.status, P.data_recor, 
	P.juros, P.multa, P.desconto, P.subtotal, P.data_baixa, P.jurosporc,
    P.multaporc, P.descontoporc, P.aviso_vencimento, P.aviso_baixa, P.aviso_forma, P.aviso_dias, CD.nome, D.nome_desp FROM contas_pagar As P 
	INNER JOIN despesas AS D ON D.id = P.plano_conta
    INNER JOIN cat_despesas AS CD ON CD.id = P.despesas
	WHERE vencimento = '$data_amanha'  order by vencimento  ");
}

else{
	
	$query = $pdo->query("SELECT P.id, P.descricao, P.cliente, saida, 
	P.documento, P.plano_conta, P.despesas, 
	P.data_emissao, P.vencimento, 
	P.frequencia, P.valor, P.usuario_lanc, 
	P.usuario_baixa, P.status, P.data_recor, 
	P.juros, P.multa, P.desconto, P.subtotal, P.data_baixa, P.jurosporc,
    P.multaporc, P.descontoporc, P.aviso_vencimento, P.aviso_baixa, P.aviso_forma, P.aviso_dias, CD.nome, D.nome_desp  FROM contas_pagar As P 
	INNER JOIN despesas AS D ON D.id = P.plano_conta
    INNER JOIN cat_despesas AS CD ON CD.id = P.despesas
	WHERE status = 'Pendente' order by vencimento  ");

    
}

@$res = $query->fetchAll(\PDO::FETCH_ASSOC);


return $res;

}

public function listarCard()
{
	$pagina = 'contas_pagar';
	$pdo = $this->connect();
	$query = $pdo->query("SELECT * from $pagina where status = 'Pendente' order by vencimento Limit 4 ");
	$res = $query->fetchAll(\PDO::FETCH_ASSOC);


	return $res;
	
}

public function conectar()
{

    $pdo = $this->connect();

    return $pdo;
}

public function baixa()
{


    
$pagina = 'contas_pagar';
//VARIAVEIS DOS INPUTS

@session_start();

$id = filter_input(INPUT_POST, 'id-baixar', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$valor = filter_input(INPUT_POST, 'valor-baixar', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$valor = $this->moneyToFloat($valor);
$id_usuario = $_SESSION['id'];
$valor_desconto = filter_input(INPUT_POST, 'valor-desconto', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$valor_desconto = $this->moneyToFloat($valor_desconto);
$valor_juros = filter_input(INPUT_POST, 'valor-juros', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$valor_juros = $this->moneyToFloat($valor_juros);
$valor_multa = filter_input(INPUT_POST, 'valor-multa', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$valor_multa = $this->moneyToFloat($valor_multa);
$subtotal = $valor + $valor_juros + $valor_multa - $valor_desconto;
$saida = filter_input(INPUT_POST, 'saida-baixar', FILTER_SANITIZE_FULL_SPECIAL_CHARS);



$pdo = $this->connect();



// Consulta preparada: evita SQL direto com id vindo do formulario.
$query = $pdo->prepare("SELECT 
id, descricao, cliente,
saida, documento, plano_conta,
valor, ativar, despesas, status, aviso_baixa, aviso_forma
FROM $pagina WHERE id = :id ");
$query->bindValue(':id', $id, \PDO::PARAM_INT);
$query->execute();

$res = $query->fetchAll(\PDO::FETCH_ASSOC);
$id = $res[0]['id'];
$id_conta_baixa = $id;
$cp1 = $res[0]['descricao'];
$cp2 = $res[0]['cliente'];
$cp3 = $res[0]['saida'];
$cp4 = $res[0]['documento'];
$cp5 = $res[0]['plano_conta'];
$cp9 = $res[0]['valor'];
$valor_original_baixa = $cp9;
$cp10 = $res[0]['ativar'];
$cp11 = $res[0]['despesas'];
$status_conta = $res[0]['status'];
$avisoBaixaConta = (int) ($res[0]['aviso_baixa'] ?? 0);
$avisoFormaConta = $res[0]['aviso_forma'] ?? 'email';

if($status_conta == 'Paga'){
	echo 'Esta conta ja esta paga';
	exit();
}

// Consulta preparada: fornecedor pode vir do cadastro da conta.
$query2 = $pdo->prepare("SELECT * FROM fornecedores WHERE id = :id");
$query2->bindValue(':id', $cp2, \PDO::PARAM_INT);
$query2->execute();
$res2 = $query2->fetchAll(\PDO::FETCH_ASSOC);
if(@count($res2) > 0){
$nome_fornecedor = $res2[0]['nome'];
$descricao_conta = $cp1 . ' - '. $nome_fornecedor;
}else{
$descricao_conta = $cp1;	
}
 
//RECUPERAR O CAIXA QUE ESTÁ ABERTO (CASO TENHA ALGUM)
$query2 = $pdo->query("SELECT * FROM caixa WHERE status = 'Aberto'");
$res2 = $query2->fetchAll(\PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$caixa_aberto = $res2[0]['id'];
	$valor_ab = $res2[0]['valor_ab'];
	$saldo = $res2[0]['saldo'];
	$status = $res2[0]['status'];
	@$banco = $res2[0]['nome'];
	$saldo_inicial = $res2[0]['saldo_inicial'];

	
}else{
	$caixa_aberto = 0;
}

if($valor > $cp9){
	echo 'O valor a ser pago não pode ser superior ao valor da conta! O valor da conta é de R$ '.$cp9;
	
	exit();
}

if($valor <= 0){
	echo 'O precisa ser maior que 0 ';
	exit();
}

if($subtotal <= 0){
	echo 'O valor total da baixa precisa ser maior que 0';
	exit();
}

// Transacao protege conta, movimentacao e saldo contra gravacao parcial.
try {
$pdo->beginTransaction();
$baixouTotal = false;

if($valor == $cp9){

	$pdo->query("UPDATE $pagina set saida = '$saida', usuario_baixa = '$id_usuario', status = 'Paga', 
	juros = '$valor_juros', multa = '$valor_multa', desconto = '$valor_desconto', subtotal = '$subtotal', data_baixa = curDate() where id = '$id' ");
	$cp9 = 0;
	$baixouTotal = true;
}else{

	//PEGAR RESIDUOS DA CONTA
	$total_resid = 0;
	$query = $pdo->query("SELECT * FROM valor_parcial WHERE id_conta = '$id'");
	$res = $query->fetchAll(\PDO::FETCH_ASSOC);
	if(@count($res) > 0){
	
		for($i=0; $i < @count($res); $i++){
		foreach ($res[$i] as $key => $value){} 
			$valor_resid = $res[$i]['valor'];
			$total_resid += $valor_resid;
		}
	}else{
		$cp1 = '(Resíduo) - ' .$cp1;
	}

	$cp9 = $cp9 - $valor;

	$pdo->query("INSERT INTO valor_parcial set id_conta = '$id', tipo = 'Pagar', valor = '$subtotal', data = curDate(), usuario = '$id_usuario'");

	$pdo->query("UPDATE $pagina set descricao = '$cp1', saida = '$saida', 
	usuario_baixa = '$id_usuario', 
	status = 'Pendente', juros = '$valor_juros', multa = '$valor_multa', 
	desconto = '$valor_desconto', valor = '$cp9', subtotal = '$subtotal', 
	data_baixa = curDate() where id = '$id'");

}

    // Consulta preparada: nome da conta/saida vem do formulario de baixa.
    $query = $pdo->prepare("SELECT * FROM bancarias WHERE banco = :banco");
	$query->bindValue(':banco', $saida);
	$query->execute();
	$res = $query->fetchAll(\PDO::FETCH_ASSOC);
	$conta = count($res);
	@$idbanc = $res[0]['id'];
	@$saldo = $res[0]['saldo'];
	@$banco = $res[0]['banco'];
	@$tipo = $res[0]['tipo'];

  
  if(@$status == 'Aberto' and $cp3 == 'Caixa'){
	//LANÇAR NAS MOVIMENTAÇÕES
$pdo->query("INSERT INTO movimentacoes set tipo = 'Saida', S = '$subtotal', 
movimento = 'Pagar', descricao = '$descricao_conta', valor = '$subtotal', 
usuario = '$id_usuario', data = curDate(), lancamento = '$cp3', 
plano_conta = '$cp5', documento = '$cp4', caixa_periodo = '$caixa_aberto', 
conta_pag = '$id', despesas = $cp11");
$idMovimento = $pdo->lastInsertId();
	
	$query = $pdo->query("SELECT * FROM movimentacoes WHERE id = '$idMovimento'");
	$res = $query->fetchAll(\PDO::FETCH_ASSOC);
	$idCaixa = $res[0]['caixa_periodo'];
     $valor = $res[0]['valor'];
	 //$total = count($res);
		
	    $id = $res[0]['id'];
		$tipoMov = $res[0]['tipo'];
		$usuaMov = $res[0]['usuario'];
		$dataMov = $res[0]['data'];
		$contaMov = $res[0]['conta_pag'];
		$descMov = $res[0]['plano_conta'];
        
	
     $total = $valor_ab -= $valor;
	 $saldof = $saldo_inicial + $total;

	$query = $pdo->query("UPDATE caixa SET saldo = '$saldof', valor_ab = '$valor_ab' WHERE id = '$idCaixa'");
    $query2 = $pdo->query("INSERT INTO controle_caixa SET data = '$dataMov',  movimento = '$descMov', 
	entrada = 0.00, saida = '$valor', saldo = '$saldof', tipo = 'Saida', id_caixa = '$idCaixa' ");

	//$pdo->query("INSERT INTO saldo_conta set conta = '$banco', tipo_conta = '$tipo', saldo = '$total', 
	//usuario = '$usuaMov', tipo = '$tipoMov', data = '$dataMov', plano_conta = '$descMov', valor = '$valor', mov = '$idCaixa', pagar_receber = '$contaMov'");

	echo 'Baixado com Sucesso';
 
}else if($conta > 0){

	
$pdo->query("INSERT INTO movimentacoes set tipo = 'Saida', S = '$subtotal', movimento = 'Conta à Pagar', 
descricao = '$descricao_conta', valor = '$subtotal', 
usuario = '$id_usuario', data = curDate(), lancamento = '$cp3', 
plano_conta = '$cp5', despesas = $cp11, documento = '$cp4', caixa_periodo = '$idbanc', conta_pag = '$id'");
$idMovimento = $pdo->lastInsertId();

$total = $saldo -= $subtotal;

$query = $pdo->query("UPDATE bancarias SET saldo = '$total' WHERE id = '$idbanc'");

$query = $pdo->query("SELECT * FROM movimentacoes WHERE id = '$idMovimento'");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
$total = count($res);
$id = $res[0]['id'];
$tipoMov = $res[0]['tipo'];
$usuaMov = $res[0]['usuario'];
$dataMov = $res[0]['data'];
$valorMov = $res[0]['valor'];
$caixmov = $res[0]['caixa_periodo'];
$movConta = $res[0]['mov_contas'];
$contaMov = $res[0]['conta_pag'];
$descMov = $res[0]['descricao'];
 
$pdo->query("INSERT INTO saldo_conta set conta = '$banco', tipo_conta = '$tipo', 
saldo = '$saldo', usuario = '$usuaMov', tipo = 'Saida', data = '$dataMov', 
plano_conta = '$descMov', valor = '$valorMov', 
mov = '$caixmov', pagar_receber = '$contaMov', credito = '$valorMov'");

echo 'Baixado com Sucesso';
	   
  }else{
     
	echo "Não foi possivel efetura baixa da lançamento . $id . caixa Fexado ";
	$pdo->query("UPDATE $pagina set saida = '$cp3', usuario_baixa = '$id_usuario', status = 'Pendente', juros = '$valor_juros', multa = '$valor_multa', desconto = '$valor_desconto', subtotal = '$subtotal', data_baixa = curDate() where id = '$id'");
 
}

// Auditoria financeira: registra a baixa e o saldo restante da conta.
$this->registrarAuditoriaFinanceira($pdo, 'baixa', $pagina, $id_conta_baixa, $id_usuario, $valor_original_baixa, $cp9, 'Baixa de conta a pagar');

$pdo->commit();

// Aviso de baixa: registra pagamento concluido sem duplicar notificacoes para a mesma conta.
if ($baixouTotal && $avisoBaixaConta === 1) {
	$notificacoes = new \app\models\CrudNotificacoes();
	$notificacoes->registrarAvisoBaixa('pagar', $id_conta_baixa, $avisoFormaConta);
}
} catch (\Throwable $erro) {
	if ($pdo->inTransaction()) {
		$pdo->rollBack();
	}
	error_log($erro->getMessage());
	echo 'Nao foi possivel concluir a baixa. Nenhum saldo foi alterado.';
	return $pdo;
}

return $pdo;


}

public function recorrentes()

{

    $pagina = 'contas_pagar';
//VARIAVEIS DOS INPUTS


  //ROTINA PARA VERIFICAR COBRANÇAS RECORRENTES
$data_atual = date('Y-m-d');
$dia = date('d');
$mes = date('m');
$ano = date('Y');
$pdo = $this->connect();
$query = $pdo->query("SELECT * from $pagina order by id desc ");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 
	
	$id = $res[$i]['id'];
		$cp1 = $res[$i]['descricao'];
		$cp2 = $res[$i]['cliente'];
		$cp3 = $res[$i]['saida'];
		$cp4 = $res[$i]['documento'];
		$cp5 = $res[$i]['plano_conta'];
		$cp6 = $res[$i]['data_emissao'];
		$cp7 = $res[$i]['vencimento'];
		$cp8 = $res[$i]['frequencia'];
		$cp9 = $res[$i]['valor'];
		$cp10 = $res[$i]['usuario_lanc'];
		$cp11 = $res[$i]['usuario_baixa'];
		
		$cp13 = $res[$i]['status'];
		@$cp14 = $res[$i]['data_recor'];
		$data_recor_atual = $cp14 ? date('Y-m-d', strtotime($cp14)) : null;
         $cp17 = $res[$i]['despesas'];
	$recor_str = explode("-",$cp14);
		
	$dia_recor = @$recor_str[2];
	//var_dump($dia_recor);
	
$frequencia = $res[$i]['frequencia'];

$query1 = $pdo->query("SELECT * from frequencias where nome = '$frequencia' ");
$res1 = $query1->fetchAll(\PDO::FETCH_ASSOC);
$dias_frequencia = $res1[0]['dias'];

// Recorrencia corrigida: calcula data_recor e vencimento pela frequencia preservando dia 29/30/31 quando possivel.
$data_recor = $this->proximaDataRecorrencia($data_atual, $frequencia, $dias_frequencia);
$nova_data_vencimento = $this->proximaDataRecorrencia($cp7, $frequencia, $dias_frequencia);

	if($dias_frequencia > 0){
		if($data_recor_atual !== null && $data_recor_atual <= $data_atual){

			$queryDuplicado = $pdo->query("SELECT id FROM contas_pagar WHERE descricao = '$cp1' AND cliente = '$cp2' AND vencimento = '$nova_data_vencimento' AND valor = '$cp9' LIMIT 1");
			$resDuplicado = $queryDuplicado->fetchAll(\PDO::FETCH_ASSOC);

			if(@count($resDuplicado) > 0){
				$pdo->query("UPDATE $pagina SET data_recor = '$data_recor' where id='$id'");
				continue;
			}

			$pdo->query("INSERT INTO contas_pagar set descricao = '$cp1', cliente = '$cp2', 
			saida = '$cp3', documento = '$cp4', plano_conta = '$cp5', despesas = '$cp17',
			 data_emissao = curDate(), vencimento = '$nova_data_vencimento', 
			 frequencia = '$cp8', valor = '$cp9', usuario_lanc = '$cp10', status = 'Pendente',
             juros = '0', multa = '0', desconto = '0', data_recor = '$data_recor',
             aviso_vencimento = '{$res[$i]['aviso_vencimento']}', aviso_baixa = '{$res[$i]['aviso_baixa']}',
             aviso_forma = '{$res[$i]['aviso_forma']}', aviso_dias = '{$res[$i]['aviso_dias']}'");
             $id_ult_registro = $pdo->lastInsertId();
           
             
             $pdo->query("UPDATE contas_pagar set data_recor = NULL where id='$id'");
 


			
				if($data_atual == $cp6){
					$pdo->query("DELETE FROM $pagina where id='$id_ult_registro'");
					$pdo->query("UPDATE $pagina SET data_recor = '$data_recor' where id='$id'");
				}

				
				
		}
		
				
	}
	

}


	return $pdo;

}


public function listarResiduos()
{

    @$id = $_POST['id'];
    $pdo = $this->connect();
    $query = $pdo->query("SELECT * from valor_parcial where id_conta = '$id' order by id desc ");
    $res = $query->fetchAll(\PDO::FETCH_ASSOC);
    return $res;

}

public function listarComprovantes()
{
    @$id = $_POST['id'];
    $pdo = $this->connect();
    $query = $pdo->query("SELECT * from fotos where id_contas = '$id' order by id_contas desc ");
    $res = $query->fetchAll(\PDO::FETCH_ASSOC);
    return $res;

}


public function parcela()

{


$pagina = 'contas_pagar';



$pdo = $this->connect();

@$id = $_POST['id-parcelar'];
@$qtd_parcelas = $_POST['qtd-parcelar'];
@$frequencia = $_POST['frequencia-parcelar'];

$query = $pdo->query("SELECT * from $pagina where id = '$id' ");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);


@$id = $res[0]['id'];
@$cp1 = $res[0]['descricao'];
@$cp2 = $res[0]['cliente'];
@$cp3 = $res[0]['saida'];
@$cp4 = $res[0]['documento'];
@$cp5 = $res[0]['plano_conta'];
@$cp6 = $res[0]['data_emissao'];
@$cp7 = $res[0]['vencimento'];
@$cp8 = $res[0]['frequencia'];
@$cp9 = $res[0]['valor'];
@$cp10 = $res[0]['usuario_lanc'];
@$cp11 = $res[0]['usuario_baixa'];
@$cp13 = $res[0]['status'];
$cp14 = $res[0]['juros'];
$cp15 = $res[0]['multa'];
$cp16 = $res[0]['desconto'];
$cp17 = $res[0]['despesas'];
$avisoVencimentoParcela = (int) ($res[0]['aviso_vencimento'] ?? 0);
$avisoBaixaParcela = (int) ($res[0]['aviso_baixa'] ?? 0);
$avisoFormaParcela = $res[0]['aviso_forma'] ?? 'email';
$avisoDiasParcela = (int) ($res[0]['aviso_dias'] ?? 2);



$query1 = $pdo->query("SELECT * from frequencias where nome = '$frequencia' ");
$res1 = $query1->fetchAll(\PDO::FETCH_ASSOC);
@$dias_frequencia = $res1[0]['dias'];


$novo_valor = $cp9/ $qtd_parcelas;
$i = 1;
$soma_valor = 0;


while($i <= $qtd_parcelas){
	
	$nova_descricao = $cp1 . ' - Parcela '.$i;
	$dias_parcela = $i - 1;
	$dias_parcela_2 = ($i - 1) * $dias_frequencia;
	//$valor_ultima_parc = 0;
	//var_dump($qtd_parcelas2);
	//var_dump($i);
	
	if($i == $qtd_parcelas){
		
		$valor_ultima_parc = $cp9 - $soma_valor;
		
		$soma_valor += number_format($valor_ultima_parc, 2, '.', '');
		$novo_valorF =  $valor_ultima_parc;
	
				
	}else{
		$soma_valor += number_format($novo_valor, 2, '.', '');
		
		$novo_valorF =  $novo_valor;
	}

	// Parcelamento corrigido: usa a mesma regra de recorrencia para nao repetir vencimentos mensais.
	$novo_vencimento = ($i == 1) ? $cp7 : $this->proximaDataRecorrencia($cp7, $frequencia, $dias_frequencia, $i - 1);


		
	$pdo->query("INSERT INTO $pagina set descricao = '$nova_descricao', cliente = '$cp2', saida = '$cp3', 
			documento = '$cp4', plano_conta = '$cp5', despesas = '$cp17', data_emissao = curDate(), vencimento = '$novo_vencimento', 
			frequencia = '$cp8', valor = '$novo_valorF', usuario_lanc = '$cp10', status = 'Pendente', data_recor = curDate(), 
			juros = '$cp14', multa = '$cp15', desconto = '$cp16',
			aviso_vencimento = '$avisoVencimentoParcela', aviso_baixa = '$avisoBaixaParcela',
			aviso_forma = '$avisoFormaParcela', aviso_dias = '$avisoDiasParcela'");
		
		  
		
	$i++; 

	
}



$pdo->query("DELETE from $pagina where id = '$id'");

echo 'Parcelado com Sucesso';
	
return $pdo;
	
}

public function jurosPag()
{

	$pagina = 'contas_pagar';

	

			$pdo = $this->connect();
			$data_juros = date('Y-m-d');

		$query = $pdo->query("SELECT * from $pagina WHERE vencimento < '$data_juros' and status = 'Pendente'");
		$res = $query->fetchAll(\PDO::FETCH_ASSOC);
		for($i=0; $i < @count($res); $i++){
		foreach ($res[$i] as $key => $value){} 
		@$id = $res[$i]['id'];
		@$cp1 = $res[$i]['descricao'];

		@$cp7 = $res[$i]['vencimento'];

		@$cp9 = $res[$i]['valor'];


		$cp16 = $res[$i]['jurosporc'];


		$valor_juros = ( $cp16 / 100) * $cp9;

		//var_dump($valor_juros);

		$pdo->query("UPDATE $pagina set juros = '$valor_juros' where vencimento < '$data_juros' and status ='Pendente' and id = '$id'");



		//CALCULAR PRAZO DE DIAS ENTRE DATAS
		/*$agora = date('Y-m-d');
		$diferenca = strtotime($agora) - strtotime($cp7);
		$prazo = floor($diferenca / (60*60*24));*/
		}
			
		/*	
		var_dump($prazo);

		var_dump($id);
		var_dump($data_juros);
		var_dump($valor_juros);

		var_dump($cp9);
		var_dump($cp1);*/


		return $pdo;

}


public function desconto()
{
	$pagina = 'contas_pagar';

	

$pdo = $this->connect();
	

$query = $pdo->query("SELECT * from $pagina WHERE descontoporc > 0 and status = 'Pendente'");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
for($i=0; $i < @count($res); $i++){
foreach ($res[$i] as $key => $value){} 
@$id = $res[$i]['id'];

@$cp9 = $res[$i]['valor'];

$cp14 = $res[$i]['descontoporc'];

$desconto = ( $cp14 / 100) * $cp9;

$pdo->query("UPDATE $pagina set desconto = '$desconto' where id = '$id'");

}

//var_dump($res);

//var_dump($desconto );

return $pdo;

}

public function multa()

{

		$pagina = 'contas_pagar';

		

	$pdo = $this->connect();
	
	$query = $pdo->query("SELECT * from $pagina WHERE multaporc > 0 and status = 'Pendente'");
	$res = $query->fetchAll(\PDO::FETCH_ASSOC);
	for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 
	@$id = $res[$i]['id'];

	@$cp9 = $res[$i]['valor'];

	$cp14 = $res[$i]['descontoporc'];

	$cp15 = $res[$i]['multaporc'];

	$multa = ( $cp15 / 100) * $cp9;

	$pdo->query("UPDATE $pagina set multa = '$multa' where id = '$id'");

}


}

public function imagens()
{

	$pagina = 'produtos';
	$pdo = $this->connect();
    $id_fotos = filter_input(INPUT_POST, 'id_fotos', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id_contas = filter_input(INPUT_POST, 'id_contas', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$fotos = filter_input(INPUT_POST, 'fotos', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

//VALIDAR CAMPO
$query = $pdo->query("SELECT * from fotos where id_fotos = '$id_fotos'");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
$total_reg = @count($res);
$id_reg = @$res[0]['id_fotos'];
$id_reg = @$res[0]['id_contas'];
$id_reg = @$res[0]['fotos'];

if($total_reg > 0 and $id_reg != $id_fotos){
	echo 'Este registro já está cadastrado!!';
	exit();
}

//SCRIPT PARA SUBIR FOTO NO BANCO
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['imagem']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = 'config/img/'.$pagina.'/' .$nome_img;
if (@$_FILES['imagem']['name'] == ""){
	$imagem = "sem-foto.jpg";
}else{
	$imagem = $nome_img;
}

$imagem_temp = @$_FILES['imagem']['tmp_name']; 
$ext = pathinfo($imagem, PATHINFO_EXTENSION);   
if($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'pdf'){ 
	move_uploaded_file($imagem_temp, $caminho);
}else{
	echo 'Extensão de Imagem não permitida!';
	exit();
}


if($id_fotos == ""){
	$query = $pdo->prepare("INSERT INTO fotos set id_contas = :campo1, fotos = :campo2 ");
	$query->bindValue(":campo2", "$imagem");
}else{
	if($imagem == "sem-foto.jpg"){
	$query = $pdo->prepare("UPDATE fotos set id_contas = :campo1 WHERE id_contas = '$id_contas'");
	
}else{
		
	//BUSCAR A IMAGEM PARA EXCLUIR DA PASTA
	$query_con = $pdo->query("SELECT * FROM fotos WHERE id_fotos = '$id_fotos'");
	$res_con = $query_con->fetchAll(\PDO::FETCH_ASSOC);
	$imagem_antiga = $res_con[0]['foto'];
	if($imagem_antiga != 'sem-foto.jpg'){
		@unlink('config/img/produtos/'.$imagem_antiga);
	}
	$query = $pdo->prepare("UPDATE fotos set id_contas = :campo1, fotos = :campo2 WHERE id_contas = '$id_contas'");
	$query->bindValue(":campo2", "$imagem");
	}
}

$query->bindValue(":campo1", "$id_contas");
$query->bindValue(":campo2", "$imagem");

$query->execute();


echo 'Salvo com Sucesso';



return $query;


}

public function listaImagens()
{

    @$id_contas = $_POST['id'];
    $pdo = $this->connect();
    $query = $pdo->query("SELECT * from fotos where id_conta = '$id_contas' order by id desc ");
    $res = $query->fetchAll(\PDO::FETCH_ASSOC);
    //var_dump($res);
     return $res;


}

public function mudarStatus()
  {
    $pagina = 'contas_pagar';

      
      $ativo = filter_input(INPUT_POST, 'ativar', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $pdo = $this->connect();

	  if($ativo == 'ativar'){

		$pdo->query("UPDATE $pagina SET ativar = 1 ");
	  }else{

		$pdo->query("UPDATE $pagina SET ativar = 0 ");
	  }

	  $query = $pdo->query("SELECT * from $pagina where status = 'Pendente' order by id desc ");
      $res = $query->fetchAll(\PDO::FETCH_ASSOC);
     
      return $res;
  }

  public function gerarExcel()
{

$dataInicial = filter_input(INPUT_POST, 'dataInicial', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$dataFinal =  filter_input(INPUT_POST, 'dataFinal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tipo =  filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	//$dataInicial = $_GET['dataInicial'];
	//$dataFinal = $_GET['dataFinal'];

	//var_dump($dataInicial);
    //var_dump($dataFinal);

	$pdo = $this->connect();

if($dataInicial != "" || $dataFinal != "" AND $tipo == ""){
	$query = $pdo->prepare("SELECT 
	P.id, P.descricao, P.cliente, P.saida,
  	F.nome_fpg, D.nome_desp, CD.nome, 
	P.data_emissao, P.vencimento, 
	P.frequencia, P.valor, Ul.nome_usu,
	P.status, P.data_recor, 
	P.juros, P.multa, P.desconto, P.subtotal, P.data_baixa
    FROM contas_pagar As P 
	INNER JOIN despesas AS D ON D.id = P.plano_conta
    INNER JOIN cat_despesas AS CD ON CD.id = P.despesas
    INNER JOIN usuarios AS Ul ON Ul.id = P.usuario_lanc 
    INNER JOIN formas_pgtos AS F ON F.id = P.documento
    WHERE data_emissao >= :dataInicial AND data_emissao <= :dataFinal OR status = :tipo ");
        
        $query->bindValue(":dataInicial", "$dataInicial");
        $query->bindValue(":dataFinal", "$dataFinal");
		$query->bindValue(":tipo", "$tipo");
        $query->execute();
	
	
	}else if($dataInicial != "" || $dataFinal != ""){         
	
		$query = $pdo->prepare("SELECT 
		P.id, P.descricao, P.cliente, P.saida,
	    F.nome_fpg, D.nome_desp, CD.nome, 
		P.data_emissao, P.vencimento, 
		P.frequencia, P.valor, Ul.nome_usu,
		P.status, P.data_recor, 
		P.juros, P.multa, P.desconto, P.subtotal, P.data_baixa
		FROM contas_pagar As P 
		INNER JOIN despesas AS D ON D.id = P.plano_conta
		INNER JOIN cat_despesas AS CD ON CD.id = P.despesas
		INNER JOIN usuarios AS Ul ON Ul.id = P.usuario_lanc 
		INNER JOIN formas_pgtos AS F ON F.id = P.documento
		WHERE data_emissao >= :dataInicial AND data_emissao <= :dataFinal AND status = :tipo ");
			
			$query->bindValue(":dataInicial", "$dataInicial");
			$query->bindValue(":dataFinal", "$dataFinal");
			$query->bindValue(":tipo", "$tipo");
			$query->execute();
	
	}else{

	}
		return $query;
		
}


  
	}

	



	
