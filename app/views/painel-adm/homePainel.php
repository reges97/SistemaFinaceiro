<?php
// Painel remodelado: variaveis de exibicao centralizadas para facilitar manutencao do resumo financeiro.
$agoraPainel = date("d/m/Y H:i");
$hojePainel = date("d/m/Y");
$saldoContaAtual = isset($saldo4F) ? number_format((float) $saldo4F, 2, ',', '.') : '0,00';
$totalCaixaPainel = $totalCon ?? '0,00';
$entradaCaixaPainel = $entradacxf ?? '0,00';
$saidaCaixaPainel = $saidacxf ?? '0,00';
$debitoPainel = $debitoTotalF ?? '0,00';
$creditoPainel = $creditoTotalF ?? '0,00';
$totalReceberPainel = $totalR ?? '0,00';
$totalPagarPainel = $total ?? '0,00';
$qtdReceberPainel = $cr1 ?? 0;
$qtdPagarPainel = $pg1 ?? 0;
$statusCaixaPainel = $aberto ?? 'Sem caixa';
$dataAberturaPainel = $dataAbF ?? '-';
?>

<!-- Estilos do novo painel: layout profissional com KPIs, graficos responsivos e agenda compacta. -->
<style>
  :root {
    --sf-page: #f3f6f8;
    --sf-panel: #ffffff;
    --sf-text: #18212f;
    --sf-muted: #6f7d8f;
    --sf-border: #dce3ea;
    --sf-blue: #2563eb;
    --sf-green: #16a34a;
    --sf-red: #dc2626;
    --sf-amber: #d97706;
    --sf-cyan: #0891b2;
  }

  body {
    background: var(--sf-page) !important;
  }

  .header .container-fluid {
    overflow: hidden;
  }

  .header h1 img {
    max-width: min(100%, 360px);
    height: auto;
  }

  .finance-dashboard {
    padding: 0 1.25rem 2rem;
    color: var(--sf-text);
  }

  .dashboard-toolbar {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.25rem;
  }

  .dashboard-title {
    margin: 0;
    font-size: 1.55rem;
    font-weight: 700;
    letter-spacing: 0;
  }

  .dashboard-subtitle {
    margin: .25rem 0 0;
    color: var(--sf-muted);
    font-size: .92rem;
  }

  .dashboard-stamp {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    min-height: 2.25rem;
    padding: .4rem .7rem;
    border: 1px solid var(--sf-border);
    border-radius: 6px;
    background: var(--sf-panel);
    color: var(--sf-muted);
    font-size: .85rem;
    white-space: nowrap;
  }

  .kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
  }

  .kpi-card,
  .chart-panel,
  .agenda-panel {
    background: var(--sf-panel);
    border: 1px solid var(--sf-border);
    border-radius: 8px;
    box-shadow: 0 10px 24px rgba(24, 33, 47, .06);
  }

  .kpi-card {
    position: relative;
    overflow: hidden;
    min-height: 142px;
    padding: 1rem;
  }

  .kpi-card::before {
    content: "";
    position: absolute;
    inset: 0 0 auto 0;
    height: 4px;
    background: var(--accent, var(--sf-blue));
  }

  .kpi-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    margin-bottom: .9rem;
  }

  .kpi-label {
    color: var(--sf-muted);
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
  }

  .kpi-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.15rem;
    height: 2.15rem;
    border-radius: 6px;
    background: color-mix(in srgb, var(--accent, var(--sf-blue)) 12%, white);
    color: var(--accent, var(--sf-blue));
    font-size: 1.15rem;
  }

  .kpi-value {
    margin: 0;
    font-size: 1.35rem;
    font-weight: 800;
    line-height: 1.2;
  }

  .kpi-meta {
    display: flex;
    justify-content: space-between;
    gap: .75rem;
    margin: .75rem 0 0;
    color: var(--sf-muted);
    font-size: .82rem;
  }

  .kpi-meta strong {
    color: var(--sf-text);
    font-weight: 700;
  }

  .kpi-link {
    color: inherit;
    text-decoration: none;
  }

  .kpi-link:hover {
    color: var(--accent, var(--sf-blue));
  }

  .chart-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
    margin-top: 1rem;
  }

  .chart-panel {
    min-height: 360px;
    padding: 1rem;
  }

  .chart-panel-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: .75rem;
  }

  .chart-title {
    margin: 0;
    font-size: 1rem;
    font-weight: 800;
  }

  .chart-subtitle {
    margin: .2rem 0 0;
    color: var(--sf-muted);
    font-size: .82rem;
  }

  .chart-total {
    text-align: right;
    color: var(--sf-muted);
    font-size: .78rem;
  }

  .chart-total strong {
    display: block;
    color: var(--sf-text);
    font-size: .98rem;
  }

  .chart-wrap {
    position: relative;
    height: 245px;
  }

  .chart-wrap canvas {
    width: 100% !important;
    height: 100% !important;
  }

  .panel-metrics {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: .65rem;
    margin-top: .8rem;
  }

  .panel-metric {
    padding: .65rem;
    border: 1px solid var(--sf-border);
    border-radius: 6px;
    background: #f8fafc;
  }

  .panel-metric span {
    display: block;
    color: var(--sf-muted);
    font-size: .75rem;
  }

  .panel-metric strong {
    display: block;
    margin-top: .15rem;
    font-size: .9rem;
  }

  .agenda-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
    margin-top: 1rem;
  }

  .agenda-panel {
    padding: 1rem;
  }

  .agenda-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    margin-bottom: .75rem;
  }

  .agenda-title {
    margin: 0;
    font-size: 1rem;
    font-weight: 800;
  }

  .agenda-count {
    padding: .25rem .5rem;
    border-radius: 6px;
    background: #eef2f7;
    color: var(--sf-muted);
    font-size: .78rem;
    font-weight: 700;
  }

  .agenda-list {
    display: grid;
    gap: .65rem;
  }

  .agenda-item {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: .75rem;
    padding: .75rem;
    border: 1px solid var(--sf-border);
    border-radius: 6px;
    background: #fbfcfe;
    color: inherit;
    text-decoration: none;
  }

  .agenda-item:hover {
    border-color: color-mix(in srgb, var(--accent, var(--sf-blue)) 40%, var(--sf-border));
    color: inherit;
  }

  .agenda-name {
    margin: 0;
    font-size: .92rem;
    font-weight: 800;
  }

  .agenda-desc {
    margin: .2rem 0 0;
    color: var(--sf-muted);
    font-size: .8rem;
  }

  .agenda-meta {
    color: var(--sf-muted);
    font-size: .78rem;
    text-align: right;
  }

  .agenda-status {
    display: inline-block;
    margin-bottom: .3rem;
    padding: .16rem .4rem;
    border-radius: 6px;
    background: color-mix(in srgb, var(--accent, var(--sf-blue)) 12%, white);
    color: var(--accent, var(--sf-blue));
    font-weight: 800;
  }

  .empty-state {
    padding: 1rem;
    border: 1px dashed var(--sf-border);
    border-radius: 6px;
    color: var(--sf-muted);
    background: #fbfcfe;
    text-align: center;
  }

  @media (max-width: 1200px) {
    .kpi-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 992px) {
    .finance-dashboard {
      padding: 0 .75rem 1.5rem;
    }

    .dashboard-toolbar,
    .chart-panel-header,
    .agenda-header {
      align-items: flex-start;
      flex-direction: column;
    }

    .dashboard-stamp,
    .chart-total {
      text-align: left;
    }

    .chart-grid,
    .agenda-grid {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 640px) {
    .header h1 img {
      max-width: 240px;
    }

    .header-nav {
      margin-left: 0 !important;
    }

    .kpi-grid,
    .panel-metrics {
      grid-template-columns: 1fr;
    }

    .agenda-item {
      grid-template-columns: 1fr;
    }

    .agenda-meta {
      text-align: left;
    }
  }
</style>

<!-- Nova estrutura do dashboard financeiro. -->
<main class="finance-dashboard">
  <!-- Cabecalho do painel com titulo e horario da ultima atualizacao. -->
  <section class="dashboard-toolbar" aria-label="Resumo do painel">
    <div>
      <h1 class="dashboard-title">Painel financeiro</h1>
      <p class="dashboard-subtitle">Visao consolidada de caixa, contas, agenda e saldos bancarios.</p>
    </div>
    <div class="dashboard-stamp">
      <i class="bi bi-clock-history"></i>
      Atualizado em <?php echo $agoraPainel; ?>
    </div>
  </section>

  <!-- Cards principais: caixa, contas bancarias, contas a receber e contas a pagar. -->
  <section class="kpi-grid" aria-label="Indicadores financeiros">
    <article class="kpi-card" style="--accent: var(--sf-blue)">
      <div class="kpi-head">
        <span class="kpi-label">Caixa</span>
        <span class="kpi-icon"><i class="bi bi-wallet2"></i></span>
      </div>
      <p class="kpi-value">R$ <?php echo $totalCaixaPainel; ?></p>
      <div class="kpi-meta">
        <span>Status: <strong><?php echo $statusCaixaPainel; ?></strong></span>
        <span>Abertura: <strong><?php echo $dataAberturaPainel; ?></strong></span>
      </div>
    </article>

    <article class="kpi-card" style="--accent: var(--sf-cyan)">
      <div class="kpi-head">
        <span class="kpi-label">Contas bancarias</span>
        <span class="kpi-icon"><i class="bi bi-bank"></i></span>
      </div>
      <p class="kpi-value">R$ <?php echo $saldoContaAtual; ?></p>
      <div class="kpi-meta">
        <span>Debito: <strong>R$ <?php echo $debitoPainel; ?></strong></span>
        <span>Credito: <strong>R$ <?php echo $creditoPainel; ?></strong></span>
      </div>
    </article>

    <article class="kpi-card" style="--accent: var(--sf-green)">
      <div class="kpi-head">
        <span class="kpi-label">A receber</span>
        <span class="kpi-icon"><i class="bi bi-arrow-down-circle"></i></span>
      </div>
      <p class="kpi-value">R$ <?php echo $totalReceberPainel; ?></p>
      <div class="kpi-meta">
        <span>Hoje: <strong><?php echo $hoje; ?></strong></span>
        <a class="kpi-link" href="?router=ContasReceber/contas_receber"><strong><?php echo $qtdReceberPainel; ?></strong> pendentes</a>
      </div>
    </article>

    <article class="kpi-card" style="--accent: var(--sf-red)">
      <div class="kpi-head">
        <span class="kpi-label">A pagar</span>
        <span class="kpi-icon"><i class="bi bi-arrow-up-circle"></i></span>
      </div>
      <p class="kpi-value">R$ <?php echo $totalPagarPainel; ?></p>
      <div class="kpi-meta">
        <span>Hoje: <strong><?php echo $hoje; ?></strong></span>
        <a class="kpi-link" href="?router=ContasPagar/contas_pagar"><strong><?php echo $qtdPagarPainel; ?></strong> pendentes</a>
      </div>
    </article>
  </section>

  <!-- Graficos remodelados com area fixa, legenda e totais de apoio. -->
  <section class="chart-grid" aria-label="Graficos financeiros">
    <article class="chart-panel">
      <div class="chart-panel-header">
        <div>
          <h2 class="chart-title">Controle de caixa diario</h2>
          <p class="chart-subtitle">Entradas e saidas registradas no caixa.</p>
        </div>
        <div class="chart-total">
          Saldo atual
          <strong>R$ <?php echo $totalCaixaPainel; ?></strong>
        </div>
      </div>
      <div class="chart-wrap"><canvas id="myAreaChart"></canvas></div>
      <div class="panel-metrics">
        <div class="panel-metric"><span>Entradas</span><strong class="text-success">R$ <?php echo $entradaCaixaPainel; ?></strong></div>
        <div class="panel-metric"><span>Saidas</span><strong class="text-danger">R$ <?php echo $saidaCaixaPainel; ?></strong></div>
        <div class="panel-metric"><span>Data</span><strong><?php echo $hojePainel; ?></strong></div>
      </div>
    </article>

    <article class="chart-panel">
      <div class="chart-panel-header">
        <div>
          <h2 class="chart-title">Controle das contas</h2>
          <p class="chart-subtitle">Evolucao de saldos por conta bancaria.</p>
        </div>
        <div class="chart-total">
          Debito/Credito
          <strong>R$ <?php echo $totalDC ?? '0,00'; ?></strong>
        </div>
      </div>
      <div class="chart-wrap"><canvas id="myAreaChart2"></canvas></div>
      <div class="panel-metrics">
        <div class="panel-metric"><span>Debitos</span><strong class="text-success">R$ <?php echo $debitoPainel; ?></strong></div>
        <div class="panel-metric"><span>Creditos</span><strong class="text-danger">R$ <?php echo $creditoPainel; ?></strong></div>
        <div class="panel-metric"><span>Conta</span><strong><?php echo $nomeConta ?? '-'; ?></strong></div>
      </div>
    </article>

    <article class="chart-panel">
      <div class="chart-panel-header">
        <div>
          <h2 class="chart-title">Saldo de movimentacao</h2>
          <p class="chart-subtitle">Comparativo consolidado de entradas e saidas.</p>
        </div>
        <div class="chart-total">
          Periodo
          <strong><?php echo $hojePainel; ?></strong>
        </div>
      </div>
      <div class="chart-wrap"><canvas id="myBarChart"></canvas></div>
      <div class="panel-metrics">
        <div class="panel-metric"><span>Entradas</span><strong class="text-success">R$ <?php echo @$totalMov1f; ?></strong></div>
        <div class="panel-metric"><span>Saidas</span><strong class="text-danger">R$ <?php echo @$totalMov2f; ?></strong></div>
        <div class="panel-metric"><span>Atualizado</span><strong><?php echo $agoraPainel; ?></strong></div>
      </div>
    </article>

    <article class="chart-panel">
      <div class="chart-panel-header">
        <div>
          <h2 class="chart-title">Saldo por banco</h2>
          <p class="chart-subtitle">Distribuicao atual dos saldos cadastrados.</p>
        </div>
      </div>
      <div class="chart-wrap"><canvas id="myPieChart"></canvas></div>
    </article>
  </section>

  <!-- Agenda do dia: listas compactas para vencimentos a receber e a pagar. -->
  <section class="agenda-grid" aria-label="Agenda de vencimentos">
    <article class="agenda-panel" style="--accent: var(--sf-green)">
      <div class="agenda-header">
        <h2 class="agenda-title"><i class="bi bi-calendar-check me-1"></i> Agenda a receber hoje</h2>
        <span class="agenda-count"><?php echo $qtdReceberPainel; ?> item(ns)</span>
      </div>
      <div class="agenda-list">
        <?php
        $listaReceber = new \app\controllers\ContasReceber;
        $dadosReceber = $listaReceber->receberCard();
        if (count($dadosReceber) === 0) {
            echo '<div class="empty-state">Nenhum recebimento vence hoje.</div>';
        }
        for ($i = 0; $i < count($dadosReceber); $i++) {
            $id = $dadosReceber[$i]['id'];
            $nome = $dadosReceber[$i]['descricao'];
            $descricao = $dadosReceber[$i]['plano_conta'];
            $status = $dadosReceber[$i]['status'];
            $documento = $dadosReceber[$i]['documento'];
            $vencimento = $dadosReceber[$i]['vencimento'];
            $data_venc = implode('/', array_reverse(explode('-', $vencimento)));
        ?>
          <a class="agenda-item" href="?router=ContasReceber/contas_receber">
            <div>
              <p class="agenda-name"><?php echo $nome; ?></p>
              <p class="agenda-desc"><?php echo $descricao; ?></p>
            </div>
            <div class="agenda-meta">
              <span class="agenda-status"><?php echo $status; ?></span>
              <div>#<?php echo $id; ?> | Doc. <?php echo $documento; ?></div>
              <div><?php echo $data_venc; ?></div>
            </div>
          </a>
        <?php } ?>
      </div>
    </article>

    <article class="agenda-panel" style="--accent: var(--sf-red)">
      <div class="agenda-header">
        <h2 class="agenda-title"><i class="bi bi-calendar-x me-1"></i> Agenda a pagar hoje</h2>
        <span class="agenda-count"><?php echo $qtdPagarPainel; ?> item(ns)</span>
      </div>
      <div class="agenda-list">
        <?php
        $listaPagar = new \app\controllers\ContasPagar;
        $dadosPagar = $listaPagar->listarCard();
        if (count($dadosPagar) === 0) {
            echo '<div class="empty-state">Nenhum pagamento vence hoje.</div>';
        }
        for ($i = 0; $i < count($dadosPagar); $i++) {
            $id = $dadosPagar[$i]['id'];
            $nome = $dadosPagar[$i]['descricao'];
            $descricao = $dadosPagar[$i]['plano_conta'];
            $status = $dadosPagar[$i]['status'];
            $documento = $dadosPagar[$i]['documento'];
            $vencimento = $dadosPagar[$i]['vencimento'];
            $data_venc = implode('/', array_reverse(explode('-', $vencimento)));
        ?>
          <a class="agenda-item" href="?router=ContasPagar/contas_pagar">
            <div>
              <p class="agenda-name"><?php echo $nome; ?></p>
              <p class="agenda-desc"><?php echo $descricao; ?></p>
            </div>
            <div class="agenda-meta">
              <span class="agenda-status"><?php echo $status; ?></span>
              <div>#<?php echo $id; ?> | Doc. <?php echo $documento; ?></div>
              <div><?php echo $data_venc; ?></div>
            </div>
          </a>
        <?php } ?>
      </div>
    </article>
  </section>
</main>

<script src="config/vendors/chart.js/js/chart.min.js"></script>
<script>
// Configuracao dos graficos: usa Chart.js local, moeda brasileira e opcoes compativeis com a versao instalada.
if (Chart.defaults.global) {
  Chart.defaults.global.defaultFontFamily = 'Segoe UI, Roboto, Arial, sans-serif';
  Chart.defaults.global.defaultFontColor = '#526071';
  Chart.defaults.global.elements.line.borderWidth = 3;
  Chart.defaults.global.elements.point.radius = 3;
  Chart.defaults.global.elements.point.hoverRadius = 5;
} else {
  Chart.defaults.font = Chart.defaults.font || {};
  Chart.defaults.font.family = 'Segoe UI, Roboto, Arial, sans-serif';
  Chart.defaults.color = '#526071';
}

const moneyFormatter = new Intl.NumberFormat('pt-BR', {
  style: 'currency',
  currency: 'BRL'
});

function asArray(value) {
  return Array.isArray(value) ? value : [];
}

function numericArray(value) {
  return asArray(value).map((item) => Number(item || 0));
}

function chartMax(values) {
  const max = Math.max(0, ...values);
  if (max <= 0) return 100;
  return Math.ceil((max * 1.18) / 100) * 100;
}

function makeGradient(ctx, color) {
  const gradient = ctx.createLinearGradient(0, 0, 0, 240);
  gradient.addColorStop(0, color);
  gradient.addColorStop(1, 'rgba(255,255,255,0)');
  return gradient;
}

function currencyTooltip(tooltipItem, data) {
  const label = data.datasets[tooltipItem.datasetIndex].label || '';
  return label + ': ' + moneyFormatter.format(Number(tooltipItem.yLabel || 0));
}

function currencyTooltipV3(context) {
  const label = context.dataset.label || '';
  return label + ': ' + moneyFormatter.format(Number(context.parsed.y || 0));
}

const entrada = numericArray(<?php echo $entrada ?: '[]'; ?>);
const saida = numericArray(<?php echo $saida ?: '[]'; ?>);
const tipo = asArray(<?php echo $tipo ?: '[]'; ?>);
const labelsCaixa = tipo.length ? tipo : asArray(<?php echo $mof ?: '[]'; ?>);

const caixaCtx = document.getElementById('myAreaChart').getContext('2d');
new Chart(caixaCtx, {
  type: 'line',
  data: {
    labels: labelsCaixa,
    datasets: [{
      label: 'Entrada',
      data: entrada,
      backgroundColor: makeGradient(caixaCtx, 'rgba(22, 163, 74, .22)'),
      borderColor: '#16a34a',
      pointBackgroundColor: '#16a34a',
      fill: true
    }, {
      label: 'Saida',
      data: saida,
      backgroundColor: makeGradient(caixaCtx, 'rgba(220, 38, 38, .18)'),
      borderColor: '#dc2626',
      pointBackgroundColor: '#dc2626',
      fill: true
    }]
  },
  options: {
    maintainAspectRatio: false,
    plugins: {
      legend: { display: true, position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } },
      tooltip: { callbacks: { label: currencyTooltipV3 } }
    },
    scales: {
      x: { grid: { display: false }, ticks: { maxTicksLimit: 8 } },
      y: { beginAtZero: true, max: chartMax(entrada.concat(saida)), ticks: { callback: (value) => moneyFormatter.format(value) }, grid: { color: 'rgba(148, 163, 184, .22)' } }
    }
  }
});

