<?php

namespace app\models;

class PainelOperacional extends Connection
{
    public function indicadores($idUsuario)
    {
        $pdo = $this->connect();

        // Home sem valores financeiros: indicadores operacionais evitam exposicao de saldo/contas ao usuario comum.
        return [
            'vendasHoje' => $this->contar($pdo, 'SELECT COUNT(DISTINCT id_venda) FROM vendas WHERE dataCompra = CURDATE() AND id_usuario = :usuario', [':usuario' => (int) $idUsuario]),
            'clientesAtivos' => $this->contar($pdo, "SELECT COUNT(*) FROM clientes WHERE COALESCE(ativo, 'Sim') = 'Sim'"),
            'produtosBaixoEstoque' => $this->contar($pdo, 'SELECT COUNT(*) FROM produtos WHERE estoque < 10'),
            'produtosAtivos' => $this->contar($pdo, "SELECT COUNT(*) FROM produtos WHERE COALESCE(ativo, 'Sim') = 'Sim'"),
            'ultimasVendas' => $this->ultimasVendas($pdo, $idUsuario),
            'estoqueBaixo' => $this->estoqueBaixo($pdo)
        ];
    }

    private function contar(\PDO $pdo, $sql, array $params = [])
    {
        try {
            $stmt = $pdo->prepare($sql);
            foreach ($params as $campo => $valor) {
                $stmt->bindValue($campo, $valor, \PDO::PARAM_INT);
            }
            $stmt->execute();
            return (int) $stmt->fetchColumn();
        } catch (\Throwable $erro) {
            error_log($erro->getMessage());
            return 0;
        }
    }

    private function ultimasVendas(\PDO $pdo, $idUsuario)
    {
        try {
            // Lista operacional: mostra comprovante e quantidade, sem total em dinheiro.
            $stmt = $pdo->prepare("SELECT V.id_venda, MIN(V.dataCompra) AS dataCompra, SUM(V.quantidade) AS quantidade,
                    COALESCE(MIN(C.nome), 'Consumidor') AS cliente
                FROM vendas V
                LEFT JOIN clientes C ON C.id = V.id_cliente
                WHERE V.id_usuario = :usuario
                GROUP BY V.id_venda
                ORDER BY V.id_venda DESC
                LIMIT 5");
            $stmt->bindValue(':usuario', (int) $idUsuario, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $erro) {
            error_log($erro->getMessage());
            return [];
        }
    }

    private function estoqueBaixo(\PDO $pdo)
    {
        try {
            $stmt = $pdo->query('SELECT nome, estoque FROM produtos WHERE estoque < 10 ORDER BY estoque ASC, nome ASC LIMIT 5');
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $erro) {
            error_log($erro->getMessage());
            return [];
        }
    }
}
