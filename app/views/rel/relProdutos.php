<?php
use app\models\CrudProdutos;


?>
<!DOCTYPE html>
<html>
<head>
	<title>Catálogo de Produtos</title>
	<link rel="shortcut icon" href="http://localhost/sistemaFinanceiro/config/img/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="/sistemaFinanceiro/config/css/relatorio.css"/>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	
</head>
<body>


	<div class="cabecalho">
			<div class="row titulos">
				<div class="col-sm-2 esquerda_float image">	
					<img src="<?php echo $url_sistema ?>config/img/caixas.jpg" width="90px">
				</div>
				<div class="col-sm-10 esquerda_float">	
					<h2 class="titulo"><b><?php echo strtoupper($nome_sistema) ?></b></h2>
					
					<div class="areaSubtituloCab">
					<h6 class="subtitulo"><?php echo $endereco_sistema . ' Tel: '.$telefone_sistema  ?></h6>

					<p class="subtitulo"><?php echo $data_hoje ?></p>
					</div>

				</div>
			</div>
	</div>

	

	<div class="container">

			<div align="center" class="">	
				<span class="titulorel">Catálogo de Produtos </span>
			</div>
			

	<hr>	

<table class='table' width='100%'  cellspacing='0' cellpadding='3'>
			<tr bgcolor='#f9f9f9' >
				<th>Nome</th>
                <th>Descrição</th>
				<th>Estoque</th>
				<th>Valor Venda</th>
				<th>Valor Compra</th>
				<th>Foto</th>
				

			</tr>
			<?php 
						
			$lista = new CrudProdutos;
            $res = $lista->listarProd();
					$totalItens = @count($res);
					
					for ($i=0; $i < @count($res); $i++) { 
						foreach ($res[$i] as $key => $value) {
						}
						$nome = $res[$i]['nome'];
                        $desc = $res[$i]['descricao'];
                        $estoque = $res[$i]['estoque'];
						$valor_compra = $res[$i]['valor_compra'];
						$valor_venda = $res[$i]['valor_venda'];
						
						
						$foto = $res[$i]['foto'];
								
						
						$id = $res[$i]['id'];

						
						$valor_compra = number_format($valor_compra, 2, ',', '.');
						$valor_venda = number_format($valor_venda, 2, ',', '.');
				?>

				<tr>
					
					<td><?php echo $nome ?> </td>
                    <td><?php echo $desc ?> </td>
					<td><?php echo $estoque ?> </td>
					<td>R$ <?php echo $valor_venda ?> </td>
					<td>R$ <?php echo $valor_compra ?> </td>
					<td><img src="<?php echo $url_sistema ?>config/img/produtos/<?php echo $foto ?>" width="35px"> </td>
					

				</tr>
			<?php } ?>



		</table>

		<hr>
		<div class="row margem-superior">
			<div class="col-md-12">
				<div class="" align="right">
								
					<span class=""> <b> Total de Produtos :  <?php echo $totalItens ?> </b> </span>
				</div>

			</div>
		</div>

		<hr>
		

	</div>
	<div>
		
		
	
	</div>



</body>

 </html>
