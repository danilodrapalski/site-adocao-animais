<?php
// agendar.php 
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/includes/auth.php';
exigirLogin(''); // visitante vai para o login
require_once __DIR__ . '/includes/animais.php';
require_once __DIR__ . '/includes/visitas.php';

$base = '';

// 1) Qual animal? (agendar.php?id=3)
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header('Location: animais.php');
    exit;
}

$stmt = $pdo->prepare('SELECT id, nome, especie, foto FROM animais WHERE id = ? AND status = ?');
$stmt->execute([$id, 'disponivel']);
$animal = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$animal) {
    header('Location: animais.php');
    exit;
}

$titulo = 'Agendar visita';
$erros  = [];

$hoje   = new DateTimeImmutable('today');
$limite = $hoje->modify('+' . DIAS_MAX_AGENDAMENTO . ' days');

$dataEscolhida    = '';
$horarioEscolhido = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dataEscolhida    = is_string($_POST['data'] ?? null) ? trim($_POST['data']) : '';
    $horarioEscolhido = is_string($_POST['horario'] ?? null) ? trim($_POST['horario']) : '';

    // 2) Validação da data
    $dataObj = null;
    if (!dataValida($dataEscolhida)) {
        $erros[] = 'Escolha uma data válida.';
    } else {
        $dataObj = DateTimeImmutable::createFromFormat('!Y-m-d', $dataEscolhida);
        if ($dataObj < $hoje) {
            $erros[] = 'Não é possível agendar em uma data que já passou.';
        } elseif ($dataObj > $limite) {
            $erros[] = 'Só é possível agendar com até ' . DIAS_MAX_AGENDAMENTO . ' dias de antecedência.';
        }
    }

    // 3) Validação do horário (só os horários oferecidos)
    if (!in_array($horarioEscolhido, HORARIOS_VISITA, true)) {
        $erros[] = 'Escolha um horário válido.';
    }

    // Se a visita é hoje, o horário ainda precisa estar no futuro
    if (!$erros && $dataObj->format('Y-m-d') === $hoje->format('Y-m-d') && $horarioEscolhido <= date('H:i')) {
        $erros[] = 'Esse horário de hoje já passou. Escolha um horário mais tarde ou outro dia.';
    }

    // 4) Regras que dependem do banco
    if (!$erros && usuarioJaTemVisita($pdo, (int) $_SESSION['usuario_id'], (int) $animal['id'])) {
        $erros[] = 'Você já tem uma visita pendente ou confirmada com este animal. Cancele-a em "Minhas visitas" para marcar outra.';
    }

    if (!$erros) {
        try {
            // A transação + FOR UPDATE trava o animal: dois pedidos ao mesmo tempo
            // para o mesmo horário não passam juntos.
            $pdo->beginTransaction();
            $pdo->prepare('SELECT id FROM animais WHERE id = ? FOR UPDATE')->execute([$animal['id']]);

            if (horarioOcupado($pdo, (int) $animal['id'], $dataEscolhida, $horarioEscolhido)) {
                $pdo->rollBack();
                $erros[] = 'Esse horário já está ocupado para este animal. Escolha outro.';
            } else {
                $pdo->prepare(
                    'INSERT INTO visitas (usuario_id, animal_id, data_visita, horario) VALUES (?, ?, ?, ?)'
                )->execute([
                    $_SESSION['usuario_id'], $animal['id'], $dataEscolhida, $horarioEscolhido . ':00',
                ]);
                $pdo->commit();

                $_SESSION['sucesso'] = 'Visita agendada! Ela fica pendente até a confirmação da equipe.';
                header('Location: minhas-visitas.php');
                exit;
            }
        } catch (PDOException $ex) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Erro ao agendar visita: ' . $ex->getMessage());
            $erros[] = 'Não foi possível agendar agora. Tente novamente.';
        }
    }
}

$srcFoto = $animal['foto'] ? 'uploads/' . rawurlencode($animal['foto']) : 'img/sem-foto.svg';

require __DIR__ . '/includes/header.php';
?>

<p><a href="animal.php?id=<?= (int) $animal['id'] ?>">← Voltar para <?= e($animal['nome']) ?></a></p>

<section class="caixa">
    <h1>Agendar visita</h1>

    <div class="resumo-animal">
        <img src="<?= e($srcFoto) ?>" alt="Foto de <?= e($animal['nome']) ?>">
        <p>Você vai conhecer <strong><?= e($animal['nome']) ?></strong>.</p>
    </div>

    <?php if ($erros): ?>
        <div class="alerta erro">
            <ul>
                <?php foreach ($erros as $erro): ?>
                    <li><?= e($erro) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post">
        <label for="data">Data da visita</label>
        <input type="date" id="data" name="data" required
            min="<?= e($hoje->format('Y-m-d')) ?>"
            max="<?= e($limite->format('Y-m-d')) ?>"
            value="<?= e($dataEscolhida) ?>">

        <label for="horario">Horário</label>
        <select id="horario" name="horario" required>
            <option value="">Escolha um horário</option>
            <?php foreach (HORARIOS_VISITA as $h): ?>
                <option value="<?= e($h) ?>" <?= $horarioEscolhido === $h ? 'selected' : '' ?>><?= e($h) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Confirmar agendamento</button>
        <a href="animal.php?id=<?= (int) $animal['id'] ?>">Cancelar</a>
    </form>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>