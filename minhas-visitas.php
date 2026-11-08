<?php
// minhas-visitas.php  (o usuário vê e cancela as próprias visitas)
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/includes/auth.php';
exigirLogin('');
require_once __DIR__ . '/includes/visitas.php';

$base   = '';
$titulo = 'Minhas visitas';

// Cancelar (só por POST, e só a visita é do próprio usuário)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idVisita = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($idVisita) {
        $stmt = $pdo->prepare(
            "UPDATE visitas SET status = 'cancelada'
             WHERE id = ? AND usuario_id = ?
               AND status IN ('pendente', 'confirmada')
               AND data_visita >= ?"
        );
        $stmt->execute([$idVisita, $_SESSION['usuario_id'], date('Y-m-d')]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['sucesso'] = 'Visita cancelada.';
        } else {
            $_SESSION['erro'] = 'Não foi possível cancelar essa visita.';
        }
    }

    header('Location: minhas-visitas.php');
    exit;
}

$sucesso = $_SESSION['sucesso'] ?? null;
$erro    = $_SESSION['erro'] ?? null;
unset($_SESSION['sucesso'], $_SESSION['erro']);

$stmt = $pdo->prepare(
    'SELECT v.id, v.data_visita, v.horario, v.status,
            a.id AS animal_id, a.nome AS animal_nome, a.status AS animal_status
    FROM visitas v
    JOIN animais a ON a.id = v.animal_id
    WHERE v.usuario_id = ?
    ORDER BY v.data_visita DESC, v.horario DESC'
);
$stmt->execute([$_SESSION['usuario_id']]);
$visitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$hoje = date('Y-m-d');

require __DIR__ . '/includes/header.php';
?>

<section class="caixa">
    <h1>Minhas visitas</h1>

    <?php if ($sucesso): ?><div class="alerta sucesso"><?= e($sucesso) ?></div><?php endif; ?>
    <?php if ($erro): ?><div class="alerta erro"><?= e($erro) ?></div><?php endif; ?>

    <?php if (!$visitas): ?>
        <p class="aviso">Você ainda não agendou nenhuma visita. <a href="animais.php">Conheça os animais</a> e marque a primeira!</p>
    <?php else: ?>
        <div class="tabela-rolagem">
            <table>
                <thead>
                    <tr>
                        <th>Animal</th>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Status</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($visitas as $v): ?>
                    <?php $podeCancelar = in_array($v['status'], ['pendente', 'confirmada'], true) && $v['data_visita'] >= $hoje; ?>
                    <tr>
                        <td>
                            <?php if ($v['animal_status'] === 'disponivel'): ?>
                                <a href="animal.php?id=<?= (int) $v['animal_id'] ?>"><?= e($v['animal_nome']) ?></a>
                            <?php else: ?>
                                <?= e($v['animal_nome']) ?>
                            <?php endif; ?>
                        </td>
                        <td><?= e(formatarData($v['data_visita'])) ?></td>
                        <td><?= e(formatarHora($v['horario'])) ?></td>
                        <td><?= seloVisita($v['status']) ?></td>
                        <td>
                            <?php if ($podeCancelar): ?>
                                <form method="post"
                                    onsubmit="return confirm(<?= e(json_encode('Cancelar a visita a ' . $v['animal_nome'] . '?')) ?>);">
                                    <input type="hidden" name="id" value="<?= (int) $v['id'] ?>">
                                    <button type="submit" class="btn btn-perigo">Cancelar</button>
                                </form>
                            <?php else: ?>
                                <small>—</small>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>