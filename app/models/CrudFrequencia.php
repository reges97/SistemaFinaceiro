<?php

namespace app\models;

class CrudFrequencia extends Connection
{
    public function listarFreq()
    {
$pagina = 'frequencias';
//VARIAVEIS DOS INPUTS
$campo1 = 'Nome';
$campo2 = 'Dias';

$pdo = $this->connect();

$query = $pdo->query("SELECT * from $pagina order by id desc ");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);

return $res;
       

    }

    public function inserir()
{
    $pagina = 'frequencias';
//VARIAVEIS DOS INPUTS
$campo1 = 'Nome';
$campo2 = 'Dias';

    $cp1 = filter_input(INPUT_POST, $campo1, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cp2 = filter_input(INPUT_POST, $campo2, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $pdo = $this->connect();

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
	$query = $pdo->prepare("INSERT INTO $pagina set nome = :campo1, dias = :campo2");
}else{
	$query = $pdo->prepare("UPDATE $pagina set nome = :campo1, dias = :campo2 WHERE id = '$id'");
}

$query->bindValue(":campo1", "$cp1");
$query->bindValue(":campo2", "$cp2");

$query->execute();

echo 'Salvo com Sucesso';
 return $query;
  

}    

public function excluir()
{

    $pagina = 'frequencias';
        $id = @$_POST['id-excluir'];
      $pdo = $this->connect();
        $pdo->query("DELETE from $pagina where id = '$id'");
        echo 'Excluído com Sucesso';

        return $pdo;
}
}