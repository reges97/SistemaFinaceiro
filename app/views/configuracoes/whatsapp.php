<?php
$configWhatsapp = $this->obterWhatsappConfig();
?>

<!-- Configuracao de WhatsApp: tela criada para parametrizar API usada nos avisos financeiros. -->
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Configuracao de WhatsApp</h2>
            <p class="text-muted mb-0">Dados da API que podera enviar avisos de vencimento, pagamento e recebimento.</p>
        </div>
        <button class="btn btn-outline-primary" type="button" id="btnTestarWhatsapp">
            <i class="bi bi-chat-dots"></i> Testar envio
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form id="formWhatsappConfig">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($configWhatsapp['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Provedor/API utilizada</label>
                        <input type="text" class="form-control" name="provedor" value="<?php echo htmlspecialchars($configWhatsapp['provedor'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Numero remetente</label>
                        <input type="text" class="form-control" name="numero_remetente" value="<?php echo htmlspecialchars($configWhatsapp['numero_remetente'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">URL da API</label>
                        <input type="url" class="form-control" name="url_api" value="<?php echo htmlspecialchars($configWhatsapp['url_api'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Token/Chave de acesso</label>
                        <textarea class="form-control" name="token_acesso" rows="4" required><?php echo htmlspecialchars($configWhatsapp['token_acesso'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="ativo" id="whatsappAtivo" <?php echo !empty($configWhatsapp['ativo']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="whatsappAtivo">Ativo</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Salvar configuracao
                    </button>
                    <span id="mensagemWhatsapp" class="text-muted"></span>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Configuracao de WhatsApp: chamadas AJAX separadas para salvar e testar a API configurada.
$('#formWhatsappConfig').on('submit', function (event) {
    event.preventDefault();
    $('#mensagemWhatsapp').text('Salvando...');
    $.post('?router=Configuracoes/salvarWhatsapp', $(this).serialize(), function (resposta) {
        $('#mensagemWhatsapp').text(resposta);
    });
});

$('#btnTestarWhatsapp').on('click', function () {
    $('#mensagemWhatsapp').text('Testando API...');
    $.post('?router=Configuracoes/testarWhatsapp', function (resposta) {
        $('#mensagemWhatsapp').text(resposta);
    });
});
</script>
