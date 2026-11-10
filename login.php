<?php
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/includes/auth.php';

// Quem já está logado não precisa entrar de novo.
if (estaLogado()) {
    header('Location: index.php');
    exit;
}

$erros = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        $erros[] = 'Preencha o e-mail e a senha.';
    } else {
        // 1) Busca o usuário pelo e-mail
        $stmt = $pdo->prepare('SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        // 2) Confere a senha digitada com o hash salvo no banco
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Gera um novo id de sessão ao logar (evita fixação de sessão)
            session_regenerate_id(true);

            // 3) Guarda os dados do usuário na sessão
            $_SESSION['usuario_id']   = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];

            // 4) Admin vai para o painel; usuário comum vai para a página inicial
            if ($usuario['tipo'] === 'admin') {
                header('Location: admin/index.php');
            } else {
                header('Location: index.php');
            }
            exit;
        }

        $erros[] = 'E-mail ou senha incorretos.';
    }
}

$base   = '';
$titulo = 'Entrar';
require __DIR__ . '/includes/header.php';
?>

<section class="caixa">
    <h1>Entrar</h1>

    <?php if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'ok'): ?>
        <div class="alerta sucesso">Cadastro realizado! Faça login para continuar.</div>
    <?php endif; ?>

    <?php foreach ($erros as $erro): ?>
        <div class="alerta erro"><?= e($erro) ?></div>
    <?php endforeach; ?>

    <form method="post" novalidate>
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" value="<?= e($email) ?>" required>

        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>

        <button type="submit">Entrar</button>
    </form>

    <p class="aviso">Ainda não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>