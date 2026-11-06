<?php
// admin/animais/editar.php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../includes/auth.php';
exigirAdmin('../../');
require_once __DIR__ . '/../../includes/animais.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM animais WHERE id = ?');
$stmt->execute([$id]);
$animal = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$animal) {
    $_SESSION['erro'] = 'Animal não encontrado.';
    header('Location: index.php');
    exit;
}

$base       = '../../';
$titulo     = 'Editar animal';
$textoBotao = 'Salvar alterações';
$fotoAtual  = $animal['foto'];
$erros      = [];

$dados = [
    'nome'      => $animal['nome'],
    'especie'   => $animal['especie'],
    'raca'      => $animal['raca'] ?? '',
    'idade'     => $animal['idade'] ?? '',
    'porte'     => $animal['porte'],
    'sexo'      => $animal['sexo'],
    'descricao' => $animal['descricao'] ?? '',
    'status'    => $animal['status'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$dados, $erros] = validarAnimal($_POST);

    $erroFoto = null;
    $novaFoto = salvarFoto($_FILES['foto'] ?? [], $erroFoto);
    if ($erroFoto) {
        $erros[] = $erroFoto;
    }

    if ($erros) {
        apagarFoto($novaFoto); // descarta a foto nova se algo deu errado
    } else {
        // Se não enviou foto nova, mantém a antiga
        $fotoFinal = $novaFoto ?? $animal['foto'];

        $stmt = $pdo->prepare(
            'UPDATE animais
            SET nome = ?, especie = ?, raca = ?, idade = ?, porte = ?, sexo = ?,
                descricao = ?, foto = ?, status = ?
            WHERE id = ?'
        );
        $stmt->execute([
            $dados['nome'], $dados['especie'], $dados['raca'], $dados['idade'],
            $dados['porte'], $dados['sexo'], $dados['descricao'], $fotoFinal,
            $dados['status'], $id,
        ]);

        // Só apaga a foto antiga DEPOIS de o banco ter sido atualizado
        if ($novaFoto !== null) {
            apagarFoto($animal['foto']);
        }

        $_SESSION['sucesso'] = 'Animal atualizado com sucesso!';
        header('Location: index.php');
        exit;
    }
}

require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/_form.php';
require __DIR__ . '/../../includes/footer.php';