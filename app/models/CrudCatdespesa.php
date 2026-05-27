<?php

namespace app\models;

class CrudCatdespesa extends Connection
{

    public function listaCatDesp()
    {
        $pagina = 'cat_despesas';
        $pdo = $this->connect();
        $query = $pdo->query("SELECT * from $pagina order by id desc ");
        $res = $query->fetchAll(\PDO::FETCH_ASSOC);
          
        		
        
        return $res;
    

    }

   public function listaDesp()
    {

        $pagina = 'cat_despesas';
        $pdo = $this->connect();
        $query = $pdo->query("SELECT * from $pagina order by id desc ");
        $res = $query->fetchAll(\PDO::FETCH_ASSOC);
        for($i=0; $i < @count($res); $i++){
            foreach ($res[$i] as $key => $value){} 
        
                $id = $res[$i]['id'];
                
                
                $query2 = $pdo->query("SELECT * from despesas where cat_despesa = '$id'");
                $res2 = $query2->fetchAll(\PDO::FETCH_ASSOC);
                $total_despesas = @count($res2);
        
    }
    return $total_despesas;
}

    public function inserir()
    {
        $pagina = 'cat_despesas';
        //VARIAVEIS DOS INPUTS
        $campo1 = 'Nome';
        $campo2 = 'Grupo';
        

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
            $query = $pdo->prepare("INSERT INTO $pagina set nome = :campo1, grupo = :campo2");
        }else{
            $query = $pdo->prepare("UPDATE $pagina set nome = :campo1, grupo = :campo2 WHERE id = '$id'");
        }
        
        $query->bindValue(":campo1", "$cp1");
        $query->bindValue(":campo2", "$cp2");
        $query->execute();
        
        echo 'Salvo com Sucesso';
        return $query;

    }

    public function excluir()
    {
        $pagina = 'cat_despesas';
        //VARIAVEIS DOS INPUTS
        $campo1 = 'Nome';
        

               
$id = filter_input(INPUT_POST, 'id-excluir', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$pdo = $this->connect();

$query = $pdo->query("SELECT * from despesas where cat_despesa = '$id'");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
$total_reg = @count($res);

if($total_reg == 0){
$pdo->query("DELETE from $pagina where id = '$id'");
echo 'Excluído com Sucesso';
}else{
	echo 'Esta categoria possui despesas associadas a ela, primeiro exclua estas despesas e depois exclua a categoria!';
}

return $pdo;

    }

    public function conectar()
{

    $pdo = $this->connect();

    return $pdo;
}


    }
    
?>