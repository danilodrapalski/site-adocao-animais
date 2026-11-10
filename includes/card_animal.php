<?php
// Se por acaso o arquivo for aberto isolado, evita o erro do PHP
$base = $base ?? '';
$a = $a ?? ['id' => 0, 'foto' => '', 'nome' => '', 'idade' => '', 'porte' => '', 'especie' => ''];

$linkDetalhes = $base . 'animal.php?id=' . (int) $a['id'];

$srcFoto = $a['foto']
    ? $base . 'uploads/' . rawurlencode($a['foto'])
    : $base . 'img/sem-foto.svg';

// "2 anos · Médio" (a espécie vai na etiqueta da foto)
$detalhes = array_filter([
    $a['idade'],
    PORTES[$a['porte']] ?? $a['porte'],
]);
?>
<article class="card-animal">
    <a class="card-foto" href="<?= e($linkDetalhes) ?>">
        <img src="<?= e($srcFoto) ?>" alt="Foto de <?= e($a['nome']) ?>" loading="lazy">
        <span class="card-etiqueta"><?= e(ESPECIES[$a['especie']] ?? $a['especie']) ?></span>
    </a>
    <div class="card-info">
        <h3><?= e($a['nome']) ?></h3>
        <p class="card-detalhes"><?= e(implode(' · ', $detalhes)) ?></p>
        <a class="card-link" href="<?= e($linkDetalhes) ?>">Conhecer <?= e($a['nome']) ?> →</a>
    </div>
</article>