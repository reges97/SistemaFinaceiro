<?php

namespace app\models;

class CrudDiario extends Connection
{

    public function inserir()
    {



@session_start();
$cp1 = $_SESSION['id'];
$nome_usu = $_SESSION['nome_usu'];
@$nivel_usu = $_SESSION['nivel'];
$pagina = 'fluxo_diario';



       $pdo = $this->connect();

       $query = $pdo->query("SELECT * FROM $pagina WHERE data_flux_ab = curDate()");

       $query2 = $pdo->query("SELECT sum(saldo) AS total FROM bancarias");
        $res1 = $query2->fetchAll(\PDO::FETCH_ASSOC);
        $b1 = $res1[0]['total'];
           
      
       

       $res = $query->fetchAll(\PDO::FETCH_ASSOC);
       
       if(@count($res) > 0){

        
        $query = $pdo->query("SELECT sum(debito) AS entrada, sum(credito) AS saida, 
        sum(valor) AS total FROM saldo_conta WHERE data = curDate()");

        $res = $query->fetchAll(\PDO::FETCH_ASSOC);
        
           
           $cp2 = $res[0]['entrada'];
           $cp3 = $res[0]['saida'];
           $cp4 = $res[0]['total'];
           
          
           if($cp2 != null || $cp3 != null){

           

           //$saldof = $b1 + $cp2 - $cp3;

           $query = $pdo->prepare("UPDATE $pagina set 
          E = '$cp2', S = '$cp3',
         status = 'F', saldo_final = '$b1'
         WHERE data_flux_ab = curDate()");
   
         
         $query->execute();
           
         
             
        } else if($cp2 == null and $cp3 == null){
        
          $data_atual = date('Y-m-d');

           //var_dump($cp3);
           //var_dump($cp4);

          $query = $pdo->prepare("UPDATE $pagina set 
          status = 'F', saldo_final = '$b1'
          WHERE data_flux_ab = curDate()");
                           
           $query->execute();
          
                  }
          

             }else{

           
              
              $query = $pdo->prepare("INSERT INTO $pagina set data_flux_ab = curDate(), 
        saldo_ini = '$b1'");

          $query->execute();

    echo 'Salvo com Sucesso';
    return $query;



             }

              

             
             
       
        

       
         
                
        
       
    }


    public function listarDiario()
    {
    
        $pagina = 'fluxo_diario';
    
          $pdo = $this->connect(); 
        $query = $pdo->query("SELECT * from $pagina ");
    $res = $query->fetchAll(\PDO::FETCH_ASSOC);
     return $res;
    }


    
}
