<?php
$configEmail = $this->obterEmailConfig();
?>

<!-- Configuracao de e-mail: tela criada para parametrizar SMTP e habilitar avisos financeiros. -->
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Configuracao de E-mail</h2>
            <p class="text-muted mb-0">Dados usados para envio de avisos de vencimento, pagamento e recebimento.</p>
        </div>
        <button class="btn btn-outline-primary" type="button" id="btnTestarEmail">
            <i class="bi bi-envelope-check"></i> Testar envio
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form id="formEmailConfig">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($configEmail['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Servidor SMTP</label>
                        <input type="text" class="form-control" name="servidor_smtp" value="<?php echo htmlspecialchars($configEmail['servidor_smtp'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Porta</label>
                        <input type="number" class="form-control" name="porta" value="<?php echo htmlspecialchars($configEmail['porta'] ?? '587', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Criptografia</label>
                        <select class="form-select" name="criptografia">
                            <?php foreach (['TLS', 'SSL', 'Nenhuma'] as $tipo) { ?>
                                <option value="<?php echo $tipo; ?>" <?php echo (($configEmail['criptografia'] ?? 'TLS') === $tipo) ? 'selected' : ''; ?>><?php echo $tipo; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Usuario</label>
                        <input type="text" class="form-control" name="usuario" value="<?php echo htmlspecialchars($configEmail['usuario'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Senha</label>
                        <input type="password" class="form-control" name="senha" value="<?php echo htmlspecialchars($configEmail['senha'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">E-mail remetente</label>
                        <input type="email" class="form-control" name="email_remetente" value="<?php echo htmlspecialchars($configEmail['email_remetente'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nome do remetente</label>
                        <input type="text" class="form-control" name="nome_remetente" value="<?php echo htmlspecialchars($configEmail['nome_remetente'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="ativo" id="emailAtivo" <?php echo !empty($configEmail['ativo']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="emailAtivo">Ativo</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Salvar configuracao
                    </button>
                    <span id="mensagemEmail" class="text-muted"></span>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Configuracao de e-mail: chamadas AJAX separadas evitam recarregar a tela ao salvar/testar SMTP.
$('#formEmailConfig').on('submit', function (event) {
    event.preventDefault();
    $('#mensagemEmail').text('Salvando...');
    $.post('?router=Configuracoes/salvarEmail', $(this).serialize(), function (resposta) {
        $('#mensagemEmail').text(resposta);
    });
});

$('#btnTestarEmail').on('click', function () {
    $('#mensagemEmail').text('Testando conexao SMTP...');
    $.post('?router=Configuracoes/testarEmail', function (resposta) {
        $('#mensagemEmail').text(resposta);
    });
});
</script>
