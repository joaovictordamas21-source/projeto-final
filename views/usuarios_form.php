<div class="topo"><h1>Editar usuário</h1><a class="btn btn-sec" href="<?= url('usuarios') ?>">Voltar para a lista</a></div>
<form method="post" action="<?= url('usuarios/atualizar') ?>" class="cartao" style="max-width:520px">
  <?= csrf() ?><input type="hidden" name="id" value="<?= (int)$alvo['id'] ?>">
  <label>Nome <input name="nome" required value="<?= e($alvo['nome']) ?>"></label>
  <label>E-mail <input type="email" name="email" required value="<?= e($alvo['email']) ?>"></label>
  <label>Perfil
    <select name="perfil">
      <?php foreach (['cliente' => 'Cliente', 'vendedor' => 'Vendedor', 'admin' => 'Administrador'] as $k => $nome): ?>
        <option value="<?= $k ?>" <?= $alvo['perfil'] === $k ? 'selected' : '' ?>><?= $nome ?></option>
      <?php endforeach; ?>
    </select></label>
  <label>Nova senha <small class="mudo">(deixe em branco para manter a atual; mínimo 6 caracteres)</small>
    <input type="password" name="senha" minlength="6" autocomplete="new-password"></label>
  <div class="linha"><button class="btn">Salvar alterações</button><a class="btn btn-sec" href="<?= url('usuarios') ?>">Cancelar</a></div>
</form>
