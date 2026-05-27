<?php
// Incluir a conexão com BD
include_once './conexao.php';
?>



<body>
    <h1>Pesquisar entre datas</h1>

    <?php
    // Receber os dados do formulário
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    // Apresentar o botão gerar excel somente quando o usuário pesquisar entre datas
    if ((isset($dados['data_inicio'])) and (isset($dados['data_final']))) {
        echo "<a href='gerar_excel.php?data_inicio=" . $dados['data_inicio'] . "&data_final=" . $dados['data_final'] . "'>Gerar Excel</a><br><br>";
    }
    ?>

    <!-- Início do formulário pesquisar entre datas -->
    <form method="POST" action="">
        <?php
        // Manter os dados no formulário
        $data_inicio = "";
        if (isset($dados['data_inicio'])) {
            $data_inicio = $dados['data_inicio'];
        }
        ?>
        <label>Data de Inicio</label>
        <input type="date" name="data_inicio" value="<?php echo $data_inicio; ?>"><br><br>

        <?php
        // Manter os dados no formulário
        $data_final = "";
        if (isset($dados['data_final'])) {
            $data_final = $dados['data_final'];
        }
        ?>
        <label>Data final</label>
        <input type="date" name="data_final" value="<?php echo $data_final; ?>"><br><br>

        <input type="submit" value="Pesquisar" name="PesqEntreData"><br><br>

    </form>
    <!-- Fim do formulário pesquisar entre datas -->

    <?php
    // Verifica se o usuário clicou no botão
    if (!empty($dados['PesqEntreData'])) {
        //var_dump($dados);
        // QUERY sql para pesquisar entre datas
        $query_usuarios = "SELECT id, nome, email, endereco, created 
                    FROM usuarios
                    WHERE created BETWEEN :data_inicio AND :data_final";
        
        // Preparar a QUERY com PDO
        $result_usuarios = $conn->prepare($query_usuarios);

        // Substituir o link da QUERY usando bindParam
        $result_usuarios->bindParam(':data_inicio', $dados['data_inicio']);
        $result_usuarios->bindParam(':data_final', $dados['data_final']);

        // Executar a QUERY com PDO
        $result_usuarios->execute();

        // Verificar se encontrou algum registro no banco de dados com PHP e acessar o IF
        if (($result_usuarios) and ($result_usuarios->rowCount() != 0)) {

            // Ler os registros que vem do banco de dados
            while ($row_usuario = $result_usuarios->fetch(PDO::FETCH_ASSOC)) {
                //var_dump($row_usuario);
                extract($row_usuario);
                echo "ID: $id<br>";
                echo "Nome: $nome<br>";
                echo "E-mail: $email<br>";
                echo "Endereço: $endereco<br>";
                // Converter a data para o formato brasileiro
                echo "Cadastrado: " . date('d/m/Y H:i:s', strtotime($created)) . "<br>";
                echo "<hr>";
            }
        } else { // Acessa o ELSE quando não encontrar nenhum registro
            echo "<p style='color: #f00;'>Erro: Nenhum usuário encontrado!</p>";
        }
    }
    ?>


</body>

</html>