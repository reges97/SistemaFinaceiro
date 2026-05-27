<?php

namespace app\models;

class CrudFornecedor extends Connection
{
    public function listrForn()

    {
       
        $pagina = 'fornecedores';

        $pdo = $this->connect();

        $query = $pdo->query("SELECT * from $pagina order by id desc ");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
return $res;


    }

    public function inserir()
    {
        $pdo = $this->connect();

        $pagina = 'fornecedores';
//VARIAVEIS DOS INPUTS
$campo1 = 'Nome';
$campo2 = 'Pessoa';
$campo3 = 'Doc';
$campo4 = 'Telefone';
$campo5 = 'Endereco';
$campo6 = 'Ativo';
$campo7 = 'Obs';
$campo8 = 'Banco';
$campo9 = 'Agencia';
$campo10 = 'Conta';
$campo11 = 'Email';

$cp1 = filter_input(INPUT_POST, $campo1, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp2 = filter_input(INPUT_POST, $campo2, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp3 = filter_input(INPUT_POST, $campo3, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp4 = filter_input(INPUT_POST, $campo4, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp5 = filter_input(INPUT_POST, $campo5, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp6 = filter_input(INPUT_POST, $campo6, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp7 = filter_input(INPUT_POST, $campo7, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp8 = filter_input(INPUT_POST, $campo8, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp9 = filter_input(INPUT_POST, $campo9, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp10 = filter_input(INPUT_POST, $campo10, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp11 = filter_input(INPUT_POST, $campo11, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if($cp9 == ""){
	$cp8 = "";
}

$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

//VALIDAR CAMPO
$query = $pdo->query("SELECT * from $pagina where email = '$cp11'");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
$total_reg = @count($res);
$id_reg = @$res[0]['id'];
if($total_reg > 0 and $id_reg != $id){
	echo 'Este registro já está cadastrado!!';
	exit();
}


//VALIDAR CAMPO
$query = $pdo->query("SELECT * from $pagina where doc = '$cp3'");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
$total_reg = @count($res);
$id_reg = @$res[0]['id'];
if($total_reg > 0 and $id_reg != $id){
	echo 'Este registro já está cadastrado!!';
	exit();
}

if($id == ""){
	$query = $pdo->prepare("INSERT INTO $pagina set nome = :campo1, pessoa = :campo2, doc = :campo3, telefone = :campo4, endereco = :campo5, ativo = :campo6, obs = :campo7, data = curDate(), banco = :campo8, agencia = :campo9, conta = :campo10, email = :campo11");
}else{
	$query = $pdo->prepare("UPDATE $pagina set nome = :campo1, pessoa = :campo2, doc = :campo3, telefone = :campo4, endereco = :campo5, ativo = :campo6, obs = :campo7, data = curDate(), banco = :campo8, agencia = :campo9, conta = :campo10, email = :campo11 WHERE id = '$id'");
}

$query->bindValue(":campo1", "$cp1");
$query->bindValue(":campo2", "$cp2");
$query->bindValue(":campo3", "$cp3");
$query->bindValue(":campo4", "$cp4");
$query->bindValue(":campo5", "$cp5");
$query->bindValue(":campo6", "$cp6");
$query->bindValue(":campo7", "$cp7");
$query->bindValue(":campo8", "$cp8");
$query->bindValue(":campo9", "$cp9");
$query->bindValue(":campo10", "$cp10");
$query->bindValue(":campo11", "$cp11");
$query->execute();

echo 'Salvo com Sucesso';

return $query;
    }

    public function excluir()
    {
        $pagina = 'fornecedores';
        $id = filter_input(INPUT_POST, 'id-excluir', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $pdo = $this->connect();
        $pdo->query("DELETE from $pagina where id = '$id'");
echo 'Excluído com Sucesso';




    }

    public function mudar()

{
$pagina = 'fornecedores';
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$ativo = filter_input(INPUT_POST, 'ativar', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$pdo = $this->connect();

$pdo->query("UPDATE $pagina SET ativo = '$ativo' where id = '$id'");
echo 'Alterado com Sucesso';
return $pdo;
}

public function selecaoBanco()
{
    $pdo = $this->connect(); 
    $query = $pdo->query("SELECT * FROM bancos order by nome asc");
    $res = $query->fetchAll(\PDO::FETCH_ASSOC);

    return $res;

}




}