<?php
use app\models\CrudSaldos;

$campo1 = 'conta';
$campo2 = 'Tipo Conta';
$campo3 = 'saldo';
$campo4 = 'usuario';
$campo5 = 'Tipo';
$campo6 = 'data';
$campo7 = 'Plando/contas';
$campo8 = 'valor';
$campo9 = 'Conta';
$campo10 = 'Pagar/Receber';

@$valorTtalEntrada  = 0.0;
@$valorTotalSaida = 0.0;
$total = 0.0;

?>
<!DOCTYPE html>
<html>
<head>
	<title>Catálogo de Produtos</title>
	<link rel="shortcut icon" href="http://localhost/sistemaFinanceiro/config/img/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="/sistemaFinanceiro/config/css/relatorio.css"/>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	
</head>
<body align ="center">


	<div class="cabecalho">
			<div class="row titulos">
				<div class="col-sm-2 esquerda_float image">	
					<img src="<?php echo $url_sistema ?>config/img/logo.jpg" width="90px">
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

	

	<div class="container" align ="center">

			<div align ="center" class="">	
				<span class="titulorel">Catálogo de Saldo </span>
			</div>
			

	<hr>	

<table class='table' width='100%'  cellspacing='1' cellpadding='1'>
			<tr bgcolor='#f9f9f9' >
           
            <th><?php echo $campo1?></th>
            <th><?php echo $campo2?></th>
            <th><?php echo $campo3?></th>	
            <th><?php echo $campo4?></th>	
            <th><?php echo $campo5?></th>	
            <th><?php echo $campo6?></th>	
            <th><?php echo $campo7?></th>	
            <th><?php echo $campo8?></th>	
            	

        	</tr>
          
			<?php 
						
			$lista = new CrudSaldos;
            $res = $lista->rel();
            
					$totalItens = @count($res);
					
					for ($i=0; $i < @count($res); $i++) { 
						foreach ($res[$i] as $key => $value) {
						}
						$id = $res[$i]['id'];
                        $cp1 = $res[$i]['conta'];
                        $cp2 = $res[$i]['tipo_conta'];
                        $cp3 = $res[$i]['saldo'];
                        $cp4 = $res[$i]['usuario'];
                        $cp5 = $res[$i]['tipo'];
                        $cp6 = $res[$i]['data'];
                        $cp7 = $res[$i]['plano_conta'];
                        $cp8 = $res[$i]['valor'];
                        $cp9 = $res[$i]['mov'];
                        $cp10 = $res[$i]['pagar_receber'];

                        $data_mov = implode('/', array_reverse(explode('-', $cp6)));
		                $valor = number_format($cp8, 2, ',', '.');
                     

                      
				?>

				<tr>
					
               <td><?php echo $cp1?></td>		
                <td><?php echo $cp2?></td>	
                <td><?php echo $cp3?></td>	
                <td><?php echo $cp4?></td>	
                <td><?php echo $cp5?></td>	
                <td><?php echo $data_mov?></td>	
                <td><?php echo $cp7?></td>	
                <td><?php echo $valor?></td>	
                
                </tr>
			<?php } ?>

		</table>

		<hr>
		<div class="row margem-superior">
			<div class="col-md-12">
				<div class="" align="right">
								
					<span class=""> <b> Total de Produtos : <?php echo $totalItens?> </b> </span>
				</div>

			</div>
		</div>

		<hr>
		

	</div>
	<div>
		
		
	
	</div>



</body>

 </html>
