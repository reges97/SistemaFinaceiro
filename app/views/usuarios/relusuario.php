<?php 
use app\models\CrudUsuarios;

$pagina = '?router=User';
//VARIAVEIS DOS INPUTS
$campo1 = 'Nome';
$campo2 = 'Email';
$campo3 = 'Senha';
$campo4 = 'Nivel';


?>
<div class="row my-3">
	<div class="col-md-12 container-fluid mb-4 mx-4">
<div class="col-md-12 my-3">
	<a href="#" onclick="inserir()" type="button" class="btn btn-dark btn-sm">Novo Usuário</a>
</div>


<small>
	<div class="tabela bg-light" id="lista">

	</div>
</small>
	</div>
</div>

<script type="text/javascript">var pag ="<?=$pagina?>" </script>
<script src="config/js/ajax.js"></script>

<script>
function relusuex(){
		var pag = "<?=$pagina?>";
		$.ajax({
			url: pag + "/relusuex",
			method: 'POST',
			data: $('#form').serialize(),
			dataType: "html",

			success:function(result){
				$("#relusuex").html(result);
			}
		});
	}

	function geraRelusu(){
		var pag = "<?=$pagina?>";
		$.ajax({
			url: pag + "/geraRelusu",
			method: 'POST',
			data: $('#form').serialize(),
			dataType: "html",

			success:function(result){
				$("#geraRelusu").html(result);
			}
		});
	}
</script>
