-- Migration 20260527: configuracoes de e-mail, WhatsApp e avisos financeiros.
CREATE TABLE IF NOT EXISTS email_configuracoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    servidor_smtp VARCHAR(180) NOT NULL,
    porta INT NOT NULL DEFAULT 587,
    criptografia ENUM('TLS','SSL','Nenhuma') NOT NULL DEFAULT 'TLS',
    usuario VARCHAR(180) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    email_remetente VARCHAR(180) NOT NULL,
    nome_remetente VARCHAR(180) NOT NULL,
    ativo TINYINT(1) NOT NULL DEFAULT 0,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS whatsapp_configuracoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provedor VARCHAR(120) NOT NULL,
    url_api VARCHAR(255) NOT NULL,
    token_acesso TEXT NOT NULL,
    numero_remetente VARCHAR(40) NOT NULL,
    ativo TINYINT(1) NOT NULL DEFAULT 0,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS notificacoes_configuracoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_conta ENUM('pagar','receber') NOT NULL,
    aviso_vencimento TINYINT(1) NOT NULL DEFAULT 1,
    aviso_baixa TINYINT(1) NOT NULL DEFAULT 1,
    aviso_forma ENUM('email','whatsapp','ambos') NOT NULL DEFAULT 'email',
    aviso_dias INT NOT NULL DEFAULT 2,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_notificacoes_tipo_conta (tipo_conta)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS notificacoes_enviadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_conta ENUM('pagar','receber') NOT NULL,
    conta_id INT NOT NULL,
    tipo_aviso ENUM('vencimento','baixa') NOT NULL,
    canal ENUM('email','whatsapp') NOT NULL,
    destinatario VARCHAR(180) NULL,
    data_referencia DATE NOT NULL,
    status VARCHAR(40) NOT NULL DEFAULT 'Registrado',
    resposta TEXT NULL,
    enviado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_notificacao_unica (tipo_conta, conta_id, tipo_aviso, canal, data_referencia)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Os ALTERs abaixo sao mantidos documentados; a aplicacao local usa checagem por information_schema para compatibilidade.
-- ALTER TABLE contas_pagar ADD COLUMN aviso_vencimento TINYINT(1) NOT NULL DEFAULT 0;
-- ALTER TABLE contas_pagar ADD COLUMN aviso_baixa TINYINT(1) NOT NULL DEFAULT 0;
-- ALTER TABLE contas_pagar ADD COLUMN aviso_forma ENUM('email','whatsapp','ambos') NOT NULL DEFAULT 'email';
-- ALTER TABLE contas_pagar ADD COLUMN aviso_dias INT NOT NULL DEFAULT 2;
-- ALTER TABLE contas_receber ADD COLUMN aviso_vencimento TINYINT(1) NOT NULL DEFAULT 0;
-- ALTER TABLE contas_receber ADD COLUMN aviso_baixa TINYINT(1) NOT NULL DEFAULT 0;
-- ALTER TABLE contas_receber ADD COLUMN aviso_forma ENUM('email','whatsapp','ambos') NOT NULL DEFAULT 'email';
-- ALTER TABLE contas_receber ADD COLUMN aviso_dias INT NOT NULL DEFAULT 2;
