<?php
// includes/animais.php
// Funções do CRUD de animais: listas de opções, validação, upload e exclusão de foto.

const ESPECIES      = ['cachorro' => 'Cachorro', 'gato' => 'Gato', 'outro' => 'Outro'];
const PORTES        = ['pequeno' => 'Pequeno', 'medio' => 'Médio', 'grande' => 'Grande'];
const SEXOS         = ['macho' => 'Macho', 'femea' => 'Fêmea'];
const STATUS_ANIMAL = ['disponivel' => 'Disponível', 'em_processo' => 'Em processo', 'adotado' => 'Adotado'];

const TAMANHO_MAX_FOTO = 2 * 1024 * 1024; // 2 MB

/**
 * Limpa e valida os dados vindos do formulário.
 * Retorna [$dados, $erros].
 */
function validarAnimal(array $post): array
{
    $dados = [
        'nome'      => trim($post['nome'] ?? ''),
        'especie'   => $post['especie'] ?? '',
        'raca'      => trim($post['raca'] ?? ''),
        'idade'     => trim($post['idade'] ?? ''),
        'porte'     => $post['porte'] ?? '',
        'sexo'      => $post['sexo'] ?? '',
        'descricao' => trim($post['descricao'] ?? ''),
        'status'    => $post['status'] ?? '',
    ];

    $erros = [];

    if ($dados['nome'] === '' || mb_strlen($dados['nome']) > 100) {
        $erros[] = 'Informe o nome do animal (até 100 caracteres).';
    }
    if (!array_key_exists($dados['especie'], ESPECIES)) {
        $erros[] = 'Escolha uma espécie válida.';
    }
    if (mb_strlen($dados['raca']) > 100) {
        $erros[] = 'A raça pode ter no máximo 100 caracteres.';
    }
    if (mb_strlen($dados['idade']) > 50) {
        $erros[] = 'A idade pode ter no máximo 50 caracteres.';
    }
    if (!array_key_exists($dados['porte'], PORTES)) {
        $erros[] = 'Escolha um porte válido.';
    }
    if (!array_key_exists($dados['sexo'], SEXOS)) {
        $erros[] = 'Escolha o sexo do animal.';
    }
    if (mb_strlen($dados['descricao']) > 2000) {
        $erros[] = 'A descrição pode ter no máximo 2000 caracteres.';
    }
    if (!array_key_exists($dados['status'], STATUS_ANIMAL)) {
        $erros[] = 'Escolha um status válido.';
    }

    return [$dados, $erros];
}

/**
 * Valida e salva a foto enviada.
 * - Retorna o nome do arquivo salvo (ex.: "a1b2c3...jpg"), ou
 * - null se nenhuma foto foi enviada (sem erro) ou se deu erro ($erro preenchido).
 */
function salvarFoto(array $arquivo, ?string &$erro = null): ?string
{
    $erro = null;

    // Nenhum arquivo enviado: não é erro (na edição, mantém a foto antiga)
    if (!isset($arquivo['error']) || $arquivo['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        $erro = ($arquivo['error'] === UPLOAD_ERR_INI_SIZE || $arquivo['error'] === UPLOAD_ERR_FORM_SIZE)
            ? 'A foto é grande demais. O limite é 2 MB.'
            : 'Não foi possível enviar a foto. Tente novamente.';
        return null;
    }

    if ($arquivo['size'] > TAMANHO_MAX_FOTO) {
        $erro = 'A foto é grande demais. O limite é 2 MB.';
        return null;
    }

    // Confere o conteúdo REAL do arquivo (não confia na extensão nem no tipo enviado pelo navegador)
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($arquivo['tmp_name']);

    $permitidos = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
    ];

    if (!isset($permitidos[$mime]) || @getimagesize($arquivo['tmp_name']) === false) {
        $erro = 'A foto precisa ser uma imagem JPG ou PNG válida.';
        return null;
    }

    // Nome aleatório: evita nomes repetidos e nomes maliciosos
    $nomeArquivo = bin2hex(random_bytes(16)) . '.' . $permitidos[$mime];
    $destino     = __DIR__ . '/../uploads/' . $nomeArquivo;

    if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
        $erro = 'Não foi possível salvar a foto no servidor.';
        return null;
    }

    return $nomeArquivo;
}

/** Apaga uma foto da pasta uploads/ (se existir). */
function apagarFoto(?string $foto): void
{
    if (!$foto) {
        return;
    }
    // basename() garante que só mexemos dentro de uploads/
    $caminho = __DIR__ . '/../uploads/' . basename($foto);
    if (is_file($caminho)) {
        unlink($caminho);
    }
}