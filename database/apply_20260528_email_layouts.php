<?php

// Aplicador local da migration 20260528: cria layouts de e-mail e registra o submenu no banco.
$pdo = new PDO('mysql:host=localhost;dbname=financeiro;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$sql = file_get_contents(__DIR__ . '/migrations/20260528_create_email_layouts.sql');
$sql = preg_replace('/^\s*--.*$/m', '', $sql);

foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
    if ($statement === '') {
        continue;
    }

    $pdo->exec($statement);
}

echo 'Migration 20260528 aplicada com sucesso.';
