<?php
// Funções de sessão e proteção de páginas.
// Inclua este arquivo no início de toda página que usa login:
//   require __DIR__ . '/includes/auth.php';

// 1) Inicia a sessão (só se ainda não foi iniciada)
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,   // JavaScript não consegue ler o cookie da sessão
        'samesite' => 'Lax',  // proteção extra contra requisições de outros sites
    ]);
    session_start();
}

function estaLogado(): bool
{
    return isset($_SESSION['usuario_id']);
}

function ehAdmin(): bool
{
    return estaLogado() && ($_SESSION['usuario_tipo'] ?? '') === 'admin';
}

// 4) Bloqueia a página para quem NÃO está logado.
function exigirLogin(string $base = ''): void
{
    if (!estaLogado()) {
        header('Location: ' . $base . 'login.php');
        exit;
    }
}

// 5) Bloqueia a página para quem NÃO é administrador.
function exigirAdmin(string $base = ''): void
{
    exigirLogin($base);

    if (!ehAdmin()) {
        http_response_code(403);
        die('Acesso negado. Esta área é restrita a administradores.');
    }
}

// 6) Escapa texto antes de mostrar na tela (evita XSS).
function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}
