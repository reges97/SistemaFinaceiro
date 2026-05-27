-- Indices financeiros: aceleram filtros por status, vencimento e vinculos de movimentacao.
-- A aplicacao cria estes indices de forma segura via rotina de migracao/check no ambiente local.

-- contas_pagar: usado em listagens, baixas, recorrencias e agenda de vencimentos.
-- idx_contas_pagar_status_vencimento(status, vencimento)

-- contas_receber: usado em listagens, baixas, recorrencias e agenda de vencimentos.
-- idx_contas_receber_status_vencimento(status, vencimento)

-- movimentacoes: usado para localizar lancamentos de contas pagas/recebidas.
-- idx_movimentacoes_conta_pag_tipo(conta_pag, tipo)
-- idx_movimentacoes_conta_rec_tipo(conta_rec, tipo)

-- caixa: usado para localizar caixa aberto.
-- idx_caixa_status(status)

-- saldo_conta: usado em relatorios e graficos por data.
-- idx_saldo_conta_data(data)

-- bancarias: usado nas baixas por conta de entrada/saida.
-- idx_bancarias_banco(banco)
