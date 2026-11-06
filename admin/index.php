<?php
require_once __DIR__ . '/../includes/auth.php';

// Só administradores entram aqui. O '../' corrige o caminho do redirecionamento.
exigirAdmin('../');

$base   = '../';
$titulo = 'Painel administrativo';
require __DIR__ . '/../includes/header.php';
?>

<section class="caixa">
    <h1>Painel administrativo</h1>
    <p>Bem-vindo, <?= e($_SESSION['usuario_nome']) ?>! Em breve, aqui você vai gerenciar os animais e as visitas.</p>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>