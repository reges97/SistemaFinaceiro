-- Automacao vendas/financeiro: vincula a conta a receber a venda para evitar lancamentos duplicados.
ALTER TABLE contas_receber
    ADD COLUMN venda_id INT NULL;

-- Indice unico permite apenas uma conta a receber por venda finalizada.
CREATE UNIQUE INDEX uq_contas_receber_venda_id ON contas_receber (venda_id);
