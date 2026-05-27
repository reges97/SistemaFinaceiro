<?php

namespace app\models;

class CrudChart extends Connection
{

    public function conectar()
    {
    
        $pdo = $this->connect();
    
        return $pdo;
    }
    public function listarchartmov()
    {
        

        $pdo = $this->conectar();

        $stmt = $pdo->query(
        " SELECT  M.tipo, M.E, M.S, M.valor,  M.usuario, M.data as data, M.plano_conta, M.documento, M.caixa_periodo, M.lancamento,
        M.conta_pag, M.mov_contas, M.conta_rec,  U.nome_usu FROM movimentacoes as M
          
         INNER JOIN usuarios AS U ON U.id = M.usuario
         INNER JOIN formas_pgtos AS F ON F.id = M.documento");

       $result3 = $stmt->fetchAll(\PDO::FETCH_ASSOC);

         
       return $result3;
      

    }
        public function listarChartTotal()
        {
            $pdo = $this->conectar();
            $stmt = $pdo->query("SELECT SUM(M.E) AS ENTRADA, SUM(M.S) AS SAIDA
            FROM movimentacoes as M");
            $result3 = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $result3;
        }


}