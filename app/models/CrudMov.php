<?php

namespace app\models;

class CrudMov extends Connection
{
    public function listarMov()
    {
        $dataInicial = filter_input(INPUT_POST, 'dataInicial', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dataFinal = filter_input(INPUT_POST, 'dataFinal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $statusValor = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $alterouData = filter_input(INPUT_POST, 'alterou_data', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $entradas = filter_input(INPUT_POST, 'entradas', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $hoje = filter_input(INPUT_POST, 'hoje', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $saida = filter_input(INPUT_POST, 'saida', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $pdo = $this->connect();
        $status = '%' . (string) $statusValor . '%';

        if ($alterouData === 'Sim' && ($dataInicial !== '' || $dataFinal !== '')) {
            // Movimentacao: filtra por periodo sem esconder registros sem plano/documento vinculado.
            $query = $pdo->query($this->sqlListagemMovimentacoes(
                "M.data >= " . $pdo->quote($dataInicial) . " AND M.data <= " . $pdo->quote($dataFinal) . " AND M.tipo LIKE " . $pdo->quote($status),
                'M.data DESC, M.id DESC'
            ));
        } elseif ($status !== '%%' && $alterouData === '') {
            $query = $pdo->query($this->sqlListagemMovimentacoes("M.tipo LIKE " . $pdo->quote($status)));
        } elseif ($entradas === 'Entradas') {
            $query = $pdo->query($this->sqlListagemMovimentacoes("M.tipo = 'Entrada'"));
        } elseif ($hoje === 'Hoje') {
            $query = $pdo->query($this->sqlListagemMovimentacoes("M.data = CURDATE()"));
        } elseif ($saida === 'Saidas') {
            // Filtro de saida: banco grava o tipo sem acento.
            $query = $pdo->query($this->sqlListagemMovimentacoes("M.tipo = 'Saida'"));
        } else {
            $query = $pdo->query($this->sqlListagemMovimentacoes("M.data = CURDATE()"));
        }

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function conectar()
    {
        return $this->connect();
    }

    public function gerarExcel()
    {
        $dataInicial = filter_input(INPUT_POST, 'dataInicial', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dataFinal = filter_input(INPUT_POST, 'dataFinal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pdo = $this->connect();

        // Relatorio de movimentacao: usa a mesma regra de LEFT JOIN da tela para nao perder lancamentos.
        if (($dataInicial !== '' || $dataFinal !== '') && $tipo === '') {
            $sql = $this->sqlListagemMovimentacoes("M.data >= :dataInicial AND M.data <= :dataFinal OR M.tipo = :tipo");
            $query = $pdo->prepare($sql);
            $query->bindValue(':dataInicial', $dataInicial);
            $query->bindValue(':dataFinal', $dataFinal);
            $query->bindValue(':tipo', $tipo);
            $query->execute();
            return $query;
        }

        if ($dataInicial !== '' || $dataFinal !== '') {
            $sql = $this->sqlListagemMovimentacoes("M.data >= :dataInicial AND M.data <= :dataFinal AND M.tipo = :tipo");
            $query = $pdo->prepare($sql);
            $query->bindValue(':dataInicial', $dataInicial);
            $query->bindValue(':dataFinal', $dataFinal);
            $query->bindValue(':tipo', $tipo);
            $query->execute();
            return $query;
        }

        return $pdo->query($this->sqlListagemMovimentacoes('M.data = CURDATE()'));
    }

    private function sqlListagemMovimentacoes($where, $order = 'M.id DESC')
    {
        // Listagem de movimentacao: textos padrao mantem a linha visivel mesmo sem cadastro auxiliar.
        return "SELECT M.id, M.tipo, M.E, M.S, M.movimento,
            M.descricao, M.valor, M.usuario,
            COALESCE(U.nome_usu, 'Sem usuario') AS nome_usu,
            M.data, M.lancamento, M.plano_conta,
            COALESCE(D.nome_desp, 'Sem plano') AS nome_desp,
            M.documento, COALESCE(F.nome_fpg, 'Sem documento') AS nome_fpg,
            M.caixa_periodo, M.conta_pag, M.mov_contas, M.conta_rec
            FROM movimentacoes AS M
            LEFT JOIN usuarios AS U ON U.id = M.usuario
            LEFT JOIN formas_pgtos AS F ON F.id = M.documento
            LEFT JOIN despesas AS D ON D.id = M.plano_conta
            WHERE {$where}
            ORDER BY {$order}";
    }
}
