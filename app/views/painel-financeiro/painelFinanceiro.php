<?php
use app\models\PainelFinanceiro;

$painelFinanceiro = new PainelFinanceiro();
$dadosFinanceiros = $painelFinanceiro->dados();

function pfMoeda($valor)
{
    return 'R$ ' . number_format((float) $valor, 2, ',', '.');
}

function pfData($data)
{
    return $data ? date('d/m/Y', strtotime($data)) : '-';
}

function pfStatusClasse($status)
{
    return [
        'vencido' => 'danger',
        'proximo' => 'warning',
        'recebido' => 'success',
        'pago' => 'success',
        'aberto' => 'primary'
    ][$status] ?? 'secondary';
}

function pfTabelaContas(array $contas, $pessoaTitulo)
{
    if (empty($contas)) {
        echo '<tr><td colspan="4" class="text-muted py-3">Nenhuma conta encontrada no periodo.</td></tr>';
        return;
    }

    foreach ($contas as $conta) {
        $status = htmlspecialchars($conta['status']);
        $classe = pfStatusClasse($conta['status']);
        $linha = $conta['status'] === 'vencido' ? 'table-danger' : ($conta['status'] === 'proximo' ? 'table-warning' : '');
        echo '<tr class="' . $linha . '">';
        echo '<td><strong>' . htmlspecialchars($conta['pessoa']) . '</strong><span>' . htmlspecialchars($conta['descricao']) . '</span></td>';
        echo '<td>' . pfMoeda($conta['valor']) . '</td>';
        echo '<td>' . pfData($conta['vencimento']) . '</td>';
        echo '<td><span class="badge bg-' . $classe . '">' . $status . '</span></td>';
        echo '</tr>';
    }
}

function pfListaVencimentos(array $contas)
{
    if (empty($contas)) {
        echo '<li class="text-muted">Sem vencimentos nesse intervalo.</li>';
        return;
    }

    foreach ($contas as $conta) {
        $classe = pfStatusClasse($conta['status']);
        echo '<li><span><strong>' . htmlspecialchars($conta['pessoa']) . '</strong><small>' . htmlspecialchars($conta['descricao']) . ' - ' . pfData($conta['vencimento']) . '</small></span><em class="text-' . $classe . '">' . pfMoeda($conta['valor']) . '</em></li>';
    }
}

$resumo = $dadosFinanceiros['resumo'];
?>

