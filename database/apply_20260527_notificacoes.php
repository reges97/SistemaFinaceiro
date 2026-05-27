<?php

// Aplicador local da migration 20260527: cria tabelas e adiciona colunas apenas se ainda nao existirem.
$pdo = new PDO('mysql:host=localhost;dbname=financeiro;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$sql = file_get_contents(__DIR__ . '/migrations/20260527_create_notificacoes_configuracoes.sql');
$sql = preg_replace('/^\s*--.*$/m', '', $sql);
foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
    if ($statement === '') {
        continue;
    }

    $pdo->exec($statement);
}

$colunas = [
    'aviso_vencimento' => 'TINYINT(1) NOT NULL DEFAULT 0',
    'aviso_baixa' => 'TINYINT(1) NOT NULL DEFAULT 0',
    'aviso_forma' => "ENUM('email','whatsapp','ambos') NOT NULL DEFAULT 'email'",
    'aviso_dias' => 'INT NOT NULL DEFAULT 2'
];

foreach (['contas_pagar', 'contas_receber'] as $tabela) {
    foreach ($colunas as $coluna => $definicao) {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :tabela AND COLUMN_NAME = :coluna');
        $stmt->execute([
            ':tabela' => $tabela,
            ':coluna' => $coluna
        ]);

        if ((int) $stmt->fetchColumn() === 0) {
            $pdo->exec("ALTER TABLE {$tabela} ADD COLUMN {$coluna} {$definicao}");
        }
    }
}

$pdo->exec("INSERT IGNORE INTO notificacoes_configuracoes
    (tipo_conta, aviso_vencimento, aviso_baixa, aviso_forma, aviso_dias, ativo)
    VALUES ('pagar', 1, 1, 'email', 2, 1), ('receber', 1, 1, 'email', 2, 1)");

echo 'Migration 20260527 aplicada com sucesso.';
