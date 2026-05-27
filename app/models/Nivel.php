<?php

namespace app\models;

class Nivel extends Connection
{


public function inserir()

{
    $nivel = filter_input(INPUT_POST, 'nivel',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id = filter_input(INPUT_POST, 'id',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    $pdo = $this->connect();
    //VALIDAR CAMPO
    $query = $pdo->query("SELECT * from niveis where nivel = '$nivel'");
    $res = $query->fetchAll(\PDO::FETCH_ASSOC);
    $total_reg = @count($res);
    $id_reg = @$res[0]['id'];
    if($total_reg > 0 and $id_reg != $id){
        echo 'Este nível já está cadastrado!!';
        exit();
    }
    
    if($id == ""){
        $query = $pdo->prepare("INSERT INTO niveis set nivel = :nivel");
    }else{
        $query = $pdo->prepare("UPDATE niveis set nivel = :nivel WHERE id = '$id'");
    }
    
    $query->bindValue(":nivel", "$nivel");
    $query->execute();
    
    echo 'Salvo com Sucesso';

    return $query;

}

    public function listarNiv()
    {
        $pdo = $this->connect(); 
        $query = $pdo->query("SELECT * from niveis order by id desc ");

        $res = $query->fetchAll(\PDO::FETCH_ASSOC);

       return $res;

    }

  public function excluir()  
  {

    $id = filter_input(INPUT_POST, 'id-excluir',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pdo = $this->connect();
    
    
$query = $pdo->query("SELECT * from niveis where id = '$id'");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
$nivel = @$res[0]['nivel'];

$query = $pdo->query("SELECT * from usuarios where nivel = '$nivel'");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
$total_reg = @count($res);

if($total_reg == 0){
	$pdo->query("DELETE from niveis where id = '$id'");
	echo 'Excluído com Sucesso';
}else{
	echo 'Este nível possui usuários associados a ele, primeiro exclua estes usuários e depois exclua o nível!';
}
  return $res;

  }

  public function listaMenu()
  {
      $pdo = $this->connect();

      $query = $pdo->query("SELECT * from menu order by id_menu");
      $res = $query->fetchAll(\PDO::FETCH_ASSOC);

       return $res;

  }

    
}