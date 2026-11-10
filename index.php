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

// Colagem do topo: até 3 dos destaques (com a foto, ou a imagem padrão)
$colagem = array_slice($destaques, 0, 3);

require __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="hero-texto">
        <p class="eyebrow">Adoção responsável</p>
        <h1>Adote um <span class="destaque">amigo</span> e mude duas vidas</h1>
        <p class="hero-lead">Conheça os animais que esperam por um lar, agende uma visita e dê a eles uma nova chance.</p>
        <div class="hero-botoes">
            <a class="btn" href="animais.php">Ver todos os animais</a>
            <?php if (!estaLogado()): ?>
                <a class="btn btn-claro" href="cadastro.php">Criar minha conta</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="hero-fotos" data-n="<?= max(1, count($colagem)) ?>">
        <?php if (!$colagem): ?>
            <div class="hero-foto f1"><img src="img/sem-foto.svg" alt=""></div>
        <?php else: ?>
            <?php foreach ($colagem as $i => $c): ?>
                <a class="hero-foto f<?= $i + 1 ?>" href="animal.php?id=<?= (int) $c['id'] ?>">
                    <img src="<?= e($c['foto'] ? 'uploads/' . rawurlencode($c['foto']) : 'img/sem-foto.svg') ?>" alt="Foto de <?= e($c['nome']) ?>">
                    <span class="hero-nome"><?= e($c['nome']) ?></span>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>

        <svg class="selo-giratorio" viewBox="0 0 120 120" aria-hidden="true">
            <defs><path id="circulo" d="M60,60 m-44,0 a44,44 0 1,1 88,0 a44,44 0 1,1 -88,0"/></defs>
            <circle cx="60" cy="60" r="58" class="selo-fundo"/>
            <text class="selo-texto"><textPath href="#circulo">cuide • adote • acolha • abrigue • </textPath></text>
            <g class="selo-pata">
                <ellipse cx="50" cy="55" rx="3.6" ry="4.6" transform="rotate(-20 50 55)"/>
                <ellipse cx="56.5" cy="49" rx="3.6" ry="4.8"/>
                <ellipse cx="64" cy="49" rx="3.6" ry="4.8"/>
                <ellipse cx="70.5" cy="55" rx="3.6" ry="4.6" transform="rotate(20 70.5 55)"/>
                <path d="M60 57c-6 0-10.5 4.6-10.5 8.600 0 3 2.5 4.4 5.200 4.4 2 0 3.5-.9 5.300-.9s3.300.9 5.300.9c2.700 0 5.200-1.400 5.200-4.4 0-4-4.5-8.600-10.5-8.600z"/>
            </g>
        </svg>
    </div>
</section>

<nav class="especies" aria-label="Buscar por espécie">
    <span>Procurar por</span>
    <?php foreach (ESPECIES as $valor => $rotulo): ?>
        <a href="animais.php?especie=<?= e($valor) ?>"><?= e($rotulo) ?></a>
    <?php endforeach; ?>
</nav>

<section class="secao">
    <div class="secao-titulo">
        <div>
            <p class="eyebrow">Esperando por você</p>
            <h2>Animais em destaque</h2>
        </div>
        <?php if ($destaques): ?>
            <a class="link-seta" href="animais.php">Ver todos os animais</a>
        <?php endif; ?>
    </div>

    <?php if (!$destaques): ?>
        <p class="aviso">Nenhum animal disponível no momento. Volte em breve!</p>
    <?php else: ?>
        <div class="grade-animais">
            <?php foreach ($destaques as $a): ?>
                <?php require __DIR__ . '/includes/card_animal.php'; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="secao">
    <div class="secao-titulo">
        <div>
            <p class="eyebrow">Passo a passo</p>
            <h2>Como funciona</h2>
        </div>
    </div>
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
