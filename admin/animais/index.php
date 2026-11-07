<?php
// admin/animais/index.php  (listagem)
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../includes/auth.php';
exigirAdmin('../../');
require_once __DIR__ . '/../../includes/animais.php';

$base   = '../../';
$titulo = 'Gerenciar animais';

// Mensagens de sucesso/erro vindas de outras páginas (mostra uma vez e apaga)
$sucesso = $_SESSION['sucesso'] ?? null;
$erro    = $_SESSION['erro'] ?? null;
unset($_SESSION['sucesso'], $_SESSION['erro']);

$animais = $pdo->query('SELECT * FROM animais ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/../../includes/header.php';
?>

<div class="caixa">
    <h1>Gerenciar animais</h1>

    <?php if ($sucesso): ?><div class="alerta sucesso"><?= e($sucesso) ?></div><?php endif; ?>
    <?php if ($erro): ?><div class="alerta erro"><?= e($erro) ?></div><?php endif; ?>

    <p><a href="cadastrar.php" class="btn">+ Cadastrar animal</a></p>

    <?php if (!$animais): ?>
        <p>Nenhum animal cadastrado ainda.</p>
    <?php else: ?>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>Espécie</th>
                        <th>Porte</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($animais as $a): ?>
                    <tr>
                        <td>
                            <?php if ($a['foto']): ?>
                                <img src="<?= $base ?>uploads/<?= e($a['foto']) ?>" alt="Foto de <?= e($a['nome']) ?>"
                                    style="width:70px; height:70px; object-fit:cover; border-radius:8px;">
                            <?php else: ?>
                                <small>sem foto</small>
                            <?php endif; ?>
                        </td>
                        <td><?= e($a['nome']) ?></td>
                        <td><?= e(ESPECIES[$a['especie']] ?? $a['especie']) ?></td>
                        <td><?= e(PORTES[$a['porte']] ?? $a['porte']) ?></td>
                        <td><?= e(STATUS_ANIMAL[$a['status']] ?? $a['status']) ?></td>
                        <td>
                            <a href="editar.php?id=<?= (int) $a['id'] ?>">Editar</a>
                            <form method="post" action="excluir.php" style="display:inline;"
                                onsubmit="return confirm(<?= e(json_encode('Excluir ' . $a['nome'] . '? Isso também apaga as visitas desse animal.')) ?>);">
                                <input type="hidden" name="id" value="<?= (int) $a['id'] ?>">
                                <button type="submit" class="btn btn-perigo">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>