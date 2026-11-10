<?php
// admin/animais/excluir.php
// Só aceita POST (um link comum não consegue excluir nada).
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../includes/auth.php';
exigirAdmin('../../');
require_once __DIR__ . '/../../includes/animais.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $stmt = $pdo->prepare('SELECT foto FROM animais WHERE id = ?');
    $stmt->execute([$id]);
    $animal = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($animal) {
        $pdo->prepare('DELETE FROM animais WHERE id = ?')->execute([$id]);
        apagarFoto($animal['foto']);
        $_SESSION['sucesso'] = 'Animal excluído com sucesso.';
    } else {
        $_SESSION['erro'] = 'Animal não encontrado.';
    }
}

header('Location: index.php');
exit;