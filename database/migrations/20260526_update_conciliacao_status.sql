-- Conciliacao profissional: status, usuario, data e diferenca evitam baixa duplicada e indicam divergencias.
ALTER TABLE conciliacao
    ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT 'Pendente',
    ADD COLUMN data_conciliacao DATETIME NULL,
    ADD COLUMN usuario_conciliacao INT NULL,
    ADD COLUMN diferenca DECIMAL(15,2) NULL;
