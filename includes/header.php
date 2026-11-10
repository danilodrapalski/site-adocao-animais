<?php
// Antes de incluir: $base ('' / '../' / '../../') e $titulo precisam estar definidos.
$base   = $base   ?? '';
$titulo = $titulo ?? 'Início';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo) ?> | Adoção de Animais</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&family=Young+Serif&display=swap">
    <link rel="stylesheet" type="text/css" href="<?= e($base) ?>css/style.css">
</head>
<body>

<header class="topo">
    <a class="logo" href="<?= e($base) ?>index.php">Adoção <small>de animais</small></a>

    <nav>
        <a href="<?= e($base) ?>index.php">Início</a>
        <a href="<?= e($base) ?>animais.php">Animais</a>

        <?php if (ehAdmin()): ?>
            <a href="<?= e($base) ?>admin/index.php">Painel admin</a>
            <a href="<?= e($base) ?>admin/animais/index.php">Gerenciar animais</a>
            <a href="<?= e($base) ?>admin/visitas.php">Gerenciar visitas</a>
        <?php endif; ?>

        <?php if (estaLogado()): ?>
            <a href="<?= e($base) ?>minhas-visitas.php">Minhas visitas</a>
            <span class="ola">Olá, <?= e($_SESSION['usuario_nome']) ?></span>
            <a href="<?= e($base) ?>logout.php">Sair</a>
        <?php else: ?>
            <a href="<?= e($base) ?>login.php">Entrar</a>
            <a class="btn-link" href="<?= e($base) ?>cadastro.php">Cadastrar</a>
        <?php endif; ?>
    </nav>
</header>

<main class="conteudo">