-- Migration 20260528: layouts de e-mail para avisos financeiros personalizados.
CREATE TABLE IF NOT EXISTS email_layouts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_aviso ENUM('pagar','receber') NOT NULL,
    nome VARCHAR(160) NOT NULL,
    assunto VARCHAR(220) NOT NULL,
    cabecalho TEXT NULL,
    corpo MEDIUMTEXT NOT NULL,
    rodape TEXT NULL,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_email_layouts_tipo_ativo (tipo_aviso, ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO email_layouts (tipo_aviso, nome, assunto, cabecalho, corpo, rodape, ativo)
SELECT 'pagar',
       'Padrao - Contas a pagar',
       'Aviso de vencimento - Conta a pagar',
       'Aviso financeiro',
       'Ola,<br><br>Existe uma conta a pagar proxima do vencimento.<br><br>Fornecedor: {{fornecedor}}<br>Descricao: {{descricao}}<br>Valor: {{valor}}<br>Data de vencimento: {{data_vencimento}}<br><br>Favor verificar e providenciar o pagamento.<br><br>Atenciosamente,<br>{{nome_empresa}}',
       '{{nome_empresa}} - {{email_empresa}} - {{telefone_empresa}}',
       1
WHERE NOT EXISTS (SELECT 1 FROM email_layouts WHERE tipo_aviso = 'pagar');

INSERT INTO email_layouts (tipo_aviso, nome, assunto, cabecalho, corpo, rodape, ativo)
SELECT 'receber',
       'Padrao - Contas a receber',
       'Aviso de vencimento - Conta a receber',
       'Aviso financeiro',
       'Ola {{cliente}},<br><br>Identificamos uma conta a receber proxima do vencimento.<br><br>Descricao: {{descricao}}<br>Valor: {{valor}}<br>Data de vencimento: {{data_vencimento}}<br><br>Caso o pagamento ja tenha sido realizado, favor desconsiderar este aviso.<br><br>Atenciosamente,<br>{{nome_empresa}}',
       '{{nome_empresa}} - {{email_empresa}} - {{telefone_empresa}}',
       1
WHERE NOT EXISTS (SELECT 1 FROM email_layouts WHERE tipo_aviso = 'receber');

INSERT INTO menu (id_menu, menu)
SELECT 31, 'Layout de E-mails'
WHERE NOT EXISTS (SELECT 1 FROM menu WHERE id_menu = 31);
