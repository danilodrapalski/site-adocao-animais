<?php
// index.php  (página inicial, na raiz do projeto)
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/animais.php'; // listas ESPECIES, PORTES etc.

$base   = '';
$titulo = 'Início';

// Destaques: os 4 animais disponíveis cadastrados mais recentemente
$stmt = $pdo->prepare(
    'SELECT id, nome, especie, idade, porte, sexo, foto
    FROM animais
    WHERE status = ?
    ORDER BY criado_em DESC, id DESC
    LIMIT 4'
);
$stmt->execute(['disponivel']);
$destaques = $stmt->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <h1>Adote um amigo e mude duas vidas 🐾</h1>
    <p>Conheça os animais que esperam por um lar, agende uma visita e dê a eles uma nova chance.</p>
    <a class="btn" href="animais.php">Ver todos os animais</a>
    <?php if (!estaLogado()): ?>
        <a class="btn" href="cadastro.php">Criar minha conta</a>
    <?php endif; ?>
</section>

<section>
    <h2>Animais em destaque</h2>

    <?php if (!$destaques): ?>
        <p class="aviso">Nenhum animal disponível no momento. Volte em breve!</p>
    <?php else: ?>
        <div class="grade-animais">
            <?php foreach ($destaques as $a): ?>
                <?php require __DIR__ . '/includes/card_animal.php'; ?>
            <?php endforeach; ?>
        </div>
        <p><a href="animais.php">Ver todos os animais →</a></p>
    <?php endif; ?>
</section>

<section>
    <h2>Como funciona</h2>
    <div class="passos">
        <div class="passo">
            <span class="passo-numero">1</span>
            <h3>Escolha</h3>
            <p>Veja os animais disponíveis e leia a história de cada um.</p>
        </div>
        <div class="passo">
            <span class="passo-numero">2</span>
            <h3>Agende</h3>
            <p>Crie sua conta e marque uma visita para conhecer o animal pessoalmente.</p>
        </div>
        <div class="passo">
            <span class="passo-numero">3</span>
            <h3>Adote</h3>
            <p>Depois da visita confirmada, é só seguir com a adoção.</p>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>