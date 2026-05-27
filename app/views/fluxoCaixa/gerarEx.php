<?php

use app\models\CrudFluxo;

session_start(); // Iniciar a sessão

// Limpar o buffer
ob_start();


$stmt = new CrudFluxo;

$query = $stmt->gerarExcel();



  // Verificar se encontrou algum registro no banco de dados com PHP e acessar o IF
  if (($query) and ($query->rowCount() != 0)) {

    // Aceitar csv ou texto para gerar Excel
    header('Content-Type: text/csv; charset=utf-8');

    // Nome do arquivo para gerar Excel
    header('Content-Disposition: attachment; filename=arquivo.csv');

    // Gravar no buffer
    $resultado = fopen("php://output", 'w');

    // Criar o cabeçalho do Excel - Usar a função mb_convert_encoding para converter carateres especiais
    $cabecalho = ['M.id', 'M.E', 'M.S', 'M.tipo', 'M.valor',  'M.usuario', 'M.data', 
    'M.plano_conta', 'M.documento', 'M.caixa_periodo', 'M.lancamento',
    'M.conta_pag', 'M.mov_contas', 'M.conta_rec',  'U.nome_usu', 'F.nome_fpg', mb_convert_encoding('Endereço', 'ISO-8859-1', 'UTF-8')];

    // Escrever o cabeçalho no arquivo
    fputcsv($resultado, $cabecalho, ';');

    // Ler os registros que vem do banco de dados
    while ($row_fluxo = $query->fetch(\PDO::FETCH_ASSOC)) {
   
      

     
      // Como usar array_walk_recursive para criar função recursiva com PHP
       array_walk_recursive($row_fluxo, 'Converter');

       
        
        // Escrever o conteúdo no arquivo
        fputcsv($resultado, $row_fluxo, ';');
    }
// Como criar função valor por referência, isto é, quando alter o valor dentro da função, vale para a variável fora da função.

    // Fechar arquivo
    fclose($resultado);
} else {
    echo "<p style='color: #f00;'>Erro: Nenhum registro encontrado!</p>";

}

    
function  converter(&$dados){
    // Converter dados de UTF-8 para ISO-8859-1
    $dados = mb_convert_encoding($dados, 'ISO-8859-1', 'UTF-8');
//var_dump($row_fluxo, );
   
} 




