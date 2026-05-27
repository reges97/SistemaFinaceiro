<?php

namespace app\models;

class CrudconCiliacao extends Connection
{

    public function inserir()

    {

    $pagina = 'conciliacao';
    //VARIAVEIS DOS INPUTS
    $campo1 = 'Data';
    $campo2 = 'Descricao';
    $campo3 = 'Documento';
    $campo4 = 'Observacao';
    $campo5 = 'Valor';
    $campo6 = 'Tipo';
    $campo7 = 'Conta';
    $campo8 = 'SaldoExterno';


    $cp1 = filter_input(INPUT_POST, $campo1, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cp2 = filter_input(INPUT_POST, $campo2, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cp3 = filter_input(INPUT_POST, $campo3, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cp4 = filter_input(INPUT_POST, $campo4, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cp5 = $this->moneyToFloat(filter_input(INPUT_POST, $campo5, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $cp6 = filter_input(INPUT_POST, $campo6, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cp7 = filter_input(INPUT_POST, $campo7, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cp8 = $this->moneyToFloat(filter_input(INPUT_POST, $campo8, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    //VALIDAR CAMPO
    $pdo = $this->connect();

    // Validacao de regra: conciliacao aceita somente credito ou debito e valor positivo.
    if(!in_array($cp6, ['Credito', 'Debito'], true)){
        echo 'Informe se o lancamento e Credito ou Debito';
        exit();
    }

    if($cp5 <= 0){
        echo 'Informe um valor maior que zero';
        exit();
    }

    if(!$cp7){
        echo 'Informe a conta bancaria';
        exit();
    }

    if(!$id){
        $query = $pdo->prepare("INSERT INTO $pagina set data = :campo1, descricao = :campo2, n_documento = :campo3, observacao = :campo4,
       valor = :campo5, tipo = :campo6, id_conta = :campo7, saldo_externo = :campo8, status = 'Pendente'");

    }else{
        // Edicao segura: registro conciliado nao pode ser alterado para nao quebrar saldo bancario.
        $stmtStatus = $pdo->prepare("SELECT status FROM $pagina WHERE id = :id LIMIT 1");
        $stmtStatus->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmtStatus->execute();
        $statusAtual = $stmtStatus->fetchColumn();

        if($statusAtual === 'Conciliado' || $statusAtual === 'Divergente'){
            echo 'Registro ja conciliado nao pode ser editado';
            exit();
        }

        $query = $pdo->prepare("UPDATE $pagina set  data = :campo1, descricao = :campo2, n_documento = :campo3, observacao = :campo4, valor = :campo5,
        tipo = :campo6, id_conta = :campo7, saldo_externo = :campo8 WHERE id = :id");
        $query->bindValue(":id", $id, \PDO::PARAM_INT);
    }
    $query->bindValue(":campo1", "$cp1");
    $query->bindValue(":campo2", "$cp2");
    $query->bindValue(":campo3", "$cp3");
    $query->bindValue(":campo4", "$cp4");
    $query->bindValue(":campo5", "$cp5");
    $query->bindValue(":campo6", "$cp6");
    $query->bindValue(":campo7", "$cp7");
    $query->bindValue(":campo8", "$cp8");
    $query->execute();

    echo 'Salvo com Sucesso';

    return $query;


        }


        public function conectar()
        {

            $pdo = $this->connect();

            return $pdo;
        }



        public function listarconci()

{

    $pagina = 'conciliacao';
//VARIAVEIS DOS INPUTS


    // Sessao centralizada para filtros AJAX sem aviso de sessao duplicada.
    $this->ensureSession();

$dataInicial = filter_input(INPUT_POST, 'dataInicial', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$dataFinal =  filter_input(INPUT_POST, 'dataFinal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$statusFiltro = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$status = '%'.$statusFiltro.'%';
$alterou_data = filter_input(INPUT_POST, 'alterou_data', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$credito =  filter_input(INPUT_POST, 'credito', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$debito =  filter_input(INPUT_POST, 'debito', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$hoje =  filter_input(INPUT_POST, 'hoje', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$data_hoje = date('Y-m-d');
$data_amanha = date('Y/m/d', strtotime("+1 days",strtotime($data_hoje)));

$pdo = $this->connect();

// Listagem segura: filtros montados com parametros para evitar SQL direto.
$sql = "SELECT C.id, C.data, C.descricao, C.n_documento, C.observacao, C.valor, C.tipo, C.id_conta,
    C.saldo_externo, C.saldo_interno, C.status, C.diferenca, B.banco, B.saldo
    FROM conciliacao AS C
    INNER JOIN bancarias AS B ON C.id_conta = B.id
    WHERE 1=1";
$params = [];

if($alterou_data == 'Sim' && ($dataInicial != "" || $dataFinal != "")){
    $sql .= " AND C.data >= :dataInicial AND C.data <= :dataFinal";
    $params[':dataInicial'] = $dataInicial ?: $data_hoje;
    $params[':dataFinal'] = $dataFinal ?: $data_amanha;
}

if($statusFiltro !== null && $statusFiltro !== ''){
    $sql .= " AND C.tipo LIKE :tipo";
    $params[':tipo'] = $status;
} else if($credito == 'Credito'){
    $sql .= " AND C.tipo = :credito";
    $params[':credito'] = 'Credito';
} else if($debito == 'Debito'){
    $sql .= " AND C.tipo = :debito";
    $params[':debito'] = 'Debito';
} else if($hoje == 'hoje' || $hoje == 'Hoje'){
    $sql .= " AND C.data = CURDATE()";
}

$sql .= " ORDER BY C.id DESC";
$query = $pdo->prepare($sql);
foreach($params as $nome => $valor){
    $query->bindValue($nome, $valor);
}
$query->execute();

@$res = $query->fetchAll(\PDO::FETCH_ASSOC);


return $res;

}

public function excluir()
{
    $pagina = 'conciliacao';
    $id = filter_input(INPUT_POST, 'id-excluir', FILTER_VALIDATE_INT);
    $pdo = $this->connect();

    if(!$id){
        echo 'Registro invalido para exclusao';
        return $pdo;
    }

    // Protecao de regra: conciliacao ja aplicada nao pode ser apagada sem rotina de estorno.
    $stmtStatus = $pdo->prepare("SELECT status FROM $pagina WHERE id = :id LIMIT 1");
    $stmtStatus->bindValue(':id', $id, \PDO::PARAM_INT);
    $stmtStatus->execute();
    $statusAtual = $stmtStatus->fetchColumn();

    if($statusAtual === 'Conciliado' || $statusAtual === 'Divergente'){
        echo 'Registro ja conciliado nao pode ser excluido';
        return $pdo;
    }

    // Exclusao segura por parametro para evitar apagar registro errado por SQL direto.
    $query = $pdo->prepare("DELETE FROM $pagina WHERE id = :id");
    $query->bindValue(':id', $id, \PDO::PARAM_INT);
    $query->execute();
   echo 'Excluido com Sucesso';
   return $pdo;

}

public function conci()
{
    $id_conci = filter_input(INPUT_POST, 'id_conci', FILTER_VALIDATE_INT);
    $this->ensureSession();
    $id_usuario = isset($_SESSION['id']) ? (int) $_SESSION['id'] : 0;
    $pdo = $this->connect();

    if(!$id_conci){
        echo 'Registro invalido para conciliacao';
        return $pdo;
    }

    try {
        // Transacao evita divergencia entre saldo bancario e status da conciliacao se algum update falhar.
        $pdo->beginTransaction();

        $query = $pdo->prepare("SELECT C.id, C.data, C.descricao, C.n_documento, C.observacao, C.valor, C.tipo, C.id_conta,
            C.saldo_externo, C.saldo_interno, C.status, B.banco, B.saldo
            FROM conciliacao AS C
            INNER JOIN bancarias AS B ON C.id_conta = B.id
            WHERE C.id = :id
            LIMIT 1
            FOR UPDATE");
        $query->bindValue(':id', $id_conci, \PDO::PARAM_INT);
        $query->execute();
        $res = $query->fetch(\PDO::FETCH_ASSOC);

        if(!$res){
            throw new \Exception('Registro de conciliacao nao encontrado');
        }

        if($res['status'] === 'Conciliado' || $res['status'] === 'Divergente'){
            throw new \Exception('Registro ja conciliado');
        }

        $valor = (float) $res['valor'];
        $tipo = $res['tipo'];
        $saldoAtual = (float) $res['saldo'];
        $saldoExterno = (float) $res['saldo_externo'];
        $idConta = (int) $res['id_conta'];

        if($valor <= 0){
            throw new \Exception('Valor invalido para conciliacao');
        }

        // Regra de negocio: credito soma no banco, debito subtrai e outro tipo e bloqueado.
        if($tipo === 'Credito'){
            $total = $saldoAtual + $valor;
        } else if($tipo === 'Debito'){
            $total = $saldoAtual - $valor;
        } else {
            throw new \Exception('Tipo de conciliacao invalido');
        }

        // Aviso de divergencia: compara o saldo calculado com o saldo externo informado.
        $diferenca = round($total - $saldoExterno, 2);
        $statusConciliacao = abs($diferenca) <= 0.01 ? 'Conciliado' : 'Divergente';

        $stmtBanco = $pdo->prepare("UPDATE bancarias SET saldo = :saldo WHERE id = :id_conta");
        $stmtBanco->bindValue(':saldo', $total);
        $stmtBanco->bindValue(':id_conta', $idConta, \PDO::PARAM_INT);
        $stmtBanco->execute();

        // Atualizacao pelo id da conciliacao corrige duplicidade que ocorria ao atualizar todos da mesma conta.
        $stmtConci = $pdo->prepare("UPDATE conciliacao
            SET saldo_interno = :saldo_interno,
                diferenca = :diferenca,
                status = :status,
                data_conciliacao = NOW(),
                usuario_conciliacao = :usuario_conciliacao
            WHERE id = :id");
        $stmtConci->bindValue(':saldo_interno', $total);
        $stmtConci->bindValue(':diferenca', $diferenca);
        $stmtConci->bindValue(':status', $statusConciliacao);
        $stmtConci->bindValue(':usuario_conciliacao', $id_usuario, \PDO::PARAM_INT);
        $stmtConci->bindValue(':id', $id_conci, \PDO::PARAM_INT);
        $stmtConci->execute();

        $this->registrarAuditoriaFinanceira($pdo, 'conciliacao', 'conciliacao', $id_conci, $id_usuario, $saldoAtual, $total, 'Conciliacao ' . $statusConciliacao);

        $pdo->commit();

        if($statusConciliacao === 'Divergente'){
            echo 'Conciliacao efetuada com divergencia de R$ ' . number_format(abs($diferenca), 2, ',', '.');
        } else {
            echo 'Conciliacao efetuada com sucesso';
        }
    } catch (\Throwable $erro) {
        if($pdo->inTransaction()){
            $pdo->rollBack();
        }

        error_log($erro->getMessage());
        echo $erro->getMessage() === 'Registro ja conciliado'
            ? 'Registro ja conciliado'
            : 'Nao foi possivel concluir a conciliacao. Nenhum saldo foi alterado.';
    }

return $pdo;


}

}
