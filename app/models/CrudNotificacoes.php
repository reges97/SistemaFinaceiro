<?php

namespace app\models;

class CrudNotificacoes extends Connection
{
    // Configuracao de e-mail: retorna o ultimo registro para manter uma configuracao ativa por empresa.
    public function obterEmailConfig()
    {
        $pdo = $this->connect();
        $stmt = $pdo->query("SELECT * FROM email_configuracoes ORDER BY id DESC LIMIT 1");
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
    }

    // Configuracao de WhatsApp: retorna o ultimo registro para uso nos testes e na rotina diaria.
    public function obterWhatsappConfig()
    {
        $pdo = $this->connect();
        $stmt = $pdo->query("SELECT * FROM whatsapp_configuracoes ORDER BY id DESC LIMIT 1");
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
    }

    public function salvarEmailConfig()
    {
        $pdo = $this->connect();
        $dados = [
            'servidor_smtp' => trim((string) ($_POST['servidor_smtp'] ?? '')),
            'porta' => (int) ($_POST['porta'] ?? 587),
            'criptografia' => $this->normalizarCriptografia($_POST['criptografia'] ?? 'TLS'),
            'usuario' => trim((string) ($_POST['usuario'] ?? '')),
            'senha' => (string) ($_POST['senha'] ?? ''),
            'email_remetente' => trim((string) ($_POST['email_remetente'] ?? '')),
            'nome_remetente' => trim((string) ($_POST['nome_remetente'] ?? '')),
            'ativo' => isset($_POST['ativo']) ? 1 : 0
        ];

        if ($dados['servidor_smtp'] === '' || $dados['email_remetente'] === '' || $dados['nome_remetente'] === '') {
            echo 'Informe servidor SMTP, e-mail remetente e nome do remetente.';
            return false;
        }

        if (!filter_var($dados['email_remetente'], FILTER_VALIDATE_EMAIL)) {
            echo 'E-mail remetente invalido.';
            return false;
        }

        // Upsert simples: atualiza a configuracao existente ou cria a primeira configuracao.
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $sql = "UPDATE email_configuracoes SET servidor_smtp = :servidor_smtp, porta = :porta, criptografia = :criptografia,
                usuario = :usuario, senha = :senha, email_remetente = :email_remetente, nome_remetente = :nome_remetente, ativo = :ativo WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        } else {
            $sql = "INSERT INTO email_configuracoes (servidor_smtp, porta, criptografia, usuario, senha, email_remetente, nome_remetente, ativo)
                VALUES (:servidor_smtp, :porta, :criptografia, :usuario, :senha, :email_remetente, :nome_remetente, :ativo)";
            $stmt = $pdo->prepare($sql);
        }

        foreach ($dados as $campo => $valor) {
            $stmt->bindValue(':' . $campo, $valor);
        }
        $stmt->execute();

