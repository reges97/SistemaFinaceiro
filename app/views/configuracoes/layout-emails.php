<?php
$layoutsEmail = $this->listarEmailLayouts();
$layoutSelecionado = !empty($_GET['id']) ? $this->obterEmailLayout((int) $_GET['id']) : ($layoutsEmail[0] ?? []);
$layoutJson = json_encode($layoutsEmail, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
$variaveisLayout = [
    '{{cliente}}',
    '{{fornecedor}}',
    '{{descricao}}',
    '{{valor}}',
    '{{data_vencimento}}',
    '{{data_pagamento}}',
    '{{nome_empresa}}',
    '{{telefone_empresa}}',
    '{{email_empresa}}',
    '{{link_pagamento}}'
];
?>

<!-- Layouts de e-mail: tela para personalizar avisos financeiros sem alterar codigo-fonte. -->
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Layout de E-mails</h2>
            <p class="text-muted mb-0">Configure os modelos usados nos avisos de contas a pagar e a receber.</p>
        </div>
        <button class="btn btn-outline-primary" type="button" id="btnNovoLayout">
            <i class="bi bi-plus-circle"></i> Novo layout
        </button>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <strong>Modelos cadastrados</strong>
                </div>
                <div class="list-group list-group-flush" id="listaLayoutsEmail">
                    <?php foreach ($layoutsEmail as $layout) { ?>
                        <button type="button" class="list-group-item list-group-item-action layout-email-item" data-id="<?php echo (int) $layout['id']; ?>">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <strong><?php echo htmlspecialchars($layout['nome'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                    <div class="small text-muted"><?php echo $layout['tipo_aviso'] === 'pagar' ? 'Contas a pagar' : 'Contas a receber'; ?></div>
                                </div>
                                <span class="badge <?php echo !empty($layout['ativo']) ? 'bg-success' : 'bg-secondary'; ?>">
                                    <?php echo !empty($layout['ativo']) ? 'Ativo' : 'Inativo'; ?>
                                </span>
                            </div>
                        </button>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form id="formLayoutEmail">
                        <input type="hidden" name="id" id="layout_id" value="<?php echo htmlspecialchars($layoutSelecionado['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Tipo do aviso</label>
                                <select class="form-select" name="tipo_aviso" id="tipo_aviso">
                                    <option value="pagar" <?php echo (($layoutSelecionado['tipo_aviso'] ?? 'pagar') === 'pagar') ? 'selected' : ''; ?>>Contas a pagar</option>
                                    <option value="receber" <?php echo (($layoutSelecionado['tipo_aviso'] ?? '') === 'receber') ? 'selected' : ''; ?>>Contas a receber</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Nome do layout</label>
                                <input type="text" class="form-control" name="nome" id="nome" value="<?php echo htmlspecialchars($layoutSelecionado['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Assunto do e-mail</label>
                                <input type="text" class="form-control" name="assunto" id="assunto" value="<?php echo htmlspecialchars($layoutSelecionado['assunto'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Cabeçalho</label>
                                <input type="text" class="form-control" name="cabecalho" id="cabecalho" value="<?php echo htmlspecialchars($layoutSelecionado['cabecalho'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Corpo da mensagem HTML</label>
                                <textarea class="form-control" name="corpo" id="corpo" rows="11" required><?php echo htmlspecialchars($layoutSelecionado['corpo'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                <small class="text-muted">Use HTML simples, como &lt;br&gt;, &lt;strong&gt; e as variáveis abaixo.</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Rodapé</label>
                                <textarea class="form-control" name="rodape" id="rodape" rows="3"><?php echo htmlspecialchars($layoutSelecionado['rodape'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" name="ativo" id="ativo" <?php echo !empty($layoutSelecionado['ativo']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="ativo">Ativo</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">E-mail para teste</label>
                                <input type="email" class="form-control" id="email_teste" placeholder="Deixe vazio para usar o remetente">
                            </div>
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Salvar layout
                            </button>
                            <button type="button" class="btn btn-outline-success" id="btnTestarLayout">
                                <i class="bi bi-envelope-check"></i> Enviar e-mail de teste
                            </button>
                            <span id="mensagemLayoutEmail" class="text-muted"></span>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white">
                    <strong>Variáveis disponíveis</strong>
                </div>
                <div class="card-body">
                    <!-- Variaveis de layout: exibidas como referencia para evitar erro de digitacao nos modelos. -->
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($variaveisLayout as $variavel) { ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary variavel-layout" data-variavel="<?php echo htmlspecialchars($variavel, ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($variavel, ENT_QUOTES, 'UTF-8'); ?>
                            </button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Layouts de e-mail: carrega modelos cadastrados sem recarregar a pagina.
const layoutsEmail = <?php echo $layoutJson ?: '[]'; ?>;

function preencherLayoutEmail(layout) {
    $('#layout_id').val(layout.id || '');
    $('#tipo_aviso').val(layout.tipo_aviso || 'pagar');
    $('#nome').val(layout.nome || '');
    $('#assunto').val(layout.assunto || '');
    $('#cabecalho').val(layout.cabecalho || '');
    $('#corpo').val(layout.corpo || '');
    $('#rodape').val(layout.rodape || '');
    $('#ativo').prop('checked', String(layout.ativo || '0') === '1');
}

$('.layout-email-item').on('click', function () {
    const id = String($(this).data('id'));
    const layout = layoutsEmail.find(item => String(item.id) === id);
    if (layout) {
        preencherLayoutEmail(layout);
        $('#mensagemLayoutEmail').text('Layout carregado para edicao.');
    }
});

$('#btnNovoLayout').on('click', function () {
    preencherLayoutEmail({ tipo_aviso: 'pagar', ativo: 1 });
    $('#mensagemLayoutEmail').text('Novo layout iniciado.');
});

$('.variavel-layout').on('click', function () {
    const variavel = $(this).data('variavel');
    const campo = $('#corpo');
    campo.val(campo.val() + variavel);
    campo.focus();
});

$('#formLayoutEmail').on('submit', function (event) {
    event.preventDefault();
    $('#mensagemLayoutEmail').text('Salvando...');
    $.post('?router=Configuracoes/salvarLayoutEmail', $(this).serialize(), function (resposta) {
        $('#mensagemLayoutEmail').text(resposta);
    });
});

$('#btnTestarLayout').on('click', function () {
    $('#mensagemLayoutEmail').text('Enviando teste...');
    const dados = $('#formLayoutEmail').serialize()
        + '&layout_id=' + encodeURIComponent($('#layout_id').val())
        + '&email_teste=' + encodeURIComponent($('#email_teste').val());
    $.post('?router=Configuracoes/testarLayoutEmail', dados, function (resposta) {
        $('#mensagemLayoutEmail').text(resposta);
    });
});
</script>
