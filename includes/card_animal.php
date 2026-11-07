<?php
// includes/card_animal.php
// Card de um animal. Usado na listagem (animais.php) e na página inicial (index.php).
// Variáveis esperadas: $a (linha do animal) e $base.

$linkDetalhes = $base . 'animal.php?id=' . (int) $a['id'];

// Se o animal não tem foto, usa a imagem padrão
$srcFoto = $a['foto']
    ? $base . 'uploads/' . rawurlencode($a['foto'])
    : $base . 'img/sem-foto.svg';

// Monta "Cachorro · 2 anos · Médio", pulando o que estiver vazio
$detalhes = array_filter([
    ESPECIES[$a['especie']] ?? $a['especie'],
    $a['idade'],
    PORTES[$a['porte']] ?? $a['porte'],
]);
?>
<article class="card-animal">
    <a class="card-foto" href="<?= e($linkDetalhes) ?>">
        <img src="<?= e($srcFoto) ?>" alt="Foto de <?= e($a['nome']) ?>" loading="lazy">
    </a>
    <div class="card-info">
        <h3><?= e($a['nome']) ?></h3>
        <p class="card-detalhes"><?= e(implode(' · ', $detalhes)) ?></p>
        <a class="btn" href="<?= e($linkDetalhes) ?>">Ver detalhes</a>
    </div>
</article>