<?php

namespace app\models;

class CrudDespesas extends Connection
{
    public function listarDesp()
    {

$pagina = 'despesas';
//VARIAVEIS DOS INPUTS
$campo1 = 'Nome';
$campo2 = 'Categoria';
$campo3 = 'Subgrupo';

$pdo = $this->connect();
$query = $pdo->query("SELECT P.id, P.nome_desp, P.cat_despesa, P.subgrupo, C.nome as cat_nome from $pagina As P 
INNER JOIN cat_despesas AS C  ON P.cat_despesa = C.id order by id desc ");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);

return $res;		

    }

    public function listarCat()
    {
        

		$pdo = $this->connect();
		
		$query2 = $pdo->query("SELECT cd.nome FROM despesas as d, cat_despesas as cd where d.id = cd.id");
		$res2 = $query2->fetchAll(\PDO::FETCH_ASSOC);
		

       
//var_dump($res2);

    return $res2;
}

    public function inserir()
    {

        $pagina = 'despesas';
//VARIAVEIS DOS INPUTS
$campo1 = 'Nome';
$campo2 = 'Categoria';
$campo3 = 'Subgrupo';


$cp1 = filter_input(INPUT_POST, $campo1, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp2 = filter_input(INPUT_POST, $campo2, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp3 = filter_input(INPUT_POST, $campo3, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$id = @$_POST['id'];

$pdo = $this->connect();

//VALIDAR CAMPO
$query = $pdo->query("SELECT id from $pagina where nome_desp = '$cp1' and cat_despesa = '$cp2'");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
$total_reg = @count($res);
$id_reg = @$res[0]['id'];
if($total_reg > 0 and $id_reg != $id){
	echo 'Este registro já está cadastrado!!';
	exit();
}

if($id == ""){
	$query = $pdo->prepare("INSERT INTO $pagina set nome_desp = :campo1, cat_despesa = :campo2,
     subgrupo = :campo3 ");
}else{
	$query = $pdo->prepare("UPDATE $pagina set nome_desp = :campo1, cat_despesa = :campo2,
    subgrupo = :campo3 WHERE id = '$id'");
}

$query->bindValue(":campo1", "$cp1");
$query->bindValue(":campo2", "$cp2");
$query->bindValue(":campo3", "$cp3");

$query->execute();

echo 'Salvo com Sucesso';

return $query;

    }

    public function excluir()
    {
        $pagina = 'despesas';
        $id = filter_input(INPUT_POST, 'id-excluir', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pdo = $this->connect();
        $pdo->query("DELETE from $pagina where id = '$id'");
        echo 'Excluído com Sucesso';

        return $pdo;
    }

    public function selecao()
    {
        $pdo = $this->connect();
        $query = $pdo->query("SELECT * FROM cat_despesas order by nome asc");
        $res = $query->fetchAll(\PDO::FETCH_ASSOC);

                            return $res;
    }

    public function conectar()
{

    $pdo = $this->connect();

    return $pdo;
}



}
