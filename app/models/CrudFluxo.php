<?php

namespace app\models;
use app\models\Connection;

class CrudFluxo extends Connection
{

    public function listarFluxo()

    {

        //@session_start();

$dataInicial = filter_input(INPUT_POST, 'dataInicial', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$dataFinal =  filter_input(INPUT_POST, 'dataFinal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$tipo = '%'.filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_FULL_SPECIAL_CHARS).'%';
$alterou_data = filter_input(INPUT_POST, 'alterou_data', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$vencidas =  filter_input(INPUT_POST, 'vencidas', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$hoje =  filter_input(INPUT_POST, 'hoje', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$amanha = filter_input(INPUT_POST, 'amanha', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$data_hoje = date('Y-m-d');
$data_amanha = date('Y/m/d', strtotime("+1 days",strtotime($data_hoje)));

//var_dump($dataInicial);
//var_dump($dataFinal);
//echo $data_hoje;
//var_dump($tipo);
        
       
        $pdo = $this->connect();

        if($alterou_data == 'Sim'){
            if($dataInicial != "" || $dataFinal != ""){
            $query = $pdo->query("SELECT 
            M.id, M.E, M.S, M.tipo, M.valor,  
            M.usuario, M.data, D.nome_desp, 
            M.documento, M.caixa_periodo, M.lancamento,
            M.conta_pag, M.mov_contas, M.conta_rec,  
            U.nome_usu, F.nome_fpg  FROM movimentacoes as M
              
             INNER JOIN usuarios AS U ON U.id = M.usuario
             INNER JOIN formas_pgtos AS F ON F.id = M.documento
             INNER JOIN despesas AS D ON D.id = M.plano_conta 
             WHERE (data >= '$dataInicial' and data <= '$dataFinal') AND tipo LIKE '$tipo'  order by id 
            ");
            
            

            }else if($tipo != '%%' and $alterou_data == ''){
                    $query = $pdo->query(  " SELECT 
                    M.id, M.E, M.S, M.tipo, M.valor,  
                    M.usuario, M.data, D.nome_desp, 
                    M.documento, M.caixa_periodo, M.lancamento,
                    M.conta_pag, M.mov_contas, M.conta_rec,  
                    U.nome_usu, F.nome_fpg  FROM movimentacoes as M
                      
                     INNER JOIN usuarios AS U ON U.id = M.usuario
                     INNER JOIN formas_pgtos AS F ON F.id = M.documento
                     INNER JOIN despesas AS D ON D.id = M.plano_conta 
                     where tipo LIKE '$tipo'  order by id ");
                    
                  
        
                    }

                    

            }else{

                $query = $pdo->query(" SELECT 
                M.id, M.E, M.S, M.tipo, M.valor,  
                M.usuario, M.data, D.nome_desp, 
                M.documento, M.caixa_periodo, M.lancamento,
                M.conta_pag, M.mov_contas, M.conta_rec,  
                U.nome_usu, F.nome_fpg  FROM movimentacoes as M
                  
                 INNER JOIN usuarios AS U ON U.id = M.usuario
                 INNER JOIN formas_pgtos AS F ON F.id = M.documento
                 INNER JOIN despesas AS D ON D.id = M.plano_conta 
                 where tipo LIKE '$tipo'  order by id");
           

            }
             
            
        
            @$res = $query->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        
        
        
        
        }

 public function gerarExcel()
        {

$dataInicial = filter_input(INPUT_POST, 'dataInicial', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$dataFinal =  filter_input(INPUT_POST, 'dataFinal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	//$dataInicial = $_GET['dataInicial'];
	//$dataFinal = $_GET['dataFinal'];

	//var_dump($dataInicial);
    //var_dump($dataFinal);

	$pdo = $this->connect();
	$query = $pdo->prepare("SELECT
    M.id, M.E, M.S, M.tipo, M.valor, 
    M.usuario, M.data, D.nome_desp, 
    F.nome_fpg, M.caixa_periodo, M.lancamento,
    M.conta_pag, M.mov_contas, M.conta_rec,  
    U.nome_usu, F.nome_fpg  FROM movimentacoes as M
      
     INNER JOIN usuarios AS U ON U.id = M.usuario
     INNER JOIN formas_pgtos AS F ON F.id = M.documento 
     INNER JOIN despesas AS D ON D.id = M.plano_conta 
     WHERE data >= :dataInicial and data <= :dataFinal");
        
        $query->bindValue(":dataInicial", "$dataInicial");
        $query->bindValue(":dataFinal", "$dataFinal");
        $query->execute();
        


        
	return $query;
		
}
       
    
function  converter(&$dados){
    // Converter dados de UTF-8 para ISO-8859-1
    $dados = mb_convert_encoding($dados, 'ISO-8859-1', 'UTF-8');
//var_dump($row_fluxo, );
   
} 


public function geraPdf()
{
	$pagina = 'movimentacoes';
	@$dataInicial = $_GET['dataInicial'];
	@$dataFinal = $_GET['dataFinal'];

	@$dataInicial = $_GET['dataInicial'];
   // var_dump($dataInicial);
	//var_dump($dataInicial);

	$pdo = $this->connect();
	$query = $pdo->prepare("SELECT
    M.id, M.E, M.S, M.tipo, M.valor, 
    M.usuario, M.data, D.nome_desp, 
    F.nome_fpg, M.caixa_periodo, M.lancamento,
    M.conta_pag, M.mov_contas, M.conta_rec,  
    U.nome_usu, F.nome_fpg  FROM $pagina as M
      
     INNER JOIN usuarios AS U ON U.id = M.usuario
     INNER JOIN formas_pgtos AS F ON F.id = M.documento 
     INNER JOIN despesas AS D ON D.id = M.plano_conta
    where data >= :dataInicial 
    AND data <= :dataFinal");
        $query->bindValue(":dataInicial", "$dataInicial");
        $query->bindValue(":dataFinal", "$dataFinal");
        $query->execute();
        $res = $query->fetchAll(\PDO::FETCH_ASSOC);
		
    //var_dump($res);
		
	return $res;



}

    
   
}  