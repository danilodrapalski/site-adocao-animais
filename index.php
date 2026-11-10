<?php
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/animais.php';

$base   = '';
$titulo = 'Início';

// Destaques: os 4 animais disponíveis mais recentes
$stmt = $pdo->prepare(
    'SELECT id, nome, especie, idade, porte, sexo, foto
    FROM animais
    WHERE status = ?
    ORDER BY criado_em DESC, id DESC
    LIMIT 4'
);
$stmt->execute(['disponivel']);
$destaques = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total de animais disponíveis
$stmt = $pdo->prepare('SELECT COUNT(*) FROM animais WHERE status = ?');
$stmt->execute(['disponivel']);
$totalDisponiveis = (int) $stmt->fetchColumn();

// O primeiro destaque vira a foto grande do topo
$capa = $destaques[0] ?? null;
$srcCapa = ($capa && $capa['foto'])
    ? 'uploads/' . rawurlencode($capa['foto'])
    : 'img/sem-foto.svg';

require __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="hero-texto">
        <h1>Adote um amigo e <em>mude duas vidas.</em></h1>
        <p class="hero-lead">Conheça os animais que esperam por um lar, agende uma visita e dê a eles uma nova chance.</p>
        <div class="hero-botoes">
            <a class="btn" href="animais.php">Ver todos os animais</a>
            <?php if (!estaLogado()): ?>
                <a class="btn btn-claro" href="cadastro.php">Criar minha conta</a>
            <?php endif; ?>
        </div>
        <?php if ($totalDisponiveis > 0): ?>
            <p class="hero-numero">
                <strong><?= $totalDisponiveis ?></strong>
                <?= $totalDisponiveis === 1 ? 'animal esperando' : 'animais esperando' ?> por um lar neste momento.
            </p>
        <?php endif; ?>
    </div>

    <figure class="hero-foto">
        <img src="<?= e($srcCapa) ?>" alt="<?= $capa ? 'Foto de ' . e($capa['nome']) : 'Animal para adoção' ?>">
        <?php if ($capa): ?>
            <figcaption>
                <strong><?= e($capa['nome']) ?></strong>
                <a href="animal.php?id=<?= (int) $capa['id'] ?>">Conhecer →</a>
            </figcaption>
        <?php endif; ?>
    </figure>
</section>

<section>
    <div class="secao-titulo">
        <h2>Animais em destaque</h2>
        <a href="animais.php">Ver todos os animais →</a>
    </div>

    <?php if (!$destaques): ?>
        <p class="aviso">Nenhum animal disponível no momento. Volte em breve!</p>
    <?php else: ?>
        <div class="grade-animais destaques">
            <?php foreach ($destaques as $a): ?>
                <?php require __DIR__ . '/includes/card_animal.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="faixa-passos">
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