<section class="login">
  <h1>Fale conosco</h1>
  <p class="mudo">Dúvidas, trocas ou sugestões? Envie uma mensagem para a equipe da LevelZone.</p>
  <form method="post" class="cartao">
    <?= csrf() ?>
    <label>Nome <input name="nome" required value="<?= e($usuario['nome'] ?? '') ?>"></label>
    <label>E-mail <input type="email" name="email" required value="<?= e($usuario['email'] ?? '') ?>"></label>
    <label>Assunto <input name="assunto" required></label>
    <label>Mensagem <textarea name="texto" rows="5" required></textarea></label>
    <button class="btn">Enviar mensagem</button>
  </form>
</section>
