<?php $nomesPerfil = ['admin' => 'Administrador', 'vendedor' => 'Vendedor', 'cliente' => 'Cliente']; ?>
<div class="topo"><h1>Usuários</h1></div>
<div class="estatisticas">
  <div class="cartao"><span class="mudo">Total de usuários</span><strong><?= (int)$cont['total'] ?></strong></div>
  <div class="cartao"><span class="mudo">Administradores</span><strong><?= (int)$cont['admin'] ?></strong></div>
  <div class="cartao"><span class="mudo">Vendedores</span><strong><?= (int)$cont['vendedor'] ?></strong></div>
  <div class="cartao"><span class="mudo">Clientes</span><strong><?= (int)$cont['cliente'] ?></strong></div>
</div>
<form method="get" action="<?= url('usuarios') ?>" class="busca">
  <input type="search" name="busca" placeholder="Pesquisar por nome ou e-mail" value="<?= e($busca) ?>">
  <select name="perfil" aria-label="Filtrar por perfil">
    <option value="">Todos os perfis</option>
    <?php foreach ($nomesPerfil as $k => $nome): ?><option value="<?= $k ?>" <?= $filtro === $k ? 'selected' : '' ?>><?= $nome ?></option><?php endforeach; ?>
  </select>
  <button class="btn btn-sec">Filtrar</button>
  <?php if ($busca !== '' || $filtro !== ''): ?><a class="btn btn-sec" href="<?= url('usuarios') ?>">Limpar</a><?php endif; ?>
</form>
<div class="divisao">
  <form method="post" action="<?= url('usuarios/salvar') ?>" class="cartao">
    <?= csrf() ?>
    <h2>Novo usuário da equipe</h2>
    <label>Nome <input name="nome" required></label>
    <label>E-mail <input type="email" name="email" required></label>
    <label>Senha (mínimo 6 caracteres) <input type="password" name="senha" minlength="6" required></label>
    <label>Perfil <select name="perfil"><option value="vendedor">Vendedor</option><option value="admin">Administrador</option></select></label>
    <button class="btn">Criar usuário</button>
    <p class="mudo pequeno">Clientes se cadastram sozinhos na loja, em Criar conta.</p>
  </form>
  <div class="cartao"><h2>Todos os usuários</h2><div class="rolagem"><table class="tabela">
    <tr><th>Usuário</th><th>Perfil</th><th>Atividade</th><th>Status</th><th></th></tr>
    <?php foreach ($lista as $u): ?>
    <tr class="<?= $u['ativo'] ? '' : 'apagada' ?>">
      <td><strong><?= e($u['nome']) ?></strong><br><small class="mudo"><?= e($u['email']) ?></small></td>
      <td><span class="perfil <?= e($u['perfil']) ?>"><?= e($nomesPerfil[$u['perfil']]) ?></span></td>
      <td><?php if ($u['perfil'] === 'cliente'): ?><?= (int)$u['compras'] ?> compra(s)<br><small class="mudo"><?= dinheiro($u['gasto']) ?></small>
        <?php else: ?><?= (int)$u['vendas_balcao'] ?> venda(s) no balcão<?php endif; ?></td>
      <td><span class="pilula <?= $u['ativo'] ? 'ativo' : 'inativo' ?>"><?= $u['ativo'] ? 'ativo' : 'inativo' ?></span></td>
      <td class="acoes"><a class="btn btn-sec btn-sm" href="<?= url('usuarios/editar/' . $u['id']) ?>">Editar</a>
        <form method="post" action="<?= url('usuarios/alternar') ?>" class="embutido"><?= csrf() ?><input type="hidden" name="id" value="<?= $u['id'] ?>">
          <?php if ($u['ativo']): ?><button class="btn btn-perigo btn-sm">Inativar</button><?php else: ?><button class="btn btn-ok btn-sm">Ativar</button><?php endif; ?></form></td>
    </tr>
    <?php endforeach; if (!$lista): ?><tr><td colspan="5" class="mudo">Nenhum usuário encontrado.</td></tr><?php endif; ?>
  </table></div></div>
</div>
