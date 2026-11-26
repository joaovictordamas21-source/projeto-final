<div class="topo"><h1>Painel</h1><a class="btn" href="<?= url('vendas') ?>">+ Nova venda</a></div>
<div class="estatisticas">
  <div class="cartao"><span class="mudo">Vendas de hoje</span><strong><?= dinheiro($hoje['total']) ?></strong><small><?= (int)$hoje['qtd'] ?> venda(s)</small></div>
  <div class="cartao"><span class="mudo">Jogos ativos</span><strong><?= (int)$cont['jogos'] ?></strong><small><?= (int)$cont['unidades'] ?> unidades em estoque</small></div>
  <div class="cartao"><span class="mudo">Fornecedores ativos</span><strong><?= (int)$cont['fornecedores'] ?></strong></div>
  <div class="cartao <?= $baixo ? 'alerta-borda' : '' ?>"><span class="mudo">Estoque baixo</span><strong><?= count($baixo) ?></strong><small><?= $baixo ? 'Repor em breve' : 'Tudo em dia' ?></small></div>
  <div class="cartao"><span class="mudo">Mensagens novas</span><strong><?= (int)$msgs ?></strong><small><a href="<?= url('mensagens') ?>">Abrir mensagens</a></small></div>
</div>
<div class="grade2">
  <div class="cartao"><h2>Mais vendidos</h2><table class="tabela">
    <tr><th>Jogo</th><th>Variação</th><th class="dir">Qtde.</th></tr>
    <?php foreach ($topo as $r): ?><tr><td><?= e($r['jogo']) ?></td><td><?= e($r['plataforma'] . ' · ' . $r['edicao']) ?></td><td class="dir"><?= (int)$r['qtd'] ?></td></tr>
    <?php endforeach; if (!$topo): ?><tr><td colspan="3" class="mudo">Nenhuma venda registrada ainda.</td></tr><?php endif; ?></table></div>
  <div class="cartao"><h2>Vendas recentes</h2><table class="tabela">
    <tr><th>ID</th><th>Cliente</th><th>Data</th><th class="dir">Total</th></tr>
    <?php foreach ($vendas as $r): ?><tr><td>#<?= $r['id'] ?></td><td><?= e($r['cliente']) ?></td><td><?= data_hora($r['criada_em']) ?></td><td class="dir"><?= dinheiro($r['total']) ?></td></tr>
    <?php endforeach; if (!$vendas): ?><tr><td colspan="4" class="mudo">Nenhuma venda registrada ainda.</td></tr><?php endif; ?></table></div>
</div>
