<?php $dados = $dados ?? []; $pf = $dados['perfil'] ?? 'cliente'; ?>
<section class="login">
  <h1>Criar conta</h1>
  <p class="mudo">Escolha seu perfil. Vendedor e administrador precisam do código da equipe.</p>
  <form method="post" class="cartao">
    <?= csrf() ?>
    <label>Perfil
      <select name="perfil"><option value="cliente" <?= $pf === 'cliente' ? 'selected' : '' ?>>Cliente</option><option value="vendedor" <?= $pf === 'vendedor' ? 'selected' : '' ?>>Vendedor</option><option value="admin" <?= $pf === 'admin' ? 'selected' : '' ?>>Administrador</option></select></label>
    <label>Código da equipe <small class="mudo">(só para vendedor ou administrador)</small> <input name="codigo" autocomplete="off"></label>
    <label>Nome <input name="nome" required value="<?= e($dados['nome'] ?? '') ?>"></label>
    <label>E-mail <input type="email" name="email" required value="<?= e($dados['email'] ?? '') ?>"></label>
    <label>Senha (mínimo 6 caracteres) <input type="password" name="senha" minlength="6" required></label>
    <label>Repita a senha <input type="password" name="senha2" minlength="6" required></label>
    <button class="btn">Criar minha conta</button>
  </form>
  <p>Já tem conta? <a href="<?= url('conta/entrar') ?>">Entrar</a></p>
</section>