<!-- Painel financeiro: tela exclusiva do perfil Financeiro, separada do painel administrativo. -->
<style>
    .financial-profile-dashboard {
        color: #172033;
        padding: 0 1.25rem 2rem;
    }

    .financial-profile-dashboard .page-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .financial-profile-dashboard h2 {
        margin: 0;
        font-size: 1.65rem;
        font-weight: 800;
        letter-spacing: 0;
    }

    .financial-profile-dashboard .subtitle {
        color: #66758a;
        margin: .25rem 0 0;
    }

    .financial-profile-dashboard .stamp {
        border: 1px solid #d9e1ea;
        background: #fff;
        border-radius: 6px;
        padding: .45rem .7rem;
        color: #66758a;
        white-space: nowrap;
    }

    .finance-summary-grid {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: .85rem;
        margin-bottom: 1rem;
    }

    .finance-card,
    .finance-panel {
        background: #fff;
        border: 1px solid #d9e1ea;
        border-radius: 8px;
        box-shadow: 0 10px 22px rgba(23, 32, 51, .06);
    }

    .finance-card {
        padding: .9rem;
        min-height: 112px;
        border-top: 4px solid var(--accent, #2563eb);
    }

    .finance-card span {
        display: block;
        color: #66758a;
        font-size: .74rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    .finance-card strong {
        display: block;
        margin-top: .55rem;
        font-size: 1.05rem;
        line-height: 1.25;
    }

    .finance-panel {
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .finance-panel h3 {
        margin: 0;
        font-size: 1rem;
        font-weight: 800;
    }

    .finance-panel .panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: .75rem;
    }

    .finance-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
    }

    .finance-table {
        margin: 0;
        font-size: .86rem;
    }

    .finance-table td:first-child span {
        display: block;
        color: #66758a;
        font-size: .78rem;
        margin-top: .1rem;
    }

    .due-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }

    .due-box {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: .85rem;
        background: #f8fafc;
    }

    .due-box h4 {
        font-size: .9rem;
        font-weight: 800;
        margin: 0 0 .55rem;
    }

    .due-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .due-list li {
        display: flex;
        justify-content: space-between;
        gap: .8rem;
        border-top: 1px solid #e2e8f0;
        padding: .55rem 0;
        font-size: .82rem;
    }

    .due-list li:first-child {
        border-top: 0;
        padding-top: 0;
    }

    .due-list small {
        display: block;
        color: #66758a;
    }

    .due-list em {
        font-style: normal;
        font-weight: 800;
        white-space: nowrap;
    }

    .notice-table {
        font-size: .86rem;
    }

    @media (max-width: 1200px) {
        .finance-summary-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .finance-grid-2,
        .due-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 720px) {
        .financial-profile-dashboard .page-head {
            align-items: flex-start;
            flex-direction: column;
        }

        .finance-summary-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="financial-profile-dashboard">
    <div class="page-head">
        <div>
            <h2>Painel financeiro</h2>
            <p class="subtitle">Agenda, vencimentos e avisos financeiros do mês.</p>
        </div>
        <div class="stamp"><i class="bi bi-calendar-check"></i> <?php echo date('d/m/Y H:i'); ?></div>
    </div>

    <div class="finance-summary-grid">
        <div class="finance-card" style="--accent:#2563eb"><span>Total a receber</span><strong><?php echo pfMoeda($resumo['totalReceber']); ?></strong></div>
        <div class="finance-card" style="--accent:#16a34a"><span>Total recebido</span><strong><?php echo pfMoeda($resumo['totalRecebido']); ?></strong></div>
        <div class="finance-card" style="--accent:#dc2626"><span>Total a pagar</span><strong><?php echo pfMoeda($resumo['totalPagar']); ?></strong></div>
        <div class="finance-card" style="--accent:#9333ea"><span>Total pago</span><strong><?php echo pfMoeda($resumo['totalPago']); ?></strong></div>
        <div class="finance-card" style="--accent:#0891b2"><span>Saldo previsto</span><strong><?php echo pfMoeda($resumo['saldoPrevisto']); ?></strong></div>
        <div class="finance-card" style="--accent:#d97706"><span>Total vencido</span><strong><?php echo pfMoeda($resumo['totalVencido']); ?></strong></div>
    </div>

    <div class="finance-grid-2">
        <div class="finance-panel">
            <div class="panel-head">
                <h3>Agenda de contas a receber</h3>
                <a class="btn btn-sm btn-outline-primary" href="?router=ContasReceber/contas_receber">Abrir</a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm finance-table align-middle">
                    <thead><tr><th>Cliente</th><th>Valor</th><th>Vencimento</th><th>Status</th></tr></thead>
                    <tbody><?php pfTabelaContas($dadosFinanceiros['receberMes'], 'Cliente'); ?></tbody>
                </table>
            </div>
        </div>

        <div class="finance-panel">
            <div class="panel-head">
                <h3>Agenda de contas a pagar</h3>
                <a class="btn btn-sm btn-outline-danger" href="?router=ContasPagar/contas_pagar">Abrir</a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm finance-table align-middle">
                    <thead><tr><th>Fornecedor</th><th>Valor</th><th>Vencimento</th><th>Status</th></tr></thead>
                    <tbody><?php pfTabelaContas($dadosFinanceiros['pagarMes'], 'Fornecedor'); ?></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="finance-panel">
        <div class="panel-head">
            <h3>Próximos vencimentos</h3>
            <span class="text-muted small">Separado por contas a receber e contas a pagar</span>
        </div>
        <div class="due-grid">
            <div class="due-box">
                <h4>Vencem hoje</h4>
                <strong class="small text-primary">Receber</strong>
                <ul class="due-list mb-3"><?php pfListaVencimentos($dadosFinanceiros['proximos']['hoje']['receber']); ?></ul>
                <strong class="small text-danger">Pagar</strong>
                <ul class="due-list"><?php pfListaVencimentos($dadosFinanceiros['proximos']['hoje']['pagar']); ?></ul>
            </div>
            <div class="due-box">
                <h4>Próximos 2 dias</h4>
                <strong class="small text-primary">Receber</strong>
                <ul class="due-list mb-3"><?php pfListaVencimentos($dadosFinanceiros['proximos']['doisDias']['receber']); ?></ul>
                <strong class="small text-danger">Pagar</strong>
                <ul class="due-list"><?php pfListaVencimentos($dadosFinanceiros['proximos']['doisDias']['pagar']); ?></ul>
            </div>
            <div class="due-box">
                <h4>Próximos 7 dias</h4>
                <strong class="small text-primary">Receber</strong>
                <ul class="due-list mb-3"><?php pfListaVencimentos($dadosFinanceiros['proximos']['seteDias']['receber']); ?></ul>
                <strong class="small text-danger">Pagar</strong>
                <ul class="due-list"><?php pfListaVencimentos($dadosFinanceiros['proximos']['seteDias']['pagar']); ?></ul>
            </div>
        </div>
    </div>

    <div class="finance-panel">
        <div class="panel-head">
            <h3>Avisos enviados por e-mail</h3>
            <span class="text-muted small">Últimos 10 registros</span>
        </div>
        <div class="table-responsive">
            <table class="table table-sm notice-table align-middle">
                <thead><tr><th>Data e hora</th><th>Destinatário</th><th>Tipo</th><th>Aviso</th><th>Status</th></tr></thead>
                <tbody>
                <?php if (empty($dadosFinanceiros['avisosEmail'])) { ?>
                    <tr><td colspan="5" class="text-muted py-3">Nenhum aviso de e-mail registrado.</td></tr>
                <?php } ?>
                <?php foreach ($dadosFinanceiros['avisosEmail'] as $aviso) { ?>
                    <?php
                    $statusAviso = mb_strtolower((string) $aviso['status'], 'UTF-8');
                    $classeAviso = strpos($statusAviso, 'erro') !== false ? 'danger' : (strpos($statusAviso, 'pend') !== false ? 'warning' : 'success');
                    ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($aviso['enviado_em'])); ?></td>
                        <td><?php echo htmlspecialchars($aviso['destinatario'] ?: 'Nao informado'); ?></td>
                        <td><?php echo $aviso['tipo_conta'] === 'receber' ? 'Contas a receber' : 'Contas a pagar'; ?></td>
                        <td><?php echo htmlspecialchars($aviso['tipo_aviso']); ?></td>
                        <td><span class="badge bg-<?php echo $classeAviso; ?>"><?php echo htmlspecialchars($aviso['status']); ?></span></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
