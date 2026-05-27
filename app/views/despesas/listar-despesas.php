<?php
use app\controllers\ContasPagar;
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
$campo10 = 'usuario_lanc';
$campo11 = 'usuario_baixa';
$campo12 = 'Caixa';
$campo13 = 'Status';


//No formualrio contas pagar e receber seleciona a despesa referente a categoria

@$id_cat = $_POST['cat'];
@$despesa = @$_POST['despesa'];
//var_dump($id_cat);
$con = new ContasPagar;
$pdo = $con->conectar();
$query = $pdo->query("SELECT * FROM cat_despesas where id = '$id_cat'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
@$id_cat = $res[0]['id'];

echo '<select class="form-select" aria-label="Default select example" name="'.$campo5.'" id="'.$campo5.' require">';

$query = $pdo->query("SELECT * FROM despesas where cat_despesa = '$id_cat' order by id asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){	}
		$id_item = $res[$i]['id'];
	$nome_item = $res[$i]['nome_desp'];

	if($despesa == $nome_item){
		$selec = 'selected';
	}else{
		$selec = '';
	}
	
	echo '<option '.$selec.' value="'.$id_item.'">'.$nome_item.'</option>';

} 

echo '</select>';

?>