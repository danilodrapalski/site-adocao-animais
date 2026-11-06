<?php
require_once __DIR__ . '/config/conexao.php';
require_once __DIR__ . '/includes/auth.php';

// Quem já está logado não precisa se cadastrar.
if (estaLogado()) {
    header('Location: index.php');
    exit;
}

$erros = [];
$nome  = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome      = trim($_POST['nome'] ?? '');
    $email     = strtolower(trim($_POST['email'] ?? ''));
    $senha     = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    // 1) Validações
    if (mb_strlen($nome) < 2 || mb_strlen($nome) > 100) {
        $erros[] = 'Informe um nome com 2 a 100 caracteres.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
        $erros[] = 'Informe um e-mail válido.';
    }
    if (strlen($senha) < 8) {
        $erros[] = 'A senha precisa ter pelo menos 8 caracteres.';
    }
    if (strlen($senha) > 72) {
        $erros[] = 'A senha pode ter no máximo 72 caracteres.';
    }
    if ($senha !== $confirmar) {
        $erros[] = 'As senhas não conferem.';
    }

    // 2) Se está tudo certo, verifica se o e-mail já existe
    if (!$erros) {
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $erros[] = 'Este e-mail já está cadastrado.';
        } else {
            // 3) Salva o usuário com a senha criptografada
            $hash = password_hash($senha, PASSWORD_DEFAULT);

            // O tipo NÃO vem do formulário: fica o padrão 'usuario' do banco.
            $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)');
            $stmt->execute([$nome, $email, $hash]);

            header('Location: login.php?cadastro=ok');
            exit;
        }
    }
}

$base   = '';
$titulo = 'Cadastro';
require __DIR__ . '/includes/header.php';
?>

<section class="caixa">
    <h1>Criar conta</h1>

    <?php foreach ($erros as $erro): ?>
        <div class="alerta erro"><?= e($erro) ?></div>
    <?php endforeach; ?>

    <form method="post" novalidate>
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" value="<?= e($nome) ?>" required>

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" value="<?= e($email) ?>" required>

        <label for="senha">Senha (mínimo 8 caracteres)</label>
        <input type="password" id="senha" name="senha" required>

        <label for="confirmar">Confirmar senha</label>
        <input type="password" id="confirmar" name="confirmar" required>

        <button type="submit">Cadastrar</button>
    </form>

    <p class="aviso">Já tem conta? <a href="login.php">Entrar</a></p>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>