const salconta = asArray(<?php echo $saldoConta ?: '[]'; ?>);
const salsaldo = numericArray(<?php echo $saldoSaldo ?: '[]'; ?>);
const contasCtx = document.getElementById('myAreaChart2').getContext('2d');
new Chart(contasCtx, {
  type: 'line',
  data: {
    labels: salconta,
    datasets: [{
      label: 'Saldo',
      data: salsaldo,
      backgroundColor: makeGradient(contasCtx, 'rgba(37, 99, 235, .20)'),
      borderColor: '#2563eb',
      pointBackgroundColor: '#2563eb',
      fill: true
    }]
  },
  options: {
    maintainAspectRatio: false,
    plugins: {
      legend: { display: true, position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } },
      tooltip: { callbacks: { label: currencyTooltipV3 } }
    },
    scales: {
      x: { grid: { display: false }, ticks: { maxTicksLimit: 8 } },
      y: { beginAtZero: true, max: chartMax(salsaldo), ticks: { callback: (value) => moneyFormatter.format(value) }, grid: { color: 'rgba(148, 163, 184, .22)' } }
    }
  }
});

const mov1 = numericArray(<?php echo $mov1f ?: '[]'; ?>);
const mov2 = numericArray(<?php echo $mov2f ?: '[]'; ?>);
const barLabels = mov1.map((_, index) => 'Mov. ' + (index + 1));
const barCtx = document.getElementById('myBarChart').getContext('2d');
new Chart(barCtx, {
  type: 'bar',
  data: {
    labels: barLabels,
    datasets: [{
      label: 'Entrada',
      backgroundColor: '#16a34a',
      data: mov1
    }, {
      label: 'Saida',
      backgroundColor: '#dc2626',
      data: mov2
    }]
  },
  options: {
    maintainAspectRatio: false,
    plugins: {
      legend: { display: true, position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } },
      tooltip: { callbacks: { label: currencyTooltipV3 } }
    },
    scales: {
      x: { grid: { display: false } },
      y: { beginAtZero: true, max: chartMax(mov1.concat(mov2)), ticks: { callback: (value) => moneyFormatter.format(value) }, grid: { color: 'rgba(148, 163, 184, .22)' } }
    }
  }
});

const banco = asArray(<?php echo $banco ?: '[]'; ?>);
const bancosald = numericArray(<?php echo $bancoSaldo ?: '[]'; ?>);
const pieCtx = document.getElementById('myPieChart').getContext('2d');
new Chart(pieCtx, {
  type: 'doughnut',
  data: {
    labels: banco,
    datasets: [{
      data: bancosald,
      backgroundColor: ['#2563eb', '#16a34a', '#d97706', '#dc2626', '#0891b2', '#7c3aed'],
      borderColor: '#ffffff',
      borderWidth: 3
    }]
  },
  options: {
    maintainAspectRatio: false,
    cutout: '62%',
    plugins: {
      legend: { display: true, position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } },
      tooltip: { callbacks: { label: function(context) {
      const label = context.label || '';
      const value = context.parsed || 0;
      return label + ': ' + moneyFormatter.format(Number(value));
    }}}
    }
  }
});
</script>
