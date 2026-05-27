-- Auditoria financeira: registra baixas e alteracoes sensiveis para rastreabilidade.
CREATE TABLE IF NOT EXISTS auditoria_financeira (
    id INT AUTO_INCREMENT PRIMARY KEY,
    acao VARCHAR(60) NOT NULL,
    tabela VARCHAR(80) NOT NULL,
    registro_id INT NOT NULL,
    usuario_id INT NULL,
    valor_anterior DECIMAL(15,2) NULL,
    valor_novo DECIMAL(15,2) NULL,
    observacao VARCHAR(255) NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
