<?php
require_once __DIR__ . '/includes/auth.php';

$base   = '';
$titulo = 'Início';
require __DIR__ . '/includes/header.php';
?>

<section class="caixa">
    <h1>Encontre seu novo melhor amigo 🐶🐱</h1>
    <p>Em breve, aqui você poderá conhecer os animais disponíveis para adoção e agendar uma visita.</p>

    <?php if (!estaLogado()): ?>
        <p><a class="btn" href="cadastro.php">Criar conta</a></p>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>