<?php

namespace app\models;

class CrudCaixa extends Connection
{
    public function inserir()
    {
@session_start();
$cp3 = $_SESSION['id'];
$nome_usu = $_SESSION['nome_usu'];
@$nivel_usu = $_SESSION['nivel'];
$pagina = 'caixa';

//VARIAVEIS DOS INPUTS
$campo2 = 'valor_ab';
$campo9 = 'Inicial';



$cp2 = filter_input(INPUT_POST, $campo2, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp2 = str_replace(',', '.', $cp2);
$cp9 = filter_input(INPUT_POST, $campo9, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp9 = str_replace(',', '.', $cp9);

        $pdo = $this->connect();

        if($cp2 == ""){
            $cp2 = 0;
        }
        
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
       // Comando IF: Verifica se o camo id  esta fazio  caso sim efetua um insert na tabela "caixa"
       // Caso o campo id esteja diferente de vazio executa um updata na tabel "caixa"
       
        if($id == ""){
        
            $query2 = $pdo->query("SELECT * FROM $pagina WHERE status = 'Aberto'");
            $res2 = $query2->fetchAll(\PDO::FETCH_ASSOC);
            if(@count($res2) > 0){
                echo 'Você precisa antes fechar o caixa aberto para abrir outro!';
                exit();
            }
        
            $query = $pdo->prepare("INSERT INTO $pagina set data_ab = curDate(), saldo_inicial = :campo9, 
            usuario_ab = :campo3, status = 'Aberto', nome = 'Caixa'");
            $query->bindValue(":campo3", "$cp3");
            $query->bindValue(":campo9", "$cp9");
         
            
            $query->execute();
        }else{
        
            $query2 = $pdo->query("SELECT * FROM $pagina WHERE id = '$id'");
            $res2 = $query2->fetchAll(\PDO::FETCH_ASSOC);
            $id_usu = $res2[0]['usuario_ab'];
         
           //Verifica se o usuario se o usuario abriu a conta  se sim, efetua a alteração
           //Caso não seja o usuário que abiu a conta não permite a alteração
            if($id_usu == $nome_usu || $nivel_usu == 'Administrador'){
                $query = $pdo->prepare("UPDATE $pagina set saldo_inicial = :campo9 WHERE id = '$id'");
                $query->bindValue(":campo9", "$cp9");
                
                $query->execute();
            }else{
                echo 'Somente o usuário que abriu o caixa pode mudar o valor da abertura!';
                exit();
            }
        
            
        }
        
        
        echo 'Salvo com Sucesso';
        return $query;
    }

    public function excluir()
    {
        $pagina = 'caixa';

        @session_start();
        $nivel_usu = $_SESSION['nivel'];
        
        $id = filter_input(INPUT_POST, 'id-excluir', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $usuario_adm = filter_input(INPUT_POST, 'usuarios_adm', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $senha_adm = filter_input(INPUT_POST, 'senha_adm', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
       
        $pdo = $this->connect();

        
        // Valida senha administrativa pelo helper central, compatibilizando hash novo e legado.
        $senha_admin_valida = $this->validarSenhaAdministrador($usuario_adm, $senha_adm);
        // valida se o usuário é do grupo administrador, se sim exclui a conta.
        // Caso não, o sistema não permite a exclusão da conta

        if($senha_admin_valida || $nivel_usu == 'Administrador'){
        $pdo->query("DELETE from $pagina where id = '$id'");
        echo 'Excluído com Sucesso';
        }else{
            echo 'Dados Incorretos ou o usuário não é um Administrador!';
        }
         
        return $pdo;

    }

    public function fecharcaixa()
    {
        $pagina = 'caixa';
        @session_start();
        $id_usuario = @$_SESSION['id'];
        $id = filter_input(INPUT_POST, 'id-fechar', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
         
        $pdo = $this->connect();

      

        $pdo->query("UPDATE $pagina SET data_fec = curDate(), status = 'Fechado', usuario_fec = '$id_usuario' where id = '$id'");
        echo 'Fechado com Sucesso';
        
      
       return $pdo;
    }

    public function listarCaixa()
    {
        $pagina = 'caixa';
        $pdo = $this->connect();

        $query = $pdo->query("SELECT * from $pagina order by id desc ");
        $res = $query->fetchAll(\PDO::FETCH_ASSOC);

        return $res;

    }

    public function conectar()
{

    $pdo = $this->connect();

    return $pdo;
}

public function listarcharcaixa()
    {
        $pagina = 'caixa';
        $pdo = $this->connect();

        $query = $pdo->query("SELECT * from $pagina Where status = 'Aberto' or status = 'Fechado' order by data_ab desc ");
        $res = $query->fetchAll(\PDO::FETCH_ASSOC);

        return $res;

    }



}
