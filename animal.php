<?php
// animal.php  (página de detalhes, na raiz do projeto)
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/animais.php'; // listas ESPECIES, PORTES etc.

$base = '';

// 1) O id vem da URL (animal.php?id=3) e precisa ser um número inteiro positivo
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header('Location: animais.php');
    exit;
}

// 2) Só mostra animais disponíveis (adotado ou em processo não aparecem, nem pela URL)
$stmt = $pdo->prepare('SELECT * FROM animais WHERE id = ? AND status = ?');
$stmt->execute([$id, 'disponivel']);
$animal = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$animal) {
    header('Location: animais.php');
    exit;
}

$titulo = $animal['nome'];

$srcFoto = $animal['foto']
    ? 'uploads/' . rawurlencode($animal['foto'])
    : 'img/sem-foto.svg';

require __DIR__ . '/includes/header.php';
?>

<p class="voltar"><a href="animais.php">← Voltar para os animais</a></p>

<article class="detalhe">
    <div class="detalhe-foto">
        <img src="<?= e($srcFoto) ?>" alt="Foto de <?= e($animal['nome']) ?>">
    </div>

    <div class="detalhe-info">
        <h1><?= e($animal['nome']) ?></h1>

        <ul class="selos">
            <li class="selo"><?= e(ESPECIES[$animal['especie']] ?? $animal['especie']) ?></li>
            <li class="selo"><?= e(SEXOS[$animal['sexo']] ?? $animal['sexo']) ?></li>
            <li class="selo">Porte <?= e(mb_strtolower(PORTES[$animal['porte']] ?? $animal['porte'])) ?></li>
        </ul>

        <dl class="ficha">
            <?php if (!empty($animal['raca'])): ?>
                <dt>Raça</dt>
                <dd><?= e($animal['raca']) ?></dd>
            <?php endif; ?>

            <?php if (!empty($animal['idade'])): ?>
                <dt>Idade</dt>
                <dd><?= e($animal['idade']) ?></dd>
            <?php endif; ?>

            <dt>Status</dt>
            <dd><?= e(STATUS_ANIMAL[$animal['status']] ?? $animal['status']) ?></dd>
        </dl>

        <?php if (!empty($animal['descricao'])): ?>
            <h2>Sobre <?= e($animal['nome']) ?></h2>
            <p><?= nl2br(e($animal['descricao'])) ?></p>
        <?php endif; ?>

        <div class="agendar">
            <?php if (estaLogado()): ?>
                <a class="btn" href="agendar.php?id=<?= (int) $animal['id'] ?>">Agendar visita</a>
            <?php else: ?>
                <p class="aviso">Para agendar uma visita, <a href="login.php">entre na sua conta</a> ou <a href="cadastro.php">cadastre-se</a>.</p>
            <?php endif; ?>
        </div>
    </div>
</article>

<?php require __DIR__ . '/includes/footer.php'; ?>