        echo 'Configuracao de e-mail salva com sucesso.';
        return true;
    }

    public function salvarWhatsappConfig()
    {
        $pdo = $this->connect();
        $dados = [
            'provedor' => trim((string) ($_POST['provedor'] ?? '')),
            'url_api' => trim((string) ($_POST['url_api'] ?? '')),
            'token_acesso' => (string) ($_POST['token_acesso'] ?? ''),
            'numero_remetente' => trim((string) ($_POST['numero_remetente'] ?? '')),
            'ativo' => isset($_POST['ativo']) ? 1 : 0
        ];

        if ($dados['provedor'] === '' || $dados['url_api'] === '' || $dados['token_acesso'] === '') {
            echo 'Informe provedor, URL da API e token de acesso.';
            return false;
        }

        if (!filter_var($dados['url_api'], FILTER_VALIDATE_URL)) {
            echo 'URL da API invalida.';
            return false;
        }

        // Upsert simples: atualiza a configuracao existente ou cria a primeira configuracao.
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $sql = "UPDATE whatsapp_configuracoes SET provedor = :provedor, url_api = :url_api, token_acesso = :token_acesso,
                numero_remetente = :numero_remetente, ativo = :ativo WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        } else {
            $sql = "INSERT INTO whatsapp_configuracoes (provedor, url_api, token_acesso, numero_remetente, ativo)
                VALUES (:provedor, :url_api, :token_acesso, :numero_remetente, :ativo)";
            $stmt = $pdo->prepare($sql);
        }

        foreach ($dados as $campo => $valor) {
            $stmt->bindValue(':' . $campo, $valor);
        }
        $stmt->execute();

        echo 'Configuracao de WhatsApp salva com sucesso.';
        return true;
    }

    public function testarEmailConfig()
    {
        $config = $this->obterEmailConfig();
        if (!$config) {
            echo 'Salve a configuracao de e-mail antes de testar.';
            return false;
        }

        // Teste SMTP leve: valida se o servidor e porta respondem antes de usar em producao.
        $conexao = @fsockopen($config['servidor_smtp'], (int) $config['porta'], $errno, $errstr, 10);
        if (!$conexao) {
            echo 'Nao foi possivel conectar ao SMTP: ' . $errstr;
            return false;
        }

        fclose($conexao);
        echo 'Conexao SMTP testada com sucesso.';
        return true;
    }

    public function testarWhatsappConfig()
    {
        $config = $this->obterWhatsappConfig();
        if (!$config) {
            echo 'Salve a configuracao de WhatsApp antes de testar.';
            return false;
        }

        if (!function_exists('curl_init')) {
            echo 'Extensao cURL nao esta habilitada no PHP.';
            return false;
        }

        // Teste HTTP: faz uma chamada curta para confirmar URL/token sem disparar mensagens duplicadas.
        $ch = curl_init($config['url_api']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 12,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $config['token_acesso'],
                'Content-Type: application/json'
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'from' => $config['numero_remetente'],
                'message' => 'Teste de conexao do Sistema Financeiro'
            ])
        ]);
        $resposta = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $erro = curl_error($ch);
        curl_close($ch);

        if ($erro || $httpCode >= 400) {
            echo 'Falha no teste WhatsApp: ' . ($erro ?: 'HTTP ' . $httpCode . ' - ' . substr((string) $resposta, 0, 120));
            return false;
        }

        echo 'Conexao WhatsApp/API testada com sucesso.';
        return true;
    }

    public function processarAvisosAutomaticos()
    {
        $pdo = $this->connect();
        $total = 0;

        // Rotina diaria: registra vencimentos configurados e evita duplicidade pela chave unica.
        $total += $this->registrarAvisosVencimento($pdo, 'pagar', 'contas_pagar');
        $total += $this->registrarAvisosVencimento($pdo, 'receber', 'contas_receber');

        echo 'Rotina concluida. Avisos registrados: ' . $total;
        return $total;
    }

    private function registrarAvisosVencimento(\PDO $pdo, $tipoConta, $tabela)
    {
        $stmt = $pdo->query("SELECT id, descricao, cliente, vencimento, valor, aviso_forma, aviso_dias
            FROM {$tabela}
            WHERE status = 'Pendente'
              AND aviso_vencimento = 1
              AND vencimento = DATE_ADD(CURDATE(), INTERVAL aviso_dias DAY)");
        $contas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $total = 0;

        foreach ($contas as $conta) {
            foreach ($this->canaisAviso($conta['aviso_forma']) as $canal) {
                if ($this->registrarNotificacao($pdo, $tipoConta, (int) $conta['id'], 'vencimento', $canal, $conta['vencimento'], 'Aviso de vencimento: ' . $conta['descricao'])) {
                    $total++;
                }
            }
        }

        return $total;
    }

    public function registrarAvisoBaixa($tipoConta, $contaId, $forma = 'email')
    {
        $pdo = $this->connect();
        $total = 0;

        // Baixa financeira: registra aviso de pagamento/recebimento quando a conta estiver configurada para isso.
        foreach ($this->canaisAviso($forma) as $canal) {
            if ($this->registrarNotificacao($pdo, $tipoConta, (int) $contaId, 'baixa', $canal, date('Y-m-d'), 'Aviso de baixa realizada')) {
                $total++;
            }
        }

        return $total;
    }

    private function registrarNotificacao(\PDO $pdo, $tipoConta, $contaId, $tipoAviso, $canal, $dataReferencia, $resposta)
    {
        try {
            $stmt = $pdo->prepare("INSERT INTO notificacoes_enviadas
                (tipo_conta, conta_id, tipo_aviso, canal, data_referencia, status, resposta)
                VALUES (:tipo_conta, :conta_id, :tipo_aviso, :canal, :data_referencia, 'Registrado', :resposta)");
            $stmt->execute([
                ':tipo_conta' => $tipoConta,
                ':conta_id' => $contaId,
                ':tipo_aviso' => $tipoAviso,
                ':canal' => $canal,
                ':data_referencia' => $dataReferencia,
                ':resposta' => $resposta
            ]);
            return true;
        } catch (\PDOException $erro) {
            if ($erro->getCode() !== '23000') {
                error_log($erro->getMessage());
            }
            return false;
        }
    }

    private function canaisAviso($forma)
    {
        $forma = strtolower((string) $forma);
        if ($forma === 'ambos') {
            return ['email', 'whatsapp'];
        }

        return in_array($forma, ['email', 'whatsapp'], true) ? [$forma] : ['email'];
    }

    private function normalizarCriptografia($valor)
    {
        $valor = trim((string) $valor);
        if (mb_strtolower($valor, 'UTF-8') === 'nenhuma') {
            return 'Nenhuma';
        }

        $valor = strtoupper($valor);
        return in_array($valor, ['TLS', 'SSL'], true) ? $valor : 'TLS';
    }
}
