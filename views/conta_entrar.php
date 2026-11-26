<section class="login">
  <h1 class="marca-grande">LEVEL<span>ZONE</span></h1>
  <p class="mudo">Entre para comprar, vender e acompanhar seus pedidos.</p>
  <form method="post" class="cartao">
    <?= csrf() ?>
    <label>E-mail <input type="email" name="email" required autofocus></label>
    <label>Senha <input type="password" name="senha" required></label>
    <button class="btn">Entrar</button>
  </form>
  <p>Ainda não tem conta? <a href="<?= url('conta/cadastrar') ?>">Criar conta</a></p>
  <p class="mudo pequeno">Equipe, primeiro acesso: admin@levelzone.com / admin123 · vendedor@levelzone.com / vendedor123</p>
</section>
