<?php

use app\controllers\ControleCaixa;
use app\controllers\Fluxo;

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
				<span class="titulorel">Relatório Fluxo Caixa </span>
			</div>
			<hr>	


<table id="example" class="table table-striped table-light table-hover my-4">
			
<tr bgcolor='#f9f9f9' >
				<th>M.id</th>
                <th>M.E</th>
				<th>M.S</th>
				<th>M.tipo</th>
				<th>M.valor</th>
				<th>M.usuario</th>
				<th>M.data</th>
				<th>M.plano_conta</th>
				<th>M.documento</th>
				
				</tr>
			
            <?php 
						
			$lista = new Fluxo;
            $res = $lista->geraPdf();
					$totalItens = @count($res);
					
					for ($i=0; $i < @count($res); $i++) { 
						foreach ($res[$i] as $key => $value) {
						}
						$cp1 = $res[$i]['id'];
                        $cp2 = $res[$i]['E'];
                        $cp3 = $res[$i]['S'];
						$cp4 = $res[$i]['tipo'];
						$cp5 = $res[$i]['valor'];
						$cp6 = $res[$i]['nome_usu'];
						$cp7 = $res[$i]['data'];
						$cp8 = $res[$i]['nome_desp'];
						$cp9 = $res[$i]['nome_fpg'];
						
									

						
						//$cp3 = number_format($cp3, 2, ',', '.');
						//$cp4 = number_format($cp4, 2, ',', '.');
                        $cp5 = number_format($cp5, 2, ',', '.');

                       
				?>

				<tr>
					
					<td><?php echo $cp1 ?> </td>
                    <td><?php echo $cp2 ?> </td>
					<td><?php echo $cp3 ?> </td>
					<td><?php echo $cp4 ?> </td>
					<td><?php echo $cp5 ?> </td>
					<td><?php echo $cp6 ?> </td>
					<td><?php echo $cp7 ?> </td>
					<td><?php echo $cp8?> </td>
					<td><?php echo $cp9?> </td>
					

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
	</small>
	
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
