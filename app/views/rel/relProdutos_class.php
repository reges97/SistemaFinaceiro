<?php


//VARIAVEIS DE CONFIGURAÇÕES DO SISTEMA

$relatorio_pdf = 'Sim'; //Se você utilizar sim ele vai gerar os relatórios utilizando a biblioteca do dompdf configurada para o PHP 8.0, se você utilizar outra versão do PHP ou do DOMPDF pode ser que ele não gere o relatório da forma correta, caso você utilize não ele vai gerar o relatório html.


@$pagina = 'http://localhost/sistemaFinanceiro/';
//ALIMENTAR OS DADOS NO RELATÓRIO
$html = file_get_contents($pagina."?router=Prod/relProdutos");

if($relatorio_pdf != 'Sim'){
	echo $html;
	exit();
}

//CARREGAR DOMPDF
use Dompdf\Dompdf;
use Dompdf\Options;


//INICIALIZAR A CLASSE DO DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);

$pdf = new DOMPDF($options);

//Definir o tamanho do papel e orientação da página
$pdf->setpaper('A4', 'portrait'); //caso queira a folha em paisagem use landscape em vez de portrait

//CARREGAR O CONTEÚDO HTML
$pdf->loadhtml($html);

//RENDERIZAR O PDF
$pdf->render();

//NOMEAR O PDF GERADO
$pdf->stream(
'produtos.pdf',
array("Attachment" => false)
);