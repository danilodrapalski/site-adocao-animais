<?php
// admin/animais/_form.php
// Formulário compartilhado por cadastrar.php e editar.php.
// Variáveis esperadas: $dados, $erros, $fotoAtual, $textoBotao, $titulo, $base
?>
<div class="caixa">
    <h1><?= e($titulo) ?></h1>

    <?php if ($erros): ?>
        <div class="alerta erro">
            <ul>
                <?php foreach ($erros as $erro): ?>
                    <li><?= e($erro) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" maxlength="100" required value="<?= e($dados['nome']) ?>">

        <label for="especie">Espécie</label>
        <select id="especie" name="especie">
            <?php foreach (ESPECIES as $valor => $rotulo): ?>
                <option value="<?= e($valor) ?>" <?= $dados['especie'] === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="raca">Raça</label>
        <input type="text" id="raca" name="raca" maxlength="100" value="<?= e($dados['raca']) ?>">

        <label for="idade">Idade (ex.: 2 anos)</label>
        <input type="text" id="idade" name="idade" maxlength="30" value="<?= e($dados['idade']) ?>">

        <label for="porte">Porte</label>
        <select id="porte" name="porte">
            <?php foreach (PORTES as $valor => $rotulo): ?>
                <option value="<?= e($valor) ?>" <?= $dados['porte'] === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="sexo">Sexo</label>
        <select id="sexo" name="sexo">
            <?php foreach (SEXOS as $valor => $rotulo): ?>
                <option value="<?= e($valor) ?>" <?= $dados['sexo'] === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="descricao">Descrição</label>
        <textarea id="descricao" name="descricao" rows="5" maxlength="2000"><?= e($dados['descricao']) ?></textarea>

        <label for="status">Status</label>
        <select id="status" name="status">
            <?php foreach (STATUS_ANIMAL as $valor => $rotulo): ?>
                <option value="<?= e($valor) ?>" <?= $dados['status'] === $valor ? 'selected' : '' ?>><?= e($rotulo) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="foto">Foto (JPG ou PNG, até 2 MB)</label>
        <?php if ($fotoAtual): ?>
            <p>Foto atual:<br>
                <img src="<?= $base ?>uploads/<?= e($fotoAtual) ?>" alt="Foto atual de <?= e($dados['nome']) ?>" style="max-width:200px; border-radius:8px;">
            </p>
            <small>Se você não escolher outra foto, a atual será mantida.</small>
        <?php endif; ?>
        <input type="file" id="foto" name="foto" accept="image/jpeg,image/png">

        <button type="submit" class="btn"><?= e($textoBotao) ?></button>
        <a href="index.php">Cancelar</a>
    </form>
</div>