<?php
// Cabeçalho e menu das páginas.
// Antes de incluir este arquivo, a página precisa:
//   1) já ter incluído o includes/auth.php
//   2) definir $base  -> '' na raiz do projeto, '../' dentro de admin/
//   3) definir $titulo -> título da aba do navegador
$base   = $base   ?? '';
$titulo = $titulo ?? 'Início';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo) ?> | Adoção de Animais</title>
    <link rel="stylesheet" href="<?= e($base) ?>css/style.css">
</head>
<body>

<header class="topo">
    <a class="logo" href="<?= e($base) ?>index.php">🐾 Adoção de Animais</a>

    <nav>
        <a href="<?= e($base) ?>index.php">Início</a>

        <?php if (ehAdmin()): ?>
            <a href="<?= e($base) ?>admin/index.php">Painel admin</a>
        <?php endif; ?>

        <?php if (estaLogado()): ?>
            <span class="ola">Olá, <?= e($_SESSION['usuario_nome']) ?></span>
            <a href="<?= e($base) ?>logout.php">Sair</a>
        <?php else: ?>
            <a href="<?= e($base) ?>login.php">Entrar</a>
            <a class="btn-link" href="<?= e($base) ?>cadastro.php">Cadastrar</a>
        <?php endif; ?>
    </nav>
</header>

<main class="conteudo">