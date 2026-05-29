<?php

namespace app\models;

class PainelFinanceiro extends Connection
{
    public function dados()
    {
        $pdo = $this->connect();
        $inicioMes = date('Y-m-01');
        $fimMes = date('Y-m-t');

        // Painel financeiro: centraliza consultas do perfil Financeiro sem reutilizar o painel administrativo.
        return [
            'periodo' => [
                'inicio' => $inicioMes,
                'fim' => $fimMes,
                'hoje' => date('Y-m-d')
            ],
            'receberMes' => $this->contasMes($pdo, 'receber', $inicioMes, $fimMes),
            'pagarMes' => $this->contasMes($pdo, 'pagar', $inicioMes, $fimMes),
            'proximos' => [
                'hoje' => $this->proximosVencimentos($pdo, 0),
                'doisDias' => $this->proximosVencimentos($pdo, 2),
                'seteDias' => $this->proximosVencimentos($pdo, 7)
            ],
            'avisosEmail' => $this->avisosEmail($pdo),
            'resumo' => $this->resumoMes($pdo, $inicioMes, $fimMes)
        ];
    }

    private function contasMes(\PDO $pdo, $tipo, $inicioMes, $fimMes)
    {
        $sql = $tipo === 'receber'
            ? "SELECT R.id, R.descricao, R.valor, R.subtotal, R.vencimento, R.status,
                    COALESCE(NULLIF(C.nome, ''), NULLIF(R.cliente, ''), 'Cliente nao informado') AS pessoa
                FROM contas_receber R
                LEFT JOIN clientes C ON C.id = R.cliente
                WHERE R.vencimento BETWEEN :inicio AND :fim
                ORDER BY R.vencimento ASC, R.id ASC"
            : "SELECT P.id, P.descricao, P.valor, P.subtotal, P.vencimento, P.status,
                    COALESCE(NULLIF(F.nome, ''), NULLIF(P.cliente, ''), 'Fornecedor nao informado') AS pessoa
                FROM contas_pagar P
                LEFT JOIN fornecedores F ON F.id = P.cliente
                WHERE P.vencimento BETWEEN :inicio AND :fim
                ORDER BY P.vencimento ASC, P.id ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':inicio', $inicioMes);
        $stmt->bindValue(':fim', $fimMes);
        $stmt->execute();

