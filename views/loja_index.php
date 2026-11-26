<h1>Catálogo de jogos</h1>
<form method="get" action="<?= url('loja') ?>" class="busca">
  <?php if ($cat): ?><input type="hidden" name="cat" value="<?= $cat ?>"><?php endif; ?>
  <input type="search" name="busca" placeholder="Pesquisar por jogo, categoria ou plataforma" value="<?= e($busca) ?>">
  <button class="btn">Pesquisar</button>
  <?php if ($busca !== ''): ?><a class="btn btn-sec" href="<?= url('loja' . ($cat ? "?cat=$cat" : '')) ?>">Limpar</a><?php endif; ?>
</form>
<div class="chips">
  <a class="chip <?= !$cat ? 'on' : '' ?>" href="<?= url('loja') ?>">Todas</a>
  <?php foreach ($cats as $c): ?><a class="chip <?= $cat === (int)$c['id'] ? 'on' : '' ?>" href="<?= url('loja?cat=' . $c['id']) ?>"><?= e($c['nome']) ?></a><?php endforeach; ?>
</div>
<div class="vitrine">
<?php foreach ($jogos as $j): ?>
  <article class="jogo">
    <img class="capa" src="<?= e(capa($j['imagem'], $j['id'])) ?>" alt="Capa de <?= e($j['nome']) ?>">
    <div class="jogo-info">
      <small class="mudo"><?= e($j['categoria'] ?? 'Sem categoria') ?></small>
      <h2><?= e($j['nome']) ?></h2>
      <?php if ($j['descricao']): ?><p class="mudo pequeno"><?= e($j['descricao']) ?></p><?php endif; ?>
      <div class="variacoes">
      <?php foreach ($vars[$j['id']] ?? [] as $v): ?>
        <form method="post" action="<?= url('carrinho/adicionar') ?>" class="variacao">
          <?= csrf() ?><input type="hidden" name="variacao" value="<?= $v['id'] ?>">
          <span><?= e($v['plataforma']) ?> · <?= e($v['edicao']) ?><br><strong><?= dinheiro($v['preco']) ?></strong>
            <?php if ($v['estoque'] > 0 && $v['estoque'] <= $v['estoque_minimo']): ?><small class="alerta">restam <?= (int)$v['estoque'] ?></small><?php endif; ?></span>
          <?php if ($v['estoque'] > 0): ?><button class="btn btn-sm">Adicionar</button><?php else: ?><span class="pilula inativo">Esgotado</span><?php endif; ?>
        </form>
      <?php endforeach; ?>
      </div>
    </div>
  </article>
<?php endforeach; if (!$jogos): ?><p class="mudo">Nenhum jogo encontrado. Tente outra categoria ou outra pesquisa.</p><?php endif; ?>
</div>
