<?php
// admin/animais/cadastrar.php
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../includes/auth.php';
exigirAdmin('../../');
require_once __DIR__ . '/../../includes/animais.php';

$base       = '../../';
$titulo     = 'Cadastrar animal';
$textoBotao = 'Cadastrar';
$fotoAtual  = null;
$erros      = [];

$dados = [
    'nome' => '', 'especie' => 'cachorro', 'raca' => '', 'idade' => '',
    'porte' => 'pequeno', 'sexo' => 'macho', 'descricao' => '', 'status' => 'disponivel',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$dados, $erros] = validarAnimal($_POST);

    $erroFoto = null;
    $foto     = salvarFoto($_FILES['foto'] ?? [], $erroFoto);
    if ($erroFoto) {
        $erros[] = $erroFoto;
    }

    if ($erros) {
        // Deu erro: se a foto chegou a ser salva, apaga (a pessoa escolhe de novo)
        apagarFoto($foto);
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO animais (nome, especie, raca, idade, porte, sexo, descricao, foto, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $dados['nome'], $dados['especie'], $dados['raca'], $dados['idade'],
            $dados['porte'], $dados['sexo'], $dados['descricao'], $foto, $dados['status'],
        ]);

        $_SESSION['sucesso'] = 'Animal cadastrado com sucesso!';
        header('Location: index.php');
        exit;
    }
}

require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/_form.php';
require __DIR__ . '/../../includes/footer.php';