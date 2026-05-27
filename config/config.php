<?php
$nivel_minimo_estoque = 10;


setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today')));

//VARIAVEIS GLOBAIS
$nome_sistema = "SISTEMA FINANCEIRO";

$url_sistema = "http://localhost/sistemaFinanceiro/" ;//é preciso configurar essa url para gerar os relatorios, ela deve apontar para a raiz do seu dominio (https://www.google.com/) com a barra no final e o protocolo http ou https de acordo com seu dominio no inicio.

$telefone_sistema = "(11) 95650-5900";
$endereco_sistema = "Rua Gruarantá 597 ";
//$rodape_relatorios = "Sistema Desenvolvido por Reginaldo Roberto Ribeiro!";


?>