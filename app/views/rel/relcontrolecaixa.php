<?php

use app\controllers\ControleCaixa;

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
					<img src="<?php echo $url_sistema ?>config/img/extratocaixa.jpg" width="90px">
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
				<span class="titulorel">Extrato Caixa </span>
			</div>
			<hr>	

<table id="example" class="table table-striped table-light table-hover my-4">
			<tr bgcolor='#f9f9f9' >
				<th>Data</th>
                <th>Movimentação</th>
				<th>Entrada</th>
				<th>Saida</th>
				<th>Saldo</th>
				</tr>
			
            <?php 
						
			$lista = new ControleCaixa;
            $res = $lista->relControle2();
					$totalItens = @count($res);
					
					for ($i=0; $i < @count($res); $i++) { 
						foreach ($res[$i] as $key => $value) {
						}
						$cp1 = $res[$i]['data'];
                        $cp2 = $res[$i]['movimento'];
                        $cp3 = $res[$i]['entrada'];
						$cp4 = $res[$i]['saida'];
						$cp5 = $res[$i]['saldo'];
									

						
						$cp3 = number_format($cp3, 2, ',', '.');
						$cp4 = number_format($cp4, 2, ',', '.');
                        $cp5 = number_format($cp5, 2, ',', '.');
				?>

				<tr>
					
					<td><?php echo $cp1 ?> </td>
                    <td><?php echo $cp2 ?> </td>
					<td><?php echo $cp3 ?> </td>
					<td>R$ <?php echo $cp4 ?> </td>
					<td>R$ <?php echo $cp5 ?> </td>
					
					

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


 <script>
 $(document).ready(function() {    
	$('#example').DataTable({
		"ordering": false
	});

} );

</script>


