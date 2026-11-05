<?php

$host    = 'localhost';
$banco   = 'adocao_animais';
$usuario = 'SEU_USUARIO';
$senha   = 'SUA_SENHA';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$banco;charset=$charset";

$opcoes = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $usuario, $senha, $opcoes);
} catch (PDOException $e) {
    error_log('Erro de conexão: ' . $e->getMessage());
    die('Não foi possível conectar ao banco de dados.');
}