        return array_map(function ($conta) use ($tipo) {
            return $this->formatarConta($conta, $tipo);
        }, $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    private function proximosVencimentos(\PDO $pdo, $dias)
    {
        $fim = date('Y-m-d', strtotime('+' . (int) $dias . ' days'));

        return [
            'receber' => $this->contasIntervaloAbertas($pdo, 'receber', date('Y-m-d'), $fim),
            'pagar' => $this->contasIntervaloAbertas($pdo, 'pagar', date('Y-m-d'), $fim)
        ];
    }

    private function contasIntervaloAbertas(\PDO $pdo, $tipo, $inicio, $fim)
    {
        $sql = $tipo === 'receber'
            ? "SELECT R.id, R.descricao, R.valor, R.subtotal, R.vencimento, R.status,
                    COALESCE(NULLIF(C.nome, ''), NULLIF(R.cliente, ''), 'Cliente nao informado') AS pessoa
                FROM contas_receber R
                LEFT JOIN clientes C ON C.id = R.cliente
                WHERE R.vencimento BETWEEN :inicio AND :fim
                  AND LOWER(COALESCE(R.status, '')) NOT IN ('paga', 'pago', 'recebido', 'recebida')
                ORDER BY R.vencimento ASC, R.id ASC
                LIMIT 8"
            : "SELECT P.id, P.descricao, P.valor, P.subtotal, P.vencimento, P.status,
                    COALESCE(NULLIF(F.nome, ''), NULLIF(P.cliente, ''), 'Fornecedor nao informado') AS pessoa
                FROM contas_pagar P
                LEFT JOIN fornecedores F ON F.id = P.cliente
                WHERE P.vencimento BETWEEN :inicio AND :fim
                  AND LOWER(COALESCE(P.status, '')) NOT IN ('paga', 'pago', 'recebido', 'recebida')
                ORDER BY P.vencimento ASC, P.id ASC
                LIMIT 8";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':inicio', $inicio);
        $stmt->bindValue(':fim', $fim);
        $stmt->execute();

        return array_map(function ($conta) use ($tipo) {
            return $this->formatarConta($conta, $tipo);
        }, $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    private function avisosEmail(\PDO $pdo)
    {
        $stmt = $pdo->query("SELECT tipo_conta, conta_id, tipo_aviso, destinatario, status, enviado_em
            FROM notificacoes_enviadas
            WHERE canal = 'email'
            ORDER BY enviado_em DESC, id DESC
            LIMIT 10");

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function resumoMes(\PDO $pdo, $inicioMes, $fimMes)
    {
        $receber = $this->resumoTabela($pdo, 'contas_receber', $inicioMes, $fimMes);
        $pagar = $this->resumoTabela($pdo, 'contas_pagar', $inicioMes, $fimMes);
        $totalVencido = $this->totalVencido($pdo, 'contas_receber') + $this->totalVencido($pdo, 'contas_pagar');

        // Painel financeiro: saldo previsto considera o que entra no mes menos o que sai no mes.
        return [
            'totalReceber' => $receber['total'],
            'totalRecebido' => $receber['baixado'],
            'totalPagar' => $pagar['total'],
            'totalPago' => $pagar['baixado'],
            'saldoPrevisto' => $receber['total'] - $pagar['total'],
            'totalVencido' => $totalVencido
        ];
    }

    private function resumoTabela(\PDO $pdo, $tabela, $inicioMes, $fimMes)
    {
        $stmt = $pdo->prepare("SELECT
                SUM(COALESCE(valor, 0)) AS total,
                SUM(CASE WHEN LOWER(COALESCE(status, '')) IN ('paga', 'pago', 'recebido', 'recebida')
                    THEN COALESCE(subtotal, valor, 0) ELSE 0 END) AS baixado
            FROM {$tabela}
            WHERE vencimento BETWEEN :inicio AND :fim");
        $stmt->bindValue(':inicio', $inicioMes);
        $stmt->bindValue(':fim', $fimMes);
        $stmt->execute();
        $dados = $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];

        return [
            'total' => (float) ($dados['total'] ?? 0),
            'baixado' => (float) ($dados['baixado'] ?? 0)
        ];
    }

    private function totalVencido(\PDO $pdo, $tabela)
    {
        $stmt = $pdo->query("SELECT SUM(COALESCE(valor, 0)) FROM {$tabela}
            WHERE vencimento < CURDATE()
              AND LOWER(COALESCE(status, '')) NOT IN ('paga', 'pago', 'recebido', 'recebida')");

        return (float) $stmt->fetchColumn();
    }

    private function formatarConta(array $conta, $tipo)
    {
        $status = $this->statusConta($conta['status'] ?? '', $conta['vencimento'] ?? null, $tipo);

        return [
            'id' => (int) ($conta['id'] ?? 0),
            'pessoa' => $conta['pessoa'] ?? '',
            'descricao' => $conta['descricao'] ?? '',
            'valor' => (float) ($conta['valor'] ?? 0),
            'vencimento' => $conta['vencimento'] ?? '',
            'status' => $status,
            'classe' => $status === 'vencido' ? 'danger' : ($status === 'proximo' ? 'warning' : 'neutral')
        ];
    }

    private function statusConta($statusBanco, $vencimento, $tipo)
    {
        $statusNormalizado = mb_strtolower(trim((string) $statusBanco), 'UTF-8');
        if (in_array($statusNormalizado, ['paga', 'pago', 'recebido', 'recebida'], true)) {
            return $tipo === 'receber' ? 'recebido' : 'pago';
        }

        if ($vencimento && $vencimento < date('Y-m-d')) {
            return 'vencido';
        }

        if ($vencimento && $vencimento <= date('Y-m-d', strtotime('+2 days'))) {
            return 'proximo';
        }

        return 'aberto';
    }
}
