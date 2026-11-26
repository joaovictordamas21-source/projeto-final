<div class="topo"><h1>Produtos</h1><a class="btn" href="<?= url('produtos/novo') ?>">+ Novo jogo</a></div>
<form method="get" action="<?= url('produtos') ?>" class="busca">
  <input type="search" name="busca" placeholder="Pesquisar por jogo, categoria, plataforma ou SKU" value="<?= e($busca) ?>">
  <button class="btn btn-sec">Pesquisar</button>
  <?php if ($busca !== ''): ?><a class="btn btn-sec" href="<?= url('produtos') ?>">Limpar</a><?php endif; ?>
</form>
<div class="cartao"><div class="rolagem"><table class="tabela">
  <tr><th></th><th>SKU</th><th>Jogo</th><th>Categoria</th><th class="dir">Preço</th><th>Estoque</th><th>Status</th><th></th></tr>
  <?php foreach ($lista as $p): ?>
  <tr class="<?= $p['ativo'] ? '' : 'apagada' ?>">
    <td><img class="miniatura" src="<?= e(capa($p['imagem'], $p['id'])) ?>" alt=""></td>
    <td class="mono"><?php foreach (array_filter(explode(',', (string)$p['skus'])) as $s) echo e($s) . '<br>'; ?></td>
    <td><strong><?= e($p['nome']) ?></strong><br><small class="mudo"><?= (int)$p['qtd_var'] ?> variação(ões)</small></td>
    <td class="mudo"><?= e($p['categoria'] ?? '—') ?></td>
    <td class="dir"><?= $p['preco_min'] !== null ? dinheiro($p['preco_min']) : '—' ?></td>
    <td><span class="estoque <?= $p['estoque_total'] == 0 ? 'zero' : 'ok' ?>"><?= (int)$p['estoque_total'] ?></span></td>
    <td><span class="pilula <?= $p['ativo'] ? 'ativo' : 'inativo' ?>"><?= $p['ativo'] ? 'ativo' : 'inativo' ?></span></td>
    <td class="acoes">
      <a class="btn btn-sec btn-sm" href="<?= url('produtos/editar/' . $p['id']) ?>">Editar</a>
      <form method="post" action="<?= url('produtos/alternar') ?>" class="embutido"><?= csrf() ?><input type="hidden" name="id" value="<?= $p['id'] ?>">
        <?php if ($p['ativo']): ?><button class="btn btn-perigo btn-sm">Inativar</button><?php else: ?><button class="btn btn-ok btn-sm">Ativar</button><?php endif; ?></form>
    </td>
  </tr>
  <?php endforeach; if (!$lista): ?><tr><td colspan="8" class="mudo">Nenhum jogo encontrado.</td></tr><?php endif; ?>
</table></div></div>
