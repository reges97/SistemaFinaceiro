<?php

use app\controllers\Banca;

$pagina = 'bancarias';
//VARIAVEIS DOS INPUTS
$campo1 = 'id';
$campo2 = 'banco';


$con = new Banca;
$pdo = $con->conectar();

echo '<select class="form-select" aria-label="Default select example" name="'.$campo2.'" id="'.$campo2.'">';
echo '<option value="">Todos os Bancos</option>';
$query = $pdo->query("SELECT * FROM $pagina order by banco");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){	}
		$nome_banco = $res[$i]['banco'];
	
		echo '<option '.$selec.' value="'.$nome_banco.'">'.$nome_banco.'</option>';
} 

	

echo '</select>';


?>



