<?php
// admin/visitas.php  (o admin vê, confirma ou recusa as visitas)
require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . '/../includes/auth.php';
exigirAdmin('../');
require_once __DIR__ . '/../includes/visitas.php';

$base   = '../';
$titulo = 'Gerenciar visitas';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idVisita = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $acao     = $_POST['acao'] ?? '';
    $msg      = '';

    if ($idVisita && $acao === 'confirmar') {
        $stmt = $pdo->prepare("UPDATE visitas SET status = 'confirmada' WHERE id = ? AND status = 'pendente'");
        $stmt->execute([$idVisita]);
        $msg = 'Visita confirmada.';
    } elseif ($idVisita && $acao === 'recusar') {
        // Pendente ou confirmada pode ser recusada (o horário volta a ficar livre)
        $stmt = $pdo->prepare("UPDATE visitas SET status = 'recusada' WHERE id = ? AND status IN ('pendente', 'confirmada')");
        $stmt->execute([$idVisita]);
        $msg = 'Visita recusada.';
    } else {
        $stmt = null;
    }

    if ($stmt && $stmt->rowCount() > 0) {
        $_SESSION['sucesso'] = $msg;
    } else {
        $_SESSION['erro'] = 'Não foi possível atualizar essa visita.';
    }

    header('Location: visitas.php');
    exit;
}

$sucesso = $_SESSION['sucesso'] ?? null;
$erro    = $_SESSION['erro'] ?? null;
unset($_SESSION['sucesso'], $_SESSION['erro']);

// Pendentes primeiro, depois por data e horário
$visitas = $pdo->query(
    "SELECT v.id, v.data_visita, v.horario, v.status,
            u.nome AS usuario_nome, u.email AS usuario_email,
            a.nome AS animal_nome
    FROM visitas v
    JOIN usuarios u ON u.id = v.usuario_id
    JOIN animais a  ON a.id = v.animal_id
    ORDER BY FIELD(v.status, 'pendente', 'confirmada', 'recusada', 'cancelada'),
            v.data_visita ASC, v.horario ASC"
)->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/../includes/header.php';
?>

<section class="caixa">
    <h1>Gerenciar visitas</h1>

    <?php if ($sucesso): ?><div class="alerta sucesso"><?= e($sucesso) ?></div><?php endif; ?>
    <?php if ($erro): ?><div class="alerta erro"><?= e($erro) ?></div><?php endif; ?>

    <?php if (!$visitas): ?>
        <p class="aviso">Nenhuma visita agendada ainda.</p>
    <?php else: ?>
        <div class="tabela-rolagem">
            <table>
                <thead>
                    <tr>
                        <th>Animal</th>
                        <th>Visitante</th>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($visitas as $v): ?>
                    <tr>
                        <td><?= e($v['animal_nome']) ?></td>
                        <td>
                            <?= e($v['usuario_nome']) ?><br>
                            <small><?= e($v['usuario_email']) ?></small>
                        </td>
                        <td><?= e(formatarData($v['data_visita'])) ?></td>
                        <td><?= e(formatarHora($v['horario'])) ?></td>
                        <td><?= seloVisita($v['status']) ?></td>
                        <td>
                            <?php if ($v['status'] === 'pendente'): ?>
                                <form method="post">
                                    <input type="hidden" name="id" value="<?= (int) $v['id'] ?>">
                                    <input type="hidden" name="acao" value="confirmar">
                                    <button type="submit" class="btn">Confirmar</button>
                                </form>
                            <?php endif; ?>

                            <?php if (in_array($v['status'], ['pendente', 'confirmada'], true)): ?>
                                <form method="post"
                                    onsubmit="return confirm(<?= e(json_encode('Recusar a visita de ' . $v['usuario_nome'] . '?')) ?>);">
                                    <input type="hidden" name="id" value="<?= (int) $v['id'] ?>">
                                    <input type="hidden" name="acao" value="recusar">
                                    <button type="submit" class="btn btn-perigo">Recusar</button>
                                </form>
                            <?php endif; ?>

                            <?php if (!in_array($v['status'], ['pendente', 'confirmada'], true)): ?>
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

<?php require __DIR__ . '/../includes/footer.php'; ?>