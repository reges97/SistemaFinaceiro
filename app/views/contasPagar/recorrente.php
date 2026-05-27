<?php
use app\controllers\ContasPagar;
 //$pagina = 'contas_pagar';
 //VARIAVEIS DOS INPUTS
 
 
     //ROTINA PARA VERIFICAR COBRANÇAS RECORRENTES
 $data_atual = date('Y-m-d');
 $dia = date('d');
 $mes = date('m');
 $ano = date('Y');
 $con = new ContasPagar;
 
 $pdo = $con->conectar(); 
 $query = $pdo->query("SELECT * from contas_pagar order by id desc ");
 $res = $query->fetchAll(\PDO::FETCH_ASSOC);
 for($i=0; $i < @count($res); $i++){
     foreach ($res[$i] as $key => $value){} 
     
     $id = $res[$i]['id'];
         $cp1 = $res[$i]['descricao'];
         $cp2 = $res[$i]['cliente'];
         $cp3 = $res[$i]['saida'];
         $cp4 = $res[$i]['documento'];
         $cp5 = $res[$i]['plano_conta'];
         $cp6 = $res[$i]['data_emissao'];
         $cp7 = $res[$i]['vencimento'];
         $cp8 = $res[$i]['frequencia'];
         $cp9 = $res[$i]['valor'];
         $cp10 = $res[$i]['usuario_lanc'];
         $cp11 = $res[$i]['usuario_baixa'];
         
         $cp13 = $res[$i]['status'];
         @$cp14 = $res[$i]['data_recor'];
         $cp15 = $res[$i] ['jurosporc'];
         $cp16 = $res[$i] ['multaporc'];
         $cp17 = $res[$i] ['descontoporc'];
 
     $recor_str = explode("-",$cp14);
         
     $dia_recor = @$recor_str[2];

     //var_dump($cp8);
  
     
 $frequencia = $res[$i]['frequencia'];
 $query1 = $pdo->query("SELECT * from frequencias where nome = '$frequencia' ");
 $res1 = $query1->fetchAll(\PDO::FETCH_ASSOC);
 $dias_frequencia = $res1[0]['dias'];

 
 
 if($dias_frequencia == 30 || $dias_frequencia == 31){
 
     $data_recor = date('Y/m/d', strtotime("+1 month",strtotime($data_atual)));
     $nova_data_vencimento = date('Y/m/d', strtotime("+1 month",strtotime($cp7)));
 
 }else if($dias_frequencia == 90){ 
 
     $data_recor = date('Y/m/d', strtotime("+3 month",strtotime($data_atual)));
     $nova_data_vencimento = date('Y/m/d', strtotime("+3 month",strtotime($cp7)));
 
 }else if($dias_frequencia == 180){ 
 
     $data_recor = date('Y/m/d', strtotime("+6 month",strtotime($data_atual)));
     $nova_data_vencimento = date('Y/m/d', strtotime("+6 month",strtotime($cp7)));
 
 }else if($dias_frequencia == 360){ 
 
     $data_recor = date('Y/m/d', strtotime("+1 year",strtotime($data_atual)));
     $nova_data_vencimento = date('Y/m/d', strtotime("+1 year",strtotime($cp7)));
 
 }else{

     $data_recor = date('Y/m/d', strtotime("+$dias_frequencia days",strtotime($data_atual)));
     $nova_data_vencimento = date('Y/m/d', strtotime("+$dias_frequencia days",strtotime($cp7))); 
 }
 
 
     if($dias_frequencia > 0){
         if($dia_recor == $dia){
       
 
             $pdo->query("INSERT INTO contas_pagar set descricao = '$cp1', cliente = '$cp2', saida = '$cp3', documento = '$cp4', plano_conta = '$cp5', data_emissao = curDate(), vencimento = '$nova_data_vencimento', frequencia = '$cp8', valor = '$cp9', usuario_lanc = '$cp10', status = 'Pendente',
             juros = '0', multa = '0', desconto = '0', data_recor = '$data_recor'");
             $id_ult_registro = $pdo->lastInsertId();
           
             
             $pdo->query("UPDATE contas_pagar set data_recor = NULL where id='$id'");
 
 
             
                 if($data_atual == $cp6){
                     $pdo->query("DELETE FROM contas_pagar where id='$id_ult_registro'");
                     $pdo->query("UPDATE contas_pagar SET data_recor = '$data_recor' where id='$id'");
                 }
                       
                
         }
        
        
     }

     
 }


 
 
 ?>