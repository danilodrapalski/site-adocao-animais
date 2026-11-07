<?php
// animais.php  (listagem pública com filtros, na raiz do projeto)
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/animais.php'; // listas ESPECIES, PORTES etc.

$base   = '';
$titulo = 'Animais para adoção';

// 1) Lê os filtros da URL (?especie=gato&porte=pequeno).
//    Só aceita valores que existem nas listas; qualquer outra coisa vira "sem filtro".
$especie = $_GET['especie'] ?? '';
$porte   = $_GET['porte'] ?? '';

if (!is_string($especie) || !array_key_exists($especie, ESPECIES)) {
    $especie = '';
}
if (!is_string($porte) || !array_key_exists($porte, PORTES)) {
    $porte = '';
}

// 2) Monta a consulta: só animais disponíveis + filtros escolhidos
$sql    = 'SELECT id, nome, especie, idade, porte, sexo, foto
           FROM animais
           WHERE status = ?';
$params = ['disponivel'];

if ($especie !== '') {
    $sql     .= ' AND especie = ?';
    $params[] = $especie;
}
if ($porte !== '') {
    $sql     .= ' AND porte = ?';
    $params[] = $porte;
}

$sql .= ' ORDER BY criado_em DESC, id DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$animais = $stmt->fetchAll(PDO::FETCH_ASSOC);

$temFiltro = ($especie !== '' || $porte !== '');

require __DIR__ . '/includes/header.php';
?>

<h1>Animais para adoção</h1>
<p>Conheça quem está esperando por um lar.</p>

<form method="get" class="filtros">
    <div>
        <label for="especie">Espécie</label>
        <select id="especie" name="especie">
            <option value="">Todas</option>
            <?php foreach (ESPECIES as $valor => $rotulo): ?>
                <option value="<?= e($valor) ?>" <?= $especie === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="porte">Porte</label>
        <select id="porte" name="porte">
            <option value="">Todos</option>
            <?php foreach (PORTES as $valor => $rotulo): ?>
                <option value="<?= e($valor) ?>" <?= $porte === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="filtros-botoes">
        <button type="submit">Filtrar</button>
        <?php if ($temFiltro): ?>
            <a href="animais.php">Limpar filtros</a>
        <?php endif; ?>
    </div>
</form>

<?php if (!$animais): ?>
    <p class="aviso">
        <?= $temFiltro
            ? 'Nenhum animal encontrado com esses filtros. Tente outra combinação.'
            : 'Nenhum animal disponível no momento. Volte em breve!' ?>
    </p>
<?php else: ?>
    <p class="contagem"><?= count($animais) ?> <?= count($animais) === 1 ? 'animal encontrado' : 'animais encontrados' ?></p>
    <div class="grade-animais">
        <?php foreach ($animais as $a): ?>
            <?php require __DIR__ . '/includes/card_animal.php'; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>