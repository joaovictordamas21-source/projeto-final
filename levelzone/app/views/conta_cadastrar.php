<?php $dados = $dados ?? []; $pf = $dados['perfil'] ?? 'cliente'; ?>
<section class="login">
  <h1>Criar conta</h1>
  <p class="mudo">Escolha seu perfil. Clientes informam o CEP; vendedor e administrador precisam do código da equipe.</p>
  <form method="post" class="cartao">
    <?= csrf() ?>
    <button class="fora-da-tela" name="acao" value="criar" tabindex="-1" aria-hidden="true">Criar</button>
    <label>Perfil
      <select name="perfil"><option value="cliente" <?= $pf === 'cliente' ? 'selected' : '' ?>>Cliente</option><option value="vendedor" <?= $pf === 'vendedor' ? 'selected' : '' ?>>Vendedor</option><option value="admin" <?= $pf === 'admin' ? 'selected' : '' ?>>Administrador</option></select></label>
    <label>Código da equipe <small class="mudo">(só para vendedor ou administrador)</small> <input name="codigo" autocomplete="off" value="<?= e($dados['codigo'] ?? '') ?>"></label>
    <label>Nome <input name="nome" required value="<?= e($dados['nome'] ?? '') ?>"></label>
    <label>E-mail <input type="email" name="email" required value="<?= e($dados['email'] ?? '') ?>"></label>
    <label>CEP <small class="mudo">(obrigatório para clientes)</small> <input name="cep" maxlength="9" inputmode="numeric" placeholder="00000-000" autocomplete="postal-code" value="<?= e($dados['cep'] ?? '') ?>"></label>
    <button class="btn btn-sec btn-sm" name="acao" value="cep" formnovalidate>Buscar endereço pelo CEP</button>
    <label style="margin-top:12px">Endereço <textarea name="endereco" rows="2" autocomplete="street-address"><?= e($dados['endereco'] ?? '') ?></textarea></label>
    <label>Número e complemento <input name="numero" placeholder="Ex.: 123, apto 4" value="<?= e($dados['numero'] ?? '') ?>"></label>
    <label>Senha (mínimo 6 caracteres) <input type="password" name="senha" minlength="6" required autocomplete="new-password"></label>
    <label>Repita a senha <input type="password" name="senha2" minlength="6" required autocomplete="new-password"></label>
    <button class="btn" name="acao" value="criar">Criar minha conta</button>
    <p class="mudo pequeno">Depois de buscar o endereço pelo CEP, digite a senha novamente.</p>
  </form>
  <p>Já tem conta? <a href="<?= url('conta/entrar') ?>">Entrar</a></p>
</section>
