<?php


        

namespace app\models;

abstract class Connection
{

    
    private $dbname = 'mysql:host=localhost;dbname=financeiro;charset=utf8mb4';

    
    private $user = 'root';

    private $pass = '';

    

    //VALORES PARA A COMBOBOX DE PAGINAÇÃO

    


    protected function connect()
    {
        
           date_default_timezone_set('America/Sao_Paulo');

        try{
           $dbname = getenv('DB_DSN') ?: $this->dbname;
           $user = getenv('DB_USER') ?: $this->user;
           $pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : $this->pass;

           $conn = new \PDO($dbname, $user, $pass, [
               \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
               \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
               \PDO::ATTR_EMULATE_PREPARES => false,
           ]);

            $conn->exec("set names utf8mb4");
            return $conn;

        } catch (\PDOException $erro){ 

            error_log($erro->getMessage());
            throw new \RuntimeException('Nao foi possivel conectar ao banco de dados.');

        }
    }

    // Helper central de sessao: evita varios session_start() repetidos espalhados nos models.
    protected function ensureSession()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    // Helper monetario: normaliza valores brasileiros/decimais antes de calcular e gravar.
    protected function moneyToFloat($valor)
    {
        if ($valor === null || $valor === '') {
            return 0.0;
        }

        $valor = trim((string) $valor);
        $valor = str_replace(['R$', ' '], '', $valor);

        if (strpos($valor, ',') !== false) {
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        }

        return (float) $valor;
    }

    // Helper de senha administrativa: suporta password_hash e senhas antigas durante a migracao.
    protected function validarSenhaAdministrador($email, $senha)
    {
        $pdo = $this->connect();
        $query = $pdo->prepare("SELECT senha FROM usuarios WHERE email = :email AND nivel = 'Administrador' LIMIT 1");
        $query->bindValue(':email', $email);
        $query->execute();
        $admin = $query->fetch(\PDO::FETCH_ASSOC);

        if (!$admin || !isset($admin['senha'])) {
            return false;
        }

        return password_verify($senha, $admin['senha'])
            || hash_equals($admin['senha'], md5($senha))
            || hash_equals($admin['senha'], $senha);
    }

    // Helper de transacao: garante commit/rollback padrao nas rotinas financeiras.
    protected function executarTransacao(\PDO $pdo, callable $callback)
    {
        try {
            $pdo->beginTransaction();
            $resultado = $callback();
            $pdo->commit();
            return $resultado;
        } catch (\Throwable $erro) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            error_log($erro->getMessage());
            throw $erro;
        }
    }

    // Auditoria financeira: registra alteracoes relevantes sem interromper a operacao principal.
    protected function registrarAuditoriaFinanceira(\PDO $pdo, $acao, $tabela, $registroId, $usuarioId, $valorAnterior, $valorNovo, $observacao = '')
    {
        $query = $pdo->prepare("INSERT INTO auditoria_financeira
            (acao, tabela, registro_id, usuario_id, valor_anterior, valor_novo, observacao)
            VALUES (:acao, :tabela, :registro_id, :usuario_id, :valor_anterior, :valor_novo, :observacao)");
        $query->bindValue(':acao', $acao);
        $query->bindValue(':tabela', $tabela);
        $query->bindValue(':registro_id', $registroId, \PDO::PARAM_INT);
        $query->bindValue(':usuario_id', $usuarioId, \PDO::PARAM_INT);
        $query->bindValue(':valor_anterior', $valorAnterior);
        $query->bindValue(':valor_novo', $valorNovo);
        $query->bindValue(':observacao', substr((string) $observacao, 0, 255));
        $query->execute();
    }

    // Recorrencia financeira: calcula a proxima data preservando o dia quando o mes permitir.
    protected function proximaDataRecorrencia($dataBase, $frequencia = '', $diasFrequencia = 0, $multiplicador = 1)
    {
        if (!$dataBase) {
            return null;
        }

        $base = new \DateTimeImmutable(str_replace('/', '-', (string) $dataBase));
        $frequenciaNormalizada = mb_strtolower(trim((string) $frequencia), 'UTF-8');
        $dias = (int) $diasFrequencia;
        $multiplicador = max(1, (int) $multiplicador);

        if (strpos($frequenciaNormalizada, 'quinzen') !== false || $dias === 15) {
            return $base->modify('+' . (15 * $multiplicador) . ' days')->format('Y-m-d');
        }

        if (strpos($frequenciaNormalizada, 'seman') !== false || $dias === 7) {
            return $base->modify('+' . (7 * $multiplicador) . ' days')->format('Y-m-d');
        }

        if (strpos($frequenciaNormalizada, 'anual') !== false || strpos($frequenciaNormalizada, 'ano') !== false || $dias >= 360) {
            return $this->adicionarMesesPreservandoDia($base, 12 * $multiplicador);
        }

        if (strpos($frequenciaNormalizada, 'mens') !== false || $dias === 30 || $dias === 31) {
            return $this->adicionarMesesPreservandoDia($base, $multiplicador);
        }

        if ($dias === 90) {
            return $this->adicionarMesesPreservandoDia($base, 3 * $multiplicador);
        }

        if ($dias === 180) {
            return $this->adicionarMesesPreservandoDia($base, 6 * $multiplicador);
        }

        return $base->modify('+' . max(0, $dias * $multiplicador) . ' days')->format('Y-m-d');
    }

    // Ajuste para dias 29, 30 e 31: usa o ultimo dia valido quando o mes destino for menor.
    private function adicionarMesesPreservandoDia(\DateTimeImmutable $base, $meses)
    {
        $diaOriginal = (int) $base->format('d');
        $primeiroDiaDestino = $base->modify('first day of this month')->modify('+' . (int) $meses . ' months');
        $ultimoDiaDestino = (int) $primeiroDiaDestino->format('t');
        $diaFinal = min($diaOriginal, $ultimoDiaDestino);

        return $primeiroDiaDestino->setDate(
            (int) $primeiroDiaDestino->format('Y'),
            (int) $primeiroDiaDestino->format('m'),
            $diaFinal
        )->format('Y-m-d');
    }

    
 }
