<?php
$base = $base ?? '';
$a = $a ?? ['id' => 0, 'foto' => '', 'nome' => '', 'idade' => '', 'porte' => '', 'especie' => ''];
?>
</main>

<footer class="rodape">
    <div class="rodape-in">
        <div class="rodape-marca">
            <p class="rodape-logo">Adoção <em>de Animais</em></p>
            <p>Conheça os animais que esperam por um lar, agende uma visita e dê a eles uma nova chance.</p>
        </div>

        <nav class="rodape-menu" aria-label="Rodapé">
            <a href="<?= e($base) ?>index.php">Início</a>
            <a href="<?= e($base) ?>animais.php">Animais</a>
            <?php if (estaLogado()): ?>
                <a href="<?= e($base) ?>minhas-visitas.php">Minhas visitas</a>
            <?php else: ?>
                <a href="<?= e($base) ?>login.php">Entrar</a>
                <a href="<?= e($base) ?>cadastro.php">Cadastrar</a>
            <?php endif; ?>
        </nav>
    </div>
    <p class="rodape-credito">Projeto de portfólio • Danilo Drapalski</p>
</footer>

</body>
</html>
