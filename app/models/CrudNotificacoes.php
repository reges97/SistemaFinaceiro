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

    // Layouts de e-mail: lista os modelos cadastrados para edicao na tela administrativa.
    public function listarEmailLayouts()
    {
        $pdo = $this->connect();
        $stmt = $pdo->query("SELECT * FROM email_layouts ORDER BY tipo_aviso ASC, ativo DESC, nome ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Layouts de e-mail: recupera um layout por id ou retorna vazio para novo cadastro.
    public function obterEmailLayout($id)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("SELECT * FROM email_layouts WHERE id = :id");
        $stmt->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
    }

    // Layouts de e-mail: busca o layout ativo do tipo solicitado e usa padrao interno se nao existir no banco.
    public function obterEmailLayoutAtivo($tipoAviso)
    {
        $tipoAviso = $this->normalizarTipoAviso($tipoAviso);
        $pdo = $this->connect();
        $stmt = $pdo->prepare("SELECT * FROM email_layouts WHERE tipo_aviso = :tipo AND ativo = 1 ORDER BY id DESC LIMIT 1");
        $stmt->bindValue(':tipo', $tipoAviso);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: $this->layoutEmailPadrao($tipoAviso);
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

    public function salvarEmailLayout()
    {
        $pdo = $this->connect();
        $dados = [
            'tipo_aviso' => $this->normalizarTipoAviso($_POST['tipo_aviso'] ?? 'pagar'),
            'nome' => trim((string) ($_POST['nome'] ?? '')),
            'assunto' => trim((string) ($_POST['assunto'] ?? '')),
            'cabecalho' => trim((string) ($_POST['cabecalho'] ?? '')),
            'corpo' => trim((string) ($_POST['corpo'] ?? '')),
            'rodape' => trim((string) ($_POST['rodape'] ?? '')),
            'ativo' => isset($_POST['ativo']) ? 1 : 0
        ];

        if ($dados['nome'] === '' || $dados['assunto'] === '' || $dados['corpo'] === '') {
            echo 'Informe nome, assunto e corpo do layout.';
            return false;
        }

        // Layout de e-mail: ao ativar um modelo, desativa outros do mesmo tipo para evitar conflito.
        if ((int) $dados['ativo'] === 1) {
            $stmtInativar = $pdo->prepare("UPDATE email_layouts SET ativo = 0 WHERE tipo_aviso = :tipo");
            $stmtInativar->bindValue(':tipo', $dados['tipo_aviso']);
            $stmtInativar->execute();
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $sql = "UPDATE email_layouts SET tipo_aviso = :tipo_aviso, nome = :nome, assunto = :assunto,
                cabecalho = :cabecalho, corpo = :corpo, rodape = :rodape, ativo = :ativo WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        } else {
            $sql = "INSERT INTO email_layouts (tipo_aviso, nome, assunto, cabecalho, corpo, rodape, ativo)
                VALUES (:tipo_aviso, :nome, :assunto, :cabecalho, :corpo, :rodape, :ativo)";
            $stmt = $pdo->prepare($sql);
        }

        foreach ($dados as $campo => $valor) {
            $stmt->bindValue(':' . $campo, $valor);
        }
        $stmt->execute();

        echo 'Layout de e-mail salvo com sucesso.';
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

    public function testarEmailLayout()
    {
        $tipoAviso = $this->normalizarTipoAviso($_POST['tipo_aviso'] ?? 'pagar');
        $emailDestino = trim((string) ($_POST['email_teste'] ?? ''));
        $layoutId = (int) ($_POST['layout_id'] ?? 0);
        $layout = $layoutId > 0 ? $this->obterEmailLayout($layoutId) : $this->obterEmailLayoutAtivo($tipoAviso);

        if (!$layout) {
            echo 'Layout nao encontrado para teste.';
            return false;
        }

        $emailConfig = $this->obterEmailConfig();
        if ($emailDestino === '') {
            $emailDestino = $emailConfig['email_remetente'] ?? '';
        }

        if (!filter_var($emailDestino, FILTER_VALIDATE_EMAIL)) {
            echo 'Informe um e-mail de teste valido.';
            return false;
        }

        // Teste de layout: usa dados ficticios para validar variaveis antes de usar em producao.
        $dadosTeste = [
            'cliente' => 'Cliente Exemplo',
            'fornecedor' => 'Fornecedor Exemplo',
            'descricao' => 'Parcela de teste do Sistema Financeiro',
            'valor' => 'R$ 150,00',
            'data_vencimento' => date('d/m/Y', strtotime('+2 days')),
            'data_pagamento' => date('d/m/Y'),
            'nome_empresa' => $emailConfig['nome_remetente'] ?? 'Sistema Financeiro',
            'telefone_empresa' => '',
            'email_empresa' => $emailConfig['email_remetente'] ?? '',
            'link_pagamento' => ''
        ];

        $email = $this->montarEmailPorLayout($layout, $dadosTeste);
        $resultado = $this->enviarEmailSmtp($emailDestino, $email['assunto'], $email['html'], true);

        echo $resultado['ok'] ? 'E-mail de teste enviado com sucesso para ' . $emailDestino : 'Falha no envio do teste: ' . $resultado['mensagem'];
        return $resultado['ok'];
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
                $resposta = 'Aviso de vencimento: ' . $conta['descricao'];
                $destinatario = null;
                $status = 'Registrado';

                // Layout de e-mail: monta e envia mensagem real quando o canal configurado for e-mail.
                if ($canal === 'email') {
                    $resultadoEmail = $this->enviarAvisoEmail($tipoConta, $conta, 'vencimento');
                    $resposta = $resultadoEmail['mensagem'];
                    $destinatario = $resultadoEmail['destinatario'];
                    $status = $resultadoEmail['ok'] ? 'Enviado' : 'Erro';
                }

                if ($this->registrarNotificacao($pdo, $tipoConta, (int) $conta['id'], 'vencimento', $canal, $conta['vencimento'], $resposta, $destinatario, $status)) {
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
        $conta = $this->buscarContaAviso($tipoConta, (int) $contaId);
        foreach ($this->canaisAviso($forma) as $canal) {
            $resposta = 'Aviso de baixa realizada';
            $destinatario = null;
            $status = 'Registrado';

            if ($canal === 'email' && $conta) {
                $resultadoEmail = $this->enviarAvisoEmail($tipoConta, $conta, 'baixa');
                $resposta = $resultadoEmail['mensagem'];
                $destinatario = $resultadoEmail['destinatario'];
                $status = $resultadoEmail['ok'] ? 'Enviado' : 'Erro';
            }

            if ($this->registrarNotificacao($pdo, $tipoConta, (int) $contaId, 'baixa', $canal, date('Y-m-d'), $resposta, $destinatario, $status)) {
                $total++;
            }
        }

        return $total;
    }

    private function registrarNotificacao(\PDO $pdo, $tipoConta, $contaId, $tipoAviso, $canal, $dataReferencia, $resposta, $destinatario = null, $status = 'Registrado')
    {
        try {
            $stmt = $pdo->prepare("INSERT INTO notificacoes_enviadas
                (tipo_conta, conta_id, tipo_aviso, canal, data_referencia, status, resposta)
                VALUES (:tipo_conta, :conta_id, :tipo_aviso, :canal, :data_referencia, :status, :resposta)");
            $stmt->execute([
                ':tipo_conta' => $tipoConta,
                ':conta_id' => $contaId,
                ':tipo_aviso' => $tipoAviso,
                ':canal' => $canal,
                ':data_referencia' => $dataReferencia,
                ':status' => $status,
                ':resposta' => $resposta
            ]);
            if ($destinatario) {
                $pdo->prepare("UPDATE notificacoes_enviadas SET destinatario = :destinatario WHERE id = LAST_INSERT_ID()")
                    ->execute([':destinatario' => $destinatario]);
            }
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

    private function normalizarTipoAviso($tipoAviso)
    {
        return in_array($tipoAviso, ['pagar', 'receber'], true) ? $tipoAviso : 'pagar';
    }

    private function layoutEmailPadrao($tipoAviso)
    {
        if ($tipoAviso === 'receber') {
            return [
                'tipo_aviso' => 'receber',
                'nome' => 'Padrao do sistema - Contas a receber',
                'assunto' => 'Aviso de vencimento - Conta a receber',
                'cabecalho' => 'Aviso financeiro',
                'corpo' => 'Ola {{cliente}},<br><br>Identificamos uma conta a receber proxima do vencimento.<br><br>Descricao: {{descricao}}<br>Valor: {{valor}}<br>Data de vencimento: {{data_vencimento}}<br><br>Caso o pagamento ja tenha sido realizado, favor desconsiderar este aviso.<br><br>Atenciosamente,<br>{{nome_empresa}}',
                'rodape' => '{{nome_empresa}} - {{email_empresa}} - {{telefone_empresa}}',
                'ativo' => 1
            ];
        }

        return [
            'tipo_aviso' => 'pagar',
            'nome' => 'Padrao do sistema - Contas a pagar',
            'assunto' => 'Aviso de vencimento - Conta a pagar',
            'cabecalho' => 'Aviso financeiro',
            'corpo' => 'Ola,<br><br>Existe uma conta a pagar proxima do vencimento.<br><br>Fornecedor: {{fornecedor}}<br>Descricao: {{descricao}}<br>Valor: {{valor}}<br>Data de vencimento: {{data_vencimento}}<br><br>Favor verificar e providenciar o pagamento.<br><br>Atenciosamente,<br>{{nome_empresa}}',
            'rodape' => '{{nome_empresa}} - {{email_empresa}} - {{telefone_empresa}}',
            'ativo' => 1
        ];
    }

    private function montarEmailPorLayout(array $layout, array $dados)
    {
        $substituicoes = [];
        foreach ($dados as $chave => $valor) {
            $substituicoes['{{' . $chave . '}}'] = (string) $valor;
        }

        $assunto = strtr($layout['assunto'] ?? '', $substituicoes);
        $cabecalho = strtr($layout['cabecalho'] ?? '', $substituicoes);
        $corpo = strtr($layout['corpo'] ?? '', $substituicoes);
        $rodape = strtr($layout['rodape'] ?? '', $substituicoes);

        // Layout de e-mail: envelope HTML simples para manter padrao visual nos avisos financeiros.
        $html = '<div style="font-family:Arial,sans-serif;color:#1f2937;line-height:1.55;max-width:680px;margin:0 auto;border:1px solid #d8e0ea;border-radius:8px;overflow:hidden">';
        $html .= '<div style="background:#0f766e;color:#fff;padding:18px 22px;font-size:18px;font-weight:700">' . $cabecalho . '</div>';
        $html .= '<div style="padding:22px;background:#fff">' . $corpo . '</div>';
        $html .= '<div style="background:#f8fafc;color:#64748b;padding:14px 22px;font-size:12px">' . $rodape . '</div>';
        $html .= '</div>';

        return ['assunto' => $assunto, 'html' => $html];
    }

    private function enviarAvisoEmail($tipoConta, array $conta, $tipoAviso)
    {
        $emailConfig = $this->obterEmailConfig();
        $destinatario = $emailConfig['email_remetente'] ?? '';
        $layout = $this->obterEmailLayoutAtivo($tipoConta);
        $dados = $this->dadosLayoutConta($tipoConta, $conta, $emailConfig);
        $email = $this->montarEmailPorLayout($layout, $dados);

        $resultado = $this->enviarEmailSmtp($destinatario, $email['assunto'], $email['html'], false);
        $acao = $tipoAviso === 'baixa' ? 'baixa' : 'vencimento';

        return [
            'ok' => $resultado['ok'],
            'destinatario' => $destinatario,
            'mensagem' => ($resultado['ok'] ? 'E-mail de ' . $acao . ' enviado: ' : 'Falha no e-mail de ' . $acao . ': ') . $resultado['mensagem']
        ];
    }

    private function dadosLayoutConta($tipoConta, array $conta, array $emailConfig)
    {
        $nome = trim((string) ($conta['cliente'] ?? ''));

        return [
            'cliente' => $tipoConta === 'receber' ? $nome : '',
            'fornecedor' => $tipoConta === 'pagar' ? $nome : '',
            'descricao' => $conta['descricao'] ?? '',
            'valor' => 'R$ ' . number_format((float) ($conta['valor'] ?? 0), 2, ',', '.'),
            'data_vencimento' => !empty($conta['vencimento']) ? date('d/m/Y', strtotime($conta['vencimento'])) : '',
            'data_pagamento' => !empty($conta['data_baixa']) ? date('d/m/Y', strtotime($conta['data_baixa'])) : date('d/m/Y'),
            'nome_empresa' => $emailConfig['nome_remetente'] ?? 'Sistema Financeiro',
            'telefone_empresa' => '',
            'email_empresa' => $emailConfig['email_remetente'] ?? '',
            'link_pagamento' => ''
        ];
    }

    private function buscarContaAviso($tipoConta, $contaId)
    {
        $pdo = $this->connect();
        $tabela = $tipoConta === 'receber' ? 'contas_receber' : 'contas_pagar';
        $stmt = $pdo->prepare("SELECT id, descricao, cliente, vencimento, valor, data_baixa FROM {$tabela} WHERE id = :id");
        $stmt->bindValue(':id', (int) $contaId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
    }

    private function enviarEmailSmtp($destinatario, $assunto, $html, $permitirInativo = false)
    {
        $config = $this->obterEmailConfig();
        if (!$config) {
            return ['ok' => false, 'mensagem' => 'Configuracao de e-mail nao encontrada.'];
        }

        if (!$permitirInativo && empty($config['ativo'])) {
            return ['ok' => false, 'mensagem' => 'Configuracao de e-mail esta inativa.'];
        }

        if (!filter_var($destinatario, FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'mensagem' => 'Destinatario invalido.'];
        }

        $host = (string) $config['servidor_smtp'];
        $porta = (int) $config['porta'];
        $criptografia = $this->normalizarCriptografia($config['criptografia'] ?? 'TLS');
        $remote = ($criptografia === 'SSL' ? 'ssl://' : '') . $host . ':' . $porta;
        $socket = @stream_socket_client($remote, $errno, $errstr, 20, STREAM_CLIENT_CONNECT);

        if (!$socket) {
            return ['ok' => false, 'mensagem' => 'Nao conectou ao SMTP: ' . $errstr];
        }

        stream_set_timeout($socket, 20);

        try {
            $this->smtpEsperar($socket, [220]);
            $this->smtpComando($socket, 'EHLO localhost', [250]);

            if ($criptografia === 'TLS') {
                $this->smtpComando($socket, 'STARTTLS', [220]);
                if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    throw new \RuntimeException('Falha ao iniciar TLS.');
                }
                $this->smtpComando($socket, 'EHLO localhost', [250]);
            }

            if (!empty($config['usuario'])) {
                $this->smtpComando($socket, 'AUTH LOGIN', [334]);
                $this->smtpComando($socket, base64_encode($config['usuario']), [334]);
                $this->smtpComando($socket, base64_encode($config['senha']), [235]);
            }

            $remetente = $config['email_remetente'];
            $nomeRemetente = $config['nome_remetente'];
            $headers = [
                'From: ' . $this->mimeHeader($nomeRemetente) . ' <' . $remetente . '>',
                'To: <' . $destinatario . '>',
                'Subject: ' . $this->mimeHeader($assunto),
                'MIME-Version: 1.0',
                'Content-Type: text/html; charset=UTF-8',
                'Content-Transfer-Encoding: 8bit'
            ];
            $mensagem = implode("\r\n", $headers) . "\r\n\r\n" . $html;
            $mensagem = preg_replace('/^\./m', '..', $mensagem);

            $this->smtpComando($socket, 'MAIL FROM:<' . $remetente . '>', [250]);
            $this->smtpComando($socket, 'RCPT TO:<' . $destinatario . '>', [250, 251]);
            $this->smtpComando($socket, 'DATA', [354]);
            $this->smtpComando($socket, $mensagem . "\r\n.", [250]);
            $this->smtpComando($socket, 'QUIT', [221, 250]);
            fclose($socket);

            return ['ok' => true, 'mensagem' => 'enviado para ' . $destinatario];
        } catch (\Throwable $erro) {
            fclose($socket);
            return ['ok' => false, 'mensagem' => $erro->getMessage()];
        }
    }

    private function smtpComando($socket, $comando, array $codigosEsperados)
    {
        fwrite($socket, $comando . "\r\n");
        return $this->smtpEsperar($socket, $codigosEsperados);
    }

    private function smtpEsperar($socket, array $codigosEsperados)
    {
        $resposta = '';
        while (($linha = fgets($socket, 515)) !== false) {
            $resposta .= $linha;
            if (isset($linha[3]) && $linha[3] === ' ') {
                break;
            }
        }

        $codigo = (int) substr($resposta, 0, 3);
        if (!in_array($codigo, $codigosEsperados, true)) {
            throw new \RuntimeException(trim($resposta) ?: 'Resposta SMTP inesperada.');
        }

        return $resposta;
    }

    private function mimeHeader($texto)
    {
        return '=?UTF-8?B?' . base64_encode((string) $texto) . '?=';
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
