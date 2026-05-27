<?php

namespace app\models;


class CrudBancarias extends Connection
{


   public function inserir()

{

$pagina = 'bancarias';
//VARIAVEIS DOS INPUTS
$campo1 = 'Banco';
$campo2 = 'Agencia';
$campo3 = 'Conta';
$campo4 = 'Tipo';
$campo5 = 'Pessoa';
$campo6 = 'Doc';
$campo7 = 'Saldo';
$campo8 = 'Saldo_ini';

$cp1 = filter_input(INPUT_POST, $campo1, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp2 = filter_input(INPUT_POST, $campo2, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp3 = filter_input(INPUT_POST, $campo3, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp4 = filter_input(INPUT_POST, $campo4, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp5 = filter_input(INPUT_POST, $campo5, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp6 = filter_input(INPUT_POST, $campo6, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp7 = filter_input(INPUT_POST, $campo7, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp8 = filter_input(INPUT_POST, $campo8, FILTER_SANITIZE_FULL_SPECIAL_CHARS);


$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

//VALIDAR CAMPO
$pdo = $this->connect(); 
$query = $pdo->query("SELECT * from bancarias where conta = '$cp3'");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
$total_reg = @count($res);
$id_reg = @$res[0]['id'];
if($total_reg > 0 and $id_reg != $id){
	echo 'Este registro já está cadastrado!!';
	exit();
}

if($id == ""){
	$query = $pdo->prepare("INSERT INTO bancarias set banco = :campo1, agencia = :campo2, conta = :campo3, 
    tipo = :campo4, pessoa = :campo5, doc = :campo6, saldo_ini = :campo8, saldo = :campo7");
}else{
	$query = $pdo->prepare("UPDATE bancarias set banco = :campo1, agencia = :campo2, conta = :campo3, 
    tipo = :campo4, pessoa = :campo5, doc = :campo6, saldo_ini = :campo8, saldo = :campo7 
    WHERE id = '$id'");
}

$query->bindValue(":campo1", "$cp1");
$query->bindValue(":campo2", "$cp2");
$query->bindValue(":campo3", "$cp3");
$query->bindValue(":campo4", "$cp4");
$query->bindValue(":campo5", "$cp5");
$query->bindValue(":campo6", "$cp6");
$query->bindValue(":campo8", "$cp8");
$query->bindValue(":campo7", "$cp7");
$query->execute();

echo 'Salvo com Sucesso';

return $query;


    }

    public function listarBanca()
{

    $pagina = 'bancarias';


    $pdo = $this->connect(); 
    $query = $pdo->query("SELECT * from bancarias order by id desc ");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
 return $res;
}

public function selecaoBanco()
{
    $pdo = $this->connect(); 
    $query = $pdo->query("SELECT * FROM bancos order by nome asc");
    $res = $query->fetchAll(\PDO::FETCH_ASSOC);

    return $res;

}

public function excluir()
{
    $pagina = 'bancarias';
    $id = filter_input(INPUT_POST, 'id-excluir', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pdo = $this->connect();
    $pdo->query("DELETE from $pagina where id = '$id'");
   echo 'Excluído com Sucesso';
   return $pdo;

}

public function gerar()
{

    // Limpar o buffer
ob_start();

    $pagina = 'bancarias'; 
    $pdo = $this->connect();
    $query = ("SELECT id, banco, agencia, conta, tipo, pessoa, doc, saldo FROM $pagina ORDER BY id ");
    $res = $pdo->prepare($query);
    $res->execute();

    // Acessa o IF quando encontrar registro no banco de dados

    if(($res) and ($res->rowCount() != 0)) 
    {

// Aceitar csv ou texto 
header('Content-Type: text/csv; charset=utf-8');

// Nome arquivo
header('Content-Disposition: attachment; filename=arquivo.csv');

// Gravar no buffer
$resultado = fopen("php://output", 'w');

// Criar o cabeçalho do Excel - Usar a função mb_convert_encoding para converter carateres especiais
$cabecalho = ['id', 'Banco', 'Agência', 'Conta', 'Pessoa', 'Doc', 'Saldo', mb_convert_encoding('Endereço', 'ISO-8859-1', 'UTF-8')];

// Escrever o cabeçalho no arquivo
fputcsv($resultado, $cabecalho, ';');

// Ler os registros retornado do banco de dados
while($row_bancarias = $res->fetch(\PDO::FETCH_ASSOC)){

    // Escrever o conteúdo no arquivo
    fputcsv($resultado, $row_bancarias, ';');

}

// Fechar arquivo
//fclose($resultado);
}else{ // Acessa O ELSE quando não encontrar nenhum registro no BD
$_SESSION['nome_usu'] = "<p style='color: #f00;'>Erro: Nenhum usuário encontrado!</p>";
header("Location: index.php");


    }

    return $res;

}

public function conectar()
{

    $pdo = $this->connect();

    return $pdo;
}



}