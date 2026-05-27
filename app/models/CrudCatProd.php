<?php

namespace app\models;

class CrudCatProd extends Connection
{
    public function listarCatProd()
    {
        $pagina = 'cat_produtos';
//VARIAVEIS DOS INPUTS


       $pdo = $this->connect();

       $query = $pdo->query("SELECT * from $pagina order by id desc ");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
return $res;
    }

    public function inserir()
    {
        $pagina = 'cat_produtos';
//VARIAVEIS DOS INPUTS
        $campo1 = 'Nome';

       $pdo = $this->connect();

       $cp1 = filter_input(INPUT_POST, $campo1, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

       $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

//VALIDAR CAMPO
$query = $pdo->query("SELECT * from $pagina where nome = '$cp1'");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
$total_reg = @count($res);
$id_reg = @$res[0]['id'];
if($total_reg > 0 and $id_reg != $id){
	echo 'Este registro já está cadastrado!!';
	exit();
}

if($id == ""){
	$query = $pdo->prepare("INSERT INTO $pagina set nome = :campo1");
}else{
	$query = $pdo->prepare("UPDATE $pagina set nome = :campo1 WHERE id = '$id'");
}

$query->bindValue(":campo1", "$cp1");
$query->execute();

echo 'Salvo com Sucesso';

return $query;
    }

    public function excluir()
{
    $pagina = 'cat_produtos';
    $id = filter_input(INPUT_POST, 'id-excluir', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $pdo = $this->connect();


    $query = $pdo->query("SELECT * from produtos where categoria = '$id'");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
$total_reg = @count($res);

if($total_reg == 0){
$pdo->query("DELETE from $pagina where id = '$id'");
echo 'Excluído com Sucesso';
}else{
	echo 'Esta categoria possui produtos associadas a ela, primeiro exclua estes produtos e depois exclua a categoria!';
}
return $pdo;

}

/*public function listarCatProd2()
{
    $pagina = 'cat_produtos';
    $pdo = $this->connect();

    $query = $pdo->query("SELECT * from $pagina order by id desc ");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $value => $value){} 

		$id = $res[$i]['id'];
		

		$query2 = $pdo->query("SELECT * from produtos where categoria = '$id'");
		$res2 = $query2->fetchAll(\PDO::FETCH_ASSOC);
        $total = count($res2);
        
        var_dump($total);
        
	
}
return $total;
}*/

public function conectar()
{

    $pdo = $this->connect();

    return $pdo;
}


}