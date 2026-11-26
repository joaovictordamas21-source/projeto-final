<div class="topo"><h1>Categorias</h1></div>
<div class="divisao">
  <form method="post" action="<?= url('categorias/salvar') ?>" class="cartao">
    <?= csrf() ?><input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
    <h2><?= $edit ? 'Renomear categoria' : 'Nova categoria' ?></h2>
    <label>Nome <input name="nome" required value="<?= e($edit['nome'] ?? '') ?>"></label>
    <div class="linha"><button class="btn">Salvar categoria</button><a class="btn btn-sec" href="<?= url('categorias') ?>">Limpar</a></div>
  </form>
  <div class="cartao"><h2>Categorias de jogos</h2><table class="tabela">
    <tr><th>Nome</th><th>Jogos</th><th></th></tr>
    <?php foreach ($lista as $c): ?>
    <tr><td><?= e($c['nome']) ?></td><td><?= (int)$c['qtd'] ?></td><td class="acoes">
      <a class="btn btn-sec btn-sm" href="<?= url('categorias/index/' . $c['id']) ?>">Editar</a>
      <form method="post" action="<?= url('categorias/excluir') ?>" class="embutido"><?= csrf() ?><input type="hidden" name="id" value="<?= $c['id'] ?>"><button class="btn btn-perigo btn-sm">Excluir</button></form></td></tr>
    <?php endforeach; ?>
  </table></div>
</div>
