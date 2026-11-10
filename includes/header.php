<?php
// Cabeçalho e menu das páginas.
// Antes de incluir este arquivo, a página precisa:
//   1) já ter incluído o includes/auth.php
//   2) definir $base  -> '' na raiz do projeto, '../' dentro de admin/,
//                        '../../' dentro de admin/animais/
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500..800;1,9..144,500..800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" type="text/css" href="<?= e($base) ?>css/style.css">
</head>
<body>

<header class="topo">
    <div class="topo-in">
        <a class="logo" href="<?= e($base) ?>index.php">
            <svg class="logo-pata" viewBox="0 0 48 48" aria-hidden="true">
                <circle cx="24" cy="24" r="24"/>
                <g fill="currentColor">
                    <ellipse cx="14" cy="21" rx="3.4" ry="4.4" transform="rotate(-20 14 21)"/>
                    <ellipse cx="21" cy="14.5" rx="3.4" ry="4.6"/>
                    <ellipse cx="29" cy="14.5" rx="3.4" ry="4.6"/>
                    <ellipse cx="35.5" cy="21" rx="3.4" ry="4.4" transform="rotate(20 35.5 21)"/>
                    <path d="M24 23c-5.5 0-10 4.6-10 8.6 0 3 2.4 4.4 5 4.4 2 0 3.4-.9 5-.9s3 .9 5 .9c2.6 0 5-1.4 5-4.4 0-4-4.6-8.6-10-8.600z"/>
                </g>
            </svg>
            <span class="logo-texto">Adoção <em>de Animais</em></span>
        </a>

        <input type="checkbox" id="menu-toggle" class="menu-check" aria-label="Abrir ou fechar o menu">
        <label for="menu-toggle" class="menu-botao"><span></span><span></span><span></span></label>

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
    </div>
</header>

<main class="conteudo">
