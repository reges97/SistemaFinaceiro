<?php

namespace app\models;

class CrudProdutos extends Connection
{
  public function listarProd()
  {
    
    @session_start();

    $pagina = 'produtos';

    $nivel_minimo_estoque = 10;

    $pdo = $this->connect();

    if(@$_SESSION['estoque']=='sim'){
        $query = $pdo->query("SELECT * from $pagina where estoque < '$nivel_minimo_estoque' order by id desc ");
    }else{
        $query = $pdo->query("SELECT * from $pagina order by id desc ");
    }
    $res = $query->fetchAll(\PDO::FETCH_ASSOC);

    return $res;
    

  }

public function inserir(){
$pagina = 'produtos';
//VARIAVEIS DOS INPUTS
$campo1 = 'Codigo';
$campo2 = 'Nome';
$campo3 = 'Descricao';
$campo4 = 'Estoque';
$campo5 = 'Valor_Compra';
$campo6 = 'Valor_Venda';
$campo7 = 'Fornecedor';
$campo8 = 'Categoria';
$campo9 = 'Foto';
$campo10 = 'Ativo';
$campo11 = 'Lucro';

$cp1 = filter_input(INPUT_POST, $campo1, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp2 = filter_input(INPUT_POST, $campo2, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp3 = filter_input(INPUT_POST, $campo3, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp6 = filter_input(INPUT_POST, $campo6, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp6 = str_replace(',', '.', $cp6);
$cp8 = filter_input(INPUT_POST, $campo8, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$cp10 = filter_input(INPUT_POST, $campo10, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$id =filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$pdo = $this->connect();

// Preservacao de estoque: edicao cadastral nao pode zerar estoque, custo e fornecedor.
$produtoAtual = null;
if($id != ""){
	$stmtAtual = $pdo->prepare("SELECT estoque, valor_compra, fornecedor FROM $pagina WHERE id = :id LIMIT 1");
	$stmtAtual->bindValue(':id', $id, \PDO::PARAM_INT);
	$stmtAtual->execute();
	$produtoAtual = $stmtAtual->fetch(\PDO::FETCH_ASSOC);
}

$cp4 = $produtoAtual ? $produtoAtual['estoque'] : 0;
$cp5 = $produtoAtual ? $produtoAtual['valor_compra'] : 0.00;
$cp7 = $produtoAtual ? $produtoAtual['fornecedor'] : 0;

//VALIDAR CAMPO
$query = $pdo->query("SELECT * from $pagina where nome = '$cp2'");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
$total_reg = @count($res);
$id_reg = @$res[0]['id'];
if($total_reg > 0 and $id_reg != $id){
	echo 'Este registro já está cadastrado!!';
	exit();
}


$query = $pdo->query("SELECT * from $pagina where codigo = '$cp1'");
$res = $query->fetchAll(\PDO::FETCH_ASSOC);
$total_reg = @count($res);
$id_reg = @$res[0]['id'];
if($total_reg > 0 and $id_reg != $id){
	echo 'Este registro já está cadastrado!!';
	exit();
}


//SCRIPT PARA SUBIR FOTO NO BANCO
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['imagem']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = 'config/img/'.$pagina.'/' .$nome_img;
if (@$_FILES['imagem']['name'] == ""){
	$imagem = "sem-foto.jpg";
}else{
	$imagem = $nome_img;
}

$imagem_temp = @$_FILES['imagem']['tmp_name']; 
$ext = pathinfo($imagem, PATHINFO_EXTENSION);   
if($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif'){ 
	move_uploaded_file($imagem_temp, $caminho);
}else{
	echo 'Extensão de Imagem não permitida!';
	exit();
}



if($id == ""){
	$query = $pdo->prepare("INSERT INTO $pagina set codigo = :campo1, nome = :campo2, descricao = :campo3, estoque = :campo4, valor_compra = :campo5, valor_venda = :campo6, fornecedor = :campo7,  categoria = :campo8, foto = :campo9, ativo = :campo10");
	$query->bindValue(":campo9", "$imagem");
}else{

	if($imagem == "sem-foto.jpg"){
		$query = $pdo->prepare("UPDATE $pagina set codigo = :campo1, nome = :campo2, descricao = :campo3, estoque = :campo4, valor_compra = :campo5, valor_venda = :campo6, fornecedor = :campo7,  categoria = :campo8, ativo = :campo10 WHERE id = '$id'");
	}else{
		
		//BUSCAR A IMAGEM PARA EXCLUIR DA PASTA
		$query_con = $pdo->query("SELECT * FROM produtos WHERE id = '$id'");
		$res_con = $query_con->fetchAll(\PDO::FETCH_ASSOC);
		$imagem_antiga = $res_con[0]['foto'];
		if($imagem_antiga != 'sem-foto.jpg'){
			@unlink('config/img/produtos/'.$imagem_antiga);
		}

		$query = $pdo->prepare("UPDATE $pagina set codigo = :campo1, nome = :campo2, descricao = :campo3, estoque = :campo4, valor_compra = :campo5, valor_venda = :campo6, fornecedor = :campo7,  categoria = :campo8, foto = :campo9, ativo = :campo10 WHERE id = '$id'");
		$query->bindValue(":campo9", "$imagem");
	}
	
}

$query->bindValue(":campo1", "$cp1");
$query->bindValue(":campo2", "$cp2");
$query->bindValue(":campo3", "$cp3");
$query->bindValue(":campo4", "$cp4");
$query->bindValue(":campo5", "$cp5");
$query->bindValue(":campo6", "$cp6");
$query->bindValue(":campo7", "$cp7");
$query->bindValue(":campo8", "$cp8");
$query->bindValue(":campo10", "$cp10");

$query->execute();

echo 'Salvo com Sucesso';
return $query;

  }

  public function excluir()

  {
    $pagina = 'produtos';

    $id = filter_input(INPUT_POST, 'id-excluir', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $pdo = $this->connect();

    //BUSCAR A IMAGEM PARA EXCLUIR DA PASTA
$query_con = $pdo->query("SELECT * FROM produtos WHERE id = '$id'");
$res_con = $query_con->fetchAll(\PDO::FETCH_ASSOC);
$imagem = $res_con[0]['foto'];
if($imagem != 'sem-foto.jpg'){
	@unlink('config/img/produtos/'.$imagem);
}


$pdo->query("DELETE from $pagina where id = '$id'");
echo 'Excluído com Sucesso';

return $pdo;

  }

  public function mudarStatus()
  {
    $pagina = 'produtos';

      $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $ativo = filter_input(INPUT_POST, 'ativar', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $pdo = $this->connect();

      $pdo->query("UPDATE $pagina SET ativo = '$ativo' where id = '$id'");
      echo 'Alterado com Sucesso';
      return $pdo;
  }

  public function comprarProd()
  {
    @session_start();
    
      $pagina = 'produtos';
      //VARIAVEIS DOS INPUTS

      $campo5 = 'Valor_Compra';

      $campo7 = 'Fornecedor';
      $campo8 = 'Categoria';
      $campo11 = 'Lucro';




@$id_usuario = $_SESSION['id'];

$id = filter_input(INPUT_POST, 'id-comprar', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp5 = filter_input(INPUT_POST, $campo5, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp5 = str_replace(',', '.', $cp5);
$cp7 = filter_input(INPUT_POST, $campo7, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp11 = filter_input(INPUT_POST, $campo11, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cp11 = str_replace(',', '.', $cp11);

$pdo = $this->connect();

$total_estoque = 0;
$query_con = $pdo->query("SELECT * FROM $pagina WHERE id = '$id'");
$res_con = $query_con->fetchAll(\PDO::FETCH_ASSOC);
$estoque = $res_con[0]['estoque'];
$valor_venda = $res_con[0]['valor_venda'];

$query_con = $pdo->query("SELECT * FROM fornecedores WHERE id = '$cp7'");
$res_con = $query_con->fetchAll(\PDO::FETCH_ASSOC);
$nome_forn = $res_con[0]['nome'];


if($cp11 != ""){
	$novo_vlr_venda = $cp5 + ($cp5 * $cp11 / 100);
}else{
	$novo_vlr_venda = $valor_venda;
}

$total_estoque = $estoque + $quantidade;

$query = $pdo->prepare("UPDATE $pagina SET estoque = :estoque, valor_compra = :valor_compra, fornecedor = :fornecedor, valor_venda = :valor_venda, lucro = :lucro where id = '$id'");
$query->bindValue(":estoque", "$total_estoque");
$query->bindValue(":valor_compra", "$cp5");
$query->bindValue(":fornecedor", "$cp7");
$query->bindValue(":valor_venda", "$novo_vlr_venda");
$query->bindValue(":lucro", "$cp11");
$query->execute();


//LANÇAR NAS CONTAS A PAGAR
$query = $pdo->prepare("INSERT INTO contas_pagar SET descricao = 'Fornecedor - $nome_forn', plano_conta = 'Compra de Produtos - Empresa', 
data_emissao = curDate(), vencimento = curDate(), juros = '0', multa = '0', desconto = '0', valor = :valor_compra, frequencia = 'Uma Vez', 
saida = 'Caixa', documento = 1, usuario_lanc = '$id_usuario', status = 'Pendente'");


$query->bindValue(":valor_compra", "$cp5");
$query->execute();

echo 'Comprado com Sucesso';

return $query;

  }


  /**
   * Summary of listarForn2
   * @return array
   */
  public function listarForn()
  {
    @session_start();

    $pagina = 'produtos';

    $nivel_minimo_estoque = 10;

    $pdo = $this->connect();

    if(@$_SESSION['estoque']=='sim'){
        $query = $pdo->query("SELECT * from $pagina where estoque < '$nivel_minimo_estoque' order by id desc ");
    }else{
        $query = $pdo->query("SELECT * from $pagina order by id desc ");
    }
    $res = $query->fetchAll(\PDO::FETCH_ASSOC);
   
    
    for($i = 0; $i < @count($res); $i++){
        foreach ($res[$i] as $key => $value){} 
    
            $id = $res[$i]['id'];
            $cp1 = $res[$i]['codigo'];
            $cp2 = $res[$i]['nome'];
            $cp3 = $res[$i]['descricao'];
            $cp4 = $res[$i]['estoque'];
            $cp5 = $res[$i]['valor_compra'];
            $cp6 = $res[$i]['valor_venda'];
            $cp7 = $res[$i]['fornecedor'];
            $cp8 = $res[$i]['categoria'];
            $cp9 = $res[$i]['foto'];
            $cp10 = $res[$i]['ativo'];
            $cp11 = $res[$i]['lucro'];
    
            $cp5 = number_format($cp5, 2, ',', '.');
            $cp6 = number_format($cp6, 2, ',', '.');
    
            $query1 = $pdo->query("SELECT * from fornecedores where id = '$cp7' ");
            $res1 = $query1->fetchAll(\PDO::FETCH_ASSOC);
           
    
            
 
    $query1 = $pdo->query("SELECT * from cat_produtos where id = '$cp8' ");
    $res1 = $query1->fetchAll(\PDO::FETCH_ASSOC);

  }
  return $res1;
    
  }

public function selecaoProd()
    {
        $pdo = $this->connect();
        $query = $pdo->query("SELECT * FROM cat_produtos order by nome asc");
        $res = $query->fetchAll(\PDO::FETCH_ASSOC);
         return $res;
    }

    public function SelecaoForne()
    {
        $pdo = $this->connect();
        $query = $pdo->query("SELECT * FROM fornecedores order by nome asc");
									$res = $query->fetchAll(\PDO::FETCH_ASSOC);
                                    return $res;

    }

    public function conectar()
{

    $pdo = $this->connect();

    return $pdo;
}


    


}
