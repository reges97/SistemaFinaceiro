<?php

namespace app\models;

class CrudControleCaixa extends Connection
{
    public function listarControle()
    {
        $this->ensureSession();

        $dataInicial = $this->postValor('dataInicial');
        $dataFinal = $this->postValor('dataFinal');
        $status = $this->postValor('status');
        $alterouData = $this->postValor('alterou_data');
        $vencidas = $this->postValor('vencidas');

        $pdo = $this->connect();
        $where = [];
        $params = [];

        // Controle de caixa: filtros usam parametros preparados para evitar falhas e duplicidade por SQL montado em texto.
        if ($alterouData === 'Sim' && ($dataInicial !== '' || $dataFinal !== '')) {
            if ($dataInicial !== '') {
                $where[] = 'data >= :dataInicial';
                $params[':dataInicial'] = $dataInicial;
            }

            if ($dataFinal !== '') {
                $where[] = 'data <= :dataFinal';
                $params[':dataFinal'] = $dataFinal;
            }

            if ($status !== '') {
                $where[] = 'tipo = :tipo';
                $params[':tipo'] = $status;
            }
        } elseif ($status !== '') {
            $where[] = 'tipo = :tipo';
            $params[':tipo'] = $status;
        } elseif ($vencidas === 'Vencidas') {
            $where[] = 'data < CURDATE()';
        } elseif ($vencidas === 'Hoje') {
            $where[] = 'data = CURDATE()';
        } elseif ($vencidas === 'Amanha') {
            // Controle de caixa: usa formato Y-m-d, igual ao tipo DATE do banco.
            $where[] = 'data = DATE_ADD(CURDATE(), INTERVAL 1 DAY)';
        }

        $sql = 'SELECT * FROM controle_caixa';
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY data DESC, id DESC';

        $query = $pdo->prepare($sql);
        foreach ($params as $campo => $valor) {
            $query->bindValue($campo, $valor);
        }
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

public function relControle2()
{
	$pagina = 'controle_caixa';
	@$dataInicial = $_GET['dataInicial'];
	$dataFinal = $_GET['dataFinal'];

	$pdo = $this->connect();
	$query = $pdo->prepare("SELECT * from $pagina where data >= :dataInicial and data <= :dataFinal");
        $query->bindValue(":dataInicial", "$dataInicial");
        $query->bindValue(":dataFinal", "$dataFinal");
        $query->execute();
        $res = $query->fetchAll(\PDO::FETCH_ASSOC);
		
		
	return $res;



}

public function chart()

{
	$pagina = 'controle_caixa';

	$pagina = 'controle_caixa';
    $pdo = $this->connect();
	$query = $pdo->query("SELECT  C.data, C.movimento, C.entrada, C.saida, C.id_caixa, C.tipo, C.saldo, CX.data_ab, 
	 CX.usuario_ab, CX.data_fec, CX.usuario_fec, CX.saldo, CX.status, CX.valor_ab, CX.saldo_inicial FROM $pagina AS C 
	INNER JOIN caixa AS CX ON C.id_caixa = CX.id ");
	$res = $query->fetchAll(\PDO::FETCH_ASSOC);

    return $res;

}

public function conectar()
{
	
    $pdo = $this->connect();
	
    return $pdo;
}

private function postValor($campo)
{
    $valor = filter_input(INPUT_POST, $campo, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($valor === null && isset($_POST[$campo])) {
        $valor = filter_var($_POST[$campo], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    return is_string($valor) ? trim($valor) : '';
}